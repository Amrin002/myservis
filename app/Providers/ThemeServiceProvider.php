<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register theme blade directive
        Blade::directive('theme', function ($expression) {
            return "<?php echo auth()->user()?->getTheme() ?? 'light'; ?>";
        });

        Blade::directive('isDarkTheme', function () {
            return "<?php echo auth()->user()?->prefersDarkTheme() ? 'true' : 'false'; ?>";
        });

        // Theme class helper
        Blade::directive('themeClass', function ($expression) {
            return "<?php echo auth()->user()?->prefersDarkTheme() ? 'dark-theme' : 'light-theme'; ?>";
        });
    }
}
