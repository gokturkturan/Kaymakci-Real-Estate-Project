<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
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
        // Testing: force every outgoing e-mail to a single address (config/mail.php).
        if ($redirect = config('mail.redirect_to')) {
            Mail::alwaysTo($redirect);
        }
    }
}
