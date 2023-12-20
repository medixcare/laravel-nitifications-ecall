<?php

namespace NotificationChannels\ECall;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class ECallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->when(ECallChannel::class)
            ->needs(ECall::class)
            ->give(function () {
                $username = config('services.ecall.username');
                $password = config('services.ecall.password');
                $from     = config('services.ecall.from');

                return new ECall(
                    $username,
                    $password,
                    $from,
                    new HttpClient()
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}