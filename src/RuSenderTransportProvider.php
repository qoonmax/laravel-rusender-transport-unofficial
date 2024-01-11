<?php

namespace Qoonmax\RuSenderApiMailTransport;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Qoonmax\RusenderApiMailTransport\RuSenderTransport\RuSenderTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class RuSenderTransportProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Mail::extend('rusender', function () {
            return (new RuSenderTransportFactory)->create(
                new Dsn(
                    scheme: 'api',
                    host: 'default',
                    password: config('services.rusender.key'),
                )
            );
        });

        $this->publishes([
            __DIR__ . '/../../config/rusender.php' => config_path('rusender.php'),
            __DIR__ . '/RuSenderTransportProvider.php' => app_path('Providers/RuSenderTransportProvider.php'),
        ]);
    }
}
