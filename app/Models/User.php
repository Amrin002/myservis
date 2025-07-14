<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the user's preference.
     */
    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Get user theme dengan fresh query
     */
    public function getTheme(): string
    {
        // Query fresh dari database untuk memastikan data terbaru
        $preference = UserPreference::where('user_id', $this->id)->first();
        return $preference?->theme ?? 'light';
    }

    /**
     * Check if user prefers dark theme
     */
    public function prefersDarkTheme(): bool
    {
        return $this->getTheme() === 'dark';
    }

    /**
     * Set user theme (helper method)
     */
    public function setTheme(string $theme): bool
    {
        // Validate theme
        if (!in_array($theme, ['light', 'dark'])) {
            return false;
        }

        try {
            UserPreference::updateOrCreate(
                ['user_id' => $this->id],
                ['theme' => $theme]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get theme dengan cache untuk performance
     */
    public function getCachedTheme(): string
    {
        static $cache = [];

        if (!isset($cache[$this->id])) {
            $cache[$this->id] = $this->getTheme();
        }

        return $cache[$this->id];
    }
}
