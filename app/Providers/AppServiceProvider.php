<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Localised date + currency Blade directives.
        Blade::directive('greg', fn ($expr) => "<?php echo \App\Support\DateFormatter::gregorian($expr); ?>");
        Blade::directive('hijri', fn ($expr) => "<?php echo \App\Support\DateFormatter::hijri($expr); ?>");
        Blade::directive('dateboth', fn ($expr) => "<?php echo \App\Support\DateFormatter::both($expr); ?>");
        Blade::directive('money', fn ($expr) => "<?php echo \App\Support\DateFormatter::money($expr); ?>");
    }
}
