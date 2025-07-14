<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sparepart',
        'nama_sparepart',
        'merk',
        'kategori',
        'deskripsi',
        'stok',
        'harga_beli',
        'harga_jual',
        'status',
        'lokasi_penyimpanan',
        'tanggal_masuk'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer'
    ];

    /**
     * Generate kode sparepart otomatis
     */
    public static function generateKodeSparepart()
    {
        $prefix = 'SPR';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())
            ->latest()
            ->first();

        $number = $last ? intval(substr($last->kode_sparepart, -3)) + 1 : 1;

        return $prefix . $date . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto-generate kode sparepart
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sparepart) {
            if (empty($sparepart->kode_sparepart)) {
                $sparepart->kode_sparepart = static::generateKodeSparepart();
            }
            if (empty($sparepart->tanggal_masuk)) {
                $sparepart->tanggal_masuk = today();
            }
        });
    }

    /**
     * Scope untuk update status berdasarkan stok
     */
    public function scopeUpdateStatus($query)
    {
        return $query->get()->each(function ($sparepart) {
            if ($sparepart->stok == 0) {
                $sparepart->status = 'habis';
            } elseif ($sparepart->stok <= 5) {
                $sparepart->status = 'segera_habis';
            } else {
                $sparepart->status = 'tersedia';
            }
            $sparepart->save();
        });
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('kode_sparepart', 'like', "%{$search}%")
                    ->orWhere('nama_sparepart', 'like', "%{$search}%")
                    ->orWhere('merk', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope untuk filter kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    /**
     * Metode untuk mengurangi stok
     */
    public function kurangiStok($jumlah)
    {
        if ($this->stok >= $jumlah) {
            $this->stok -= $jumlah;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Metode untuk menambah stok
     */
    public function tambahStok($jumlah)
    {
        $this->stok += $jumlah;
        $this->save();
        return true;
    }

    /**
     * Getter untuk status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'tersedia' => 'Tersedia',
            'habis' => 'Habis',
            'segera_habis' => 'Segera Habis'
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Getter untuk status warna bootstrap
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'tersedia' => 'success',
            'habis' => 'danger',
            'segera_habis' => 'warning'
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}
