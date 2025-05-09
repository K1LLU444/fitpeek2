<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;

class SendGridMailerProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MailManager::class)->extend('sendgrid', function (array $config) {
            return new SendgridApiTransport($config['api_key']);
        });
    }    
}
