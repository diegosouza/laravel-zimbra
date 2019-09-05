<?php

namespace DiegoSouza\Zimbra;

class ZimbraApiClient
{
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
}
