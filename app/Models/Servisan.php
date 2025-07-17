<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Servisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_servis',
        'pelanggan_id',
        'tipe_barang',
        'merk_barang',
        'model_barang',
        'kerusakan',
        'aksesoris',
        'catatan_teknisi',
        'estimasi_biaya',
        'biaya_akhir',
        'dp',
        'status',
        'lunas',
        'tanggal_masuk',
        'tanggal_selesai',

        // New fillable fields for Tuser
        'tuser_id',
        'is_prioritas',
        'durasi_servis',
        'mulai_servis',
        'last_status_update'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'biaya_akhir' => 'decimal:2',
        'dp' => 'decimal:2',
        'lunas' => 'boolean',
        'is_prioritas' => 'boolean',
        'mulai_servis' => 'datetime',
        'last_status_update' => 'datetime'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // New relationship with Tuser
    public function tuser()
    {
        return $this->belongsTo(Tuser::class, 'tuser_id');
    }
    /**
     * Get status label attribute
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'diambil' => 'Diambil',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status color attribute for Bootstrap badges
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'menunggu' => 'warning',
            'proses' => 'info',
            'selesai' => 'success',
            'diambil' => 'primary',
            'dibatalkan' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status badge attribute (alias for status_color)
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status_color;
    }

    /**
     * Get sisa pembayaran attribute
     */
    public function getSisaPembayaranAttribute()
    {
        $biaya = $this->biaya_akhir ?? $this->estimasi_biaya;
        return $biaya - $this->dp;
    }

    /**
     * Boot method untuk auto-generate kode servis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($servisan) {
            if (empty($servisan->kode_servis)) {
                $servisan->kode_servis = static::generateKodeServis();
            }
            if (empty($servisan->tanggal_masuk)) {
                $servisan->tanggal_masuk = now();
            }
        });
    }

    /**
     * Generate kode servis otomatis
     */
    public static function generateKodeServis()
    {
        $prefix = 'SRV';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())
            ->latest()
            ->first();

        $number = $last ? intval(substr($last->kode_servis, -3)) + 1 : 1;

        return $prefix . $date . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope untuk search
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('kode_servis', 'like', "%{$search}%")
                    ->orWhere('tipe_barang', 'like', "%{$search}%")
                    ->orWhere('merk_barang', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('no_hp', 'like', "%{$search}%");
                    });
            });
        }
        return $query;
    }

    /**
     * Check if servisan can be completed
     */
    public function canBeCompleted()
    {
        return in_array($this->status, ['menunggu', 'proses']);
    }

    /**
     * Check if servisan can be delivered
     */
    public function canBeDelivered()
    {
        return $this->status === 'selesai';
    }

    /**
     * Check if servisan is in progress
     */
    public function isInProgress()
    {
        return in_array($this->status, ['menunggu', 'proses']);
    }

    /**
     * Check if servisan is completed
     */
    public function isCompleted()
    {
        return in_array($this->status, ['selesai', 'diambil']);
    }

    /**
     * Scope untuk servisan yang dapat diambil Tuser
     */
    public function scopeAvailableTuser($query)
    {
        return $query->whereIn('status', ['menunggu', 'proses'])
            ->whereNull('tuser_id');
    }

    /**
     * Scope untuk servisan prioritas
     */
    public function scopePrioritas($query)
    {
        return $query->where('is_prioritas', true);
    }

    /**
     * Cek dan set prioritas servisan
     */
    public function cekPrioritas()
    {
        // Jika sudah lebih dari 2 hari dalam status menunggu
        if (
            $this->status === 'menunggu' &&
            Carbon::parse($this->tanggal_masuk)->diffInDays(now()) > 2
        ) {
            $this->is_prioritas = true;
            $this->save();
        }

        // Jika sudah lebih dari 4 hari dalam proses
        if (
            $this->status === 'proses' &&
            Carbon::parse($this->mulai_servis)->diffInDays(now()) > 4
        ) {
            $this->is_prioritas = true;
            $this->save();
        }

        return $this->is_prioritas;
    }
    /**
     * Update status servisan khusus untuk Tuser
     */
    public function updateStatusTuser($status, $tuser_id = null)
    {
        // Update status
        $this->status = $status;
        $this->last_status_update = now();

        // Set tuser yang mengerjakan jika ada
        if ($tuser_id) {
            $this->tuser_id = $tuser_id;
        }

        // Set waktu mulai atau selesai servis
        if ($status === 'proses') {
            $this->mulai_servis = now();
        } elseif ($status === 'selesai') {
            $this->tanggal_selesai = now();

            // Hitung durasi servis
            if ($this->mulai_servis) {
                $this->durasi_servis = Carbon::parse($this->mulai_servis)
                    ->diffInMinutes(now());
            }
        }

        $this->save();

        // Cek prioritas
        $this->cekPrioritas();

        return $this;
    }
}
