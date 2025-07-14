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
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'biaya_akhir' => 'decimal:2',
        'dp' => 'decimal:2',
        'lunas' => 'boolean'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
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
}
