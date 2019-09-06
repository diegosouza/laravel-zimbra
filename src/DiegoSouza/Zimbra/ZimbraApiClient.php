<?php

namespace DiegoSouza\Zimbra;

use Illuminate\Log\LogManager;
use Illuminate\Support\Traits\ForwardsCalls;

class ZimbraApiClient
{
    use ForwardsCalls;

    public $api;
    private $logger;

    public function __construct($host, $user, $password, LogManager $logger)
    {
        $this->api = \Zimbra\Admin\AdminFactory::instance("https://{$host}:7071/service/admin/soap");
        $this->api->auth($user, $password);

        $this->logger = $logger;

        $this->api->getClient()->on('before.request', function ($request) {
            $this->logger->debug("SOAP REQUEST: {$request}");
        });

        $this->api->getClient()->on('after.request', function ($response) {
            $this->logger->debug("SOAP RESPONSE: {$response}");
        });
    }

    public function getAllCos()
    {
        return $this->api->getAllCos()->cos;
    }

    public function getAllDomains()
    {
        return $this->api->getAllDomains()->domain;
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->api, $method, $parameters);
    }
}
