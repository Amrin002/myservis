<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'theme',
        'preferences'
    ];

    protected $casts = [
        'preferences' => 'array'
    ];

    /**
     * Get the user that owns the preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if dark theme is active
     */
    public function isDarkTheme(): bool
    {
        return $this->theme === 'dark';
    }

    /**
     * Get valid themes
     */
    public static function getValidThemes(): array
    {
        return ['light', 'dark'];
    }

    /**
     * Check if theme is valid
     */
    public static function isValidTheme(string $theme): bool
    {
        return in_array($theme, self::getValidThemes());
    }
}
