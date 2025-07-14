<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function servisans()
    {
        return $this->hasMany(Servisan::class);
    }

    /**
     * Get active servisans (menunggu, proses, selesai)
     */
    public function activeServisans()
    {
        return $this->servisans()->whereIn('status', ['menunggu', 'proses', 'selesai']);
    }

    /**
     * Get completed servisans (diambil)
     */
    public function completedServisans()
    {
        return $this->servisans()->where('status', 'diambil');
    }

    /**
     * Get total payment amount (only paid servisans)
     */
    public function getTotalPembayaranAttribute()
    {
        return $this->servisans()
            ->where('lunas', true)
            ->get()
            ->sum(function ($servisan) {
                return $servisan->biaya_akhir ?: $servisan->estimasi_biaya;
            });
    }

    /**
     * Get initials for avatar
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->nama);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->nama, 0, 2));
    }

    /**
     * Get formatted phone number for WhatsApp
     */
    public function getWhatsappLinkAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->no_hp);

        // Convert 08xxx to 628xxx for WhatsApp
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            // Jika langsung 8xxx tanpa 0
            $phone = '62' . $phone;
        } elseif (substr($phone, 0, 3) !== '628') {
            // Jika belum ada kode negara sama sekali
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } else {
                $phone = '62' . $phone;
            }
        }

        return "https://wa.me/{$phone}";
    }

    /**
     * Get formatted phone number for display
     */
    public function getFormattedPhoneAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->no_hp);

        // Format: +62 812-3456-7890
        if (substr($phone, 0, 3) === '628') {
            // Sudah format internasional
            return '+62 ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        } elseif (substr($phone, 0, 2) === '08') {
            // Format lokal 08xxx
            return '+62 ' . substr($phone, 1, 3) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
        } else {
            // Return as is jika format tidak dikenali
            return $this->no_hp;
        }
    }

    /**
     * Get international phone number (without + symbol)
     */
    public function getInternationalPhoneAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->no_hp);

        // Convert ke format 628xxx
        if (substr($phone, 0, 2) === '08') {
            return '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            return '62' . $phone;
        } elseif (substr($phone, 0, 3) === '628') {
            return $phone;
        } else {
            // Default ke 62
            if (substr($phone, 0, 1) === '0') {
                return '62' . substr($phone, 1);
            } else {
                return '62' . $phone;
            }
        }
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Cari atau buat pelanggan berdasarkan nomor HP
     */
    public static function findOrCreatePelanggan($nama, $no_hp, $alamat = null)
    {
        // Cari pelanggan berdasarkan nomor HP
        $pelanggan = self::where('no_hp', $no_hp)->first();

        if ($pelanggan) {
            // Jika pelanggan ditemukan, update datanya
            $pelanggan->update([
                'nama' => $nama,
                'alamat' => $alamat
            ]);
        } else {
            // Jika tidak ditemukan, buat pelanggan baru
            $pelanggan = self::create([
                'nama' => $nama,
                'no_hp' => $no_hp,
                'alamat' => $alamat
            ]);
        }

        return $pelanggan;
    }

    /**
     * Check if pelanggan can be deleted
     */
    public function canBeDeleted()
    {
        return $this->activeServisans()->count() === 0;
    }

    /**
     * Get pelanggan statistics
     */
    public function getStatisticsAttribute()
    {
        return [
            'total_servis' => $this->servisans->count(),
            'aktif' => $this->activeServisans()->count(),
            'selesai' => $this->completedServisans()->count(),
            'dibatalkan' => $this->servisans()->where('status', 'dibatalkan')->count(),
            'lunas' => $this->servisans()->where('lunas', true)->count(),
            'total_pembayaran' => $this->total_pembayaran
        ];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Format phone number before saving
        static::saving(function ($pelanggan) {
            // Clean phone number
            $pelanggan->no_hp = preg_replace('/[^0-9]/', '', $pelanggan->no_hp);

            // Ensure it starts with 0
            if (!empty($pelanggan->no_hp) && !str_starts_with($pelanggan->no_hp, '0')) {
                $pelanggan->no_hp = '0' . $pelanggan->no_hp;
            }
        });
    }
}
