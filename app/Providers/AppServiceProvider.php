<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Tambahkan ini
use Illuminate\Database\Eloquent\Model;

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
        // Matikan proteksi mass assignment (opsional, tapi sering bikin ribet di production)
        Model::unguard();

        // LOGIC UTAMA: Paksa HTTPS jika di Production
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}