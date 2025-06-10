<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
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
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        // URL::forceScheme('https');
        
        // // (Opsional) Redirect jika permintaan tidak HTTPS
        // if (request()->header('x-forwarded-proto') != 'https') {
        //     redirect()->secure(request()->getRequestUri());
        // }
    }
}
