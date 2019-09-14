<?php

namespace DiegoSouza\Zimbra;

use DiegoSouza\Zimbra\ZimbraApiClient;
use Illuminate\Support\ServiceProvider;

class ZimbraServiceProvider extends ServiceProvider
{
    public function register()
    {
        $host           = config('zimbra.host');
        $emailDomain    = config('zimbra.domain');
        $user           = config('zimbra.api.user');
        $password       = config('zimbra.api.password');
        $logger         = $this->app->log;

        $this->app->singleton(
            ZimbraApiClient::class,
            function () use ($host, $emailDomain, $user, $password, $logger) {
                return new ZimbraApiClient($host, $emailDomain, $user, $password, $logger);
            }
        );

        $this->app->alias(ZimbraApiClient::class, 'zimbra');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/zimbra.php' => config_path('zimbra.php'),
        ]);
    }
}
