<?php

namespace DiegoSouza\Zimbra;

use Illuminate\Support\Traits\ForwardsCalls;

class ZimbraApiClient
{
    use ForwardsCalls;

    public $api;

    public function __construct($host, $user, $password)
    {
        $this->api = \Zimbra\Admin\AdminFactory::instance("https://{$host}:7071/service/admin/soap");
        $this->api->auth($user, $password);
    }

    public function getAllCos()
    {
        return $this->api->getAllCos()->cos;
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->api, $method, $parameters);
    }
}
