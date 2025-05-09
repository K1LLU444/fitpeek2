<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\SendGridMailerProvider;

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
        (new SendGridMailerProvider($this->app))->boot();
    }
}
