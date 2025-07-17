<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Tuser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'address',
        'skill_category'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string'
    ];

    // Mutator untuk password (otomatis hash)
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get formatted phone number for WhatsApp
     */
    public function getWhatsappLinkAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

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
        if (!$this->phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        // Format: +62 812-3456-7890
        if (substr($phone, 0, 3) === '628') {
            // Sudah format internasional
            return '+62 ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        } elseif (substr($phone, 0, 2) === '08') {
            // Format lokal 08xxx
            return '+62 ' . substr($phone, 1, 3) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
        } else {
            // Return as is jika format tidak dikenali
            return $this->phone;
        }
    }

    /**
     * Get international phone number (without + symbol)
     */
    public function getInternationalPhoneAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

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
     * Get initials for avatar
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
    // Relasi dengan Servisan
    public function servisans()
    {
        return $this->hasMany(Servisan::class, 'tuser_id');
    }

    // Metode untuk mengambil servisan yang sedang diproses
    public function servisanDalamProses()
    {
        return $this->servisans()->where('status', 'proses');
    }

    // Metode untuk mengambil servisan yang selesai
    public function servisanSelesai()
    {
        return $this->servisans()->where('status', 'selesai');
    }

    // Metode untuk menghitung jumlah servisan dalam periode tertentu
    public function hitungServisanPeriode($status = null, $periode = 'hari')
    {
        $query = $this->servisans();

        if ($status) {
            $query->where('status', $status);
        }

        switch ($periode) {
            case 'hari':
                $query->whereDate('created_at', today());
                break;
            case 'minggu':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulan':
                $query->whereMonth('created_at', now()->month);
                break;
        }

        return $query->count();
    }
    // Metode untuk dashboard Tuser
    public function getDashboardStatistik()
    {
        return [
            'servisan_hari_ini' => $this->hitungServisanPeriode(null, 'hari'),
            'servisan_minggu_ini' => $this->hitungServisanPeriode(null, 'minggu'),
            'servisan_bulan_ini' => $this->hitungServisanPeriode(null, 'bulan'),
            'servisan_proses' => $this->servisanDalamProses()->count(),
            'servisan_prioritas' => $this->servisans()->where('is_prioritas', true)->count(),
        ];
    }
}
