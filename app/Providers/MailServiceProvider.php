<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransport;

class MailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app(MailManager::class)->extend('sendgrid', function (array $config) {
            return new SendgridTransport(
                $config['password'],
            );
        });
    }
}
