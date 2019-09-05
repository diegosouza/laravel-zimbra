<?php

namespace DiegoSouza\Zimbra\Facades;

use DiegoSouza\Zimbra\ZimbraApiClient;
use Illuminate\Support\Facades\Facade;

class Zimbra extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ZimbraApiClient::class;
    }
}
