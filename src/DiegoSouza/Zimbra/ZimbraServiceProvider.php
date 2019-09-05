<?php

namespace DiegoSouza\Zimbra;

use DiegoSouza\Zimbra\Facades\Zimbra;
use DiegoSouza\Zimbra\ZimbraApiClient;
use Illuminate\Support\ServiceProvider;

class ZimbraServiceProvider extends ServiceProvider
{
    public function register()
    {
        $host = config('zimbra.host');
        $user = config('zimbra.api.user');
        $password = config('zimbra.api.password');

        $this->app->singleton(ZimbraApiClient::class, function () use ($host, $user, $password) {
            return new ZimbraApiClient($host, $user, $password);
        });

        $this->app->alias(ZimbraApiClient::class, 'zimbra');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/zimbra.php' => config_path('zimbra.php'),
        ]);
    }
}
