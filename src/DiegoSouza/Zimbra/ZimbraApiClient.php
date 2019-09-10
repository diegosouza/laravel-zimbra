<?php

namespace DiegoSouza\Zimbra;

use Illuminate\Log\LogManager;
use Illuminate\Support\Traits\ForwardsCalls;

class ZimbraApiClient
{
    use ForwardsCalls;

    public $api;
    private $logger;
    protected $domain;

    public function __construct($host, $user, $password, LogManager $logger)
    {
        $this->domain = 'santos.sp.gov.br';
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

    public function createDistributionList($name)
    {       
        $dynamic = false;
        $attr = new \Zimbra\Struct\KeyValuePair('', '');       
        return $this->api->createDistributionList("{$name}@{$this->domain}", $dynamic, [$attr]);
    }

    public function deleteDistributionList($id)
    {
        return $this->api->deleteDistributionList($id);
    }

    public function getAllDistributionLists()
    {
        $domainSelector = new \Zimbra\Admin\Struct\DomainSelector(\Zimbra\Enum\DomainBy::NAME(), $this->domain);
        return $this->api->getAllDistributionLists($domainSelector);
    } 

    public function addDistributionListMember($distListId, $users)
    {
        return $this->api->addDistributionListMember($listId, [$user]);
    }

    public function getDistributionListById($id)
    {
        $attr = new \Zimbra\Struct\KeyValuePair('', '');
        $dl = new \Zimbra\Admin\Struct\DistributionListSelector(\Zimbra\Enum\DistributionListBy::ID(), $id);      
        return $this->api->getDistributionList($dl, 0, 0, true, [$attr]);
    }

    public function getDistributionListByName($name)
    {   
        $attr = new \Zimbra\Struct\KeyValuePair('', '');
        $dl = new \Zimbra\Admin\Struct\DistributionListSelector(\Zimbra\Enum\DistributionListBy::NAME(), $name);
        return $this->api->getDistributionList($dl, 0, 0, true, [$attr]);
    }

    public function getDistributionListMembership($distListName)
    {
        $dl = new \Zimbra\Admin\Struct\DistributionListSelector(\Zimbra\Enum\DistributionListBy::Name(), $distListName);
        return $this->api->getDistributionListMembership($dl, 100, 25);
    }

    public function getAllAccounts() {
        $serverSelector = null;
        $domainSelector = new \Zimbra\Admin\Struct\DomainSelector(\Zimbra\Enum\DomainBy::NAME(), $this->domain);
        return $this->api->getAllAccounts($serverSelector, $domainSelector);
    }

    public function modifyCoS($accountId, $cosId)
    {
        $attr = new \Zimbra\Struct\KeyValuePair('zimbraCOSId', $cosId);
        return $this->api->modifyAccount($accountId, [$attr]);
    }

    public function removeDistributionListMember($listId, $member)
    {
        $dlms = [$member];
        $accounts = [];
        return $this->api->removeDistributionListMember($listId, $dlms, $accounts);
    }

    public function renameDistributionList($listId, $newName)
    {
        return $this->api->renameDistributionList($listId, $newname);
    }

    public function getAccountById($id) 
    {
        $apllyCos = null;
        $attr = ['uid','givenName','sn','mail','zimbraMailQuota','zimbraAccountStatus'];
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::ID(), $id);
        return $this->api->getAccount($account, apllyCos, $attr)->account;
    }

    public function getAccountByName($name) 
    {
        $apllyCos = null;
        $attr = ['uid','givenName','sn','mail','zimbraMailQuota','zimbraAccountStatus'];
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::NAME(), $name);
        return $this->api->getAccount($account, $apllyCos, $attr)->account;
    }

    public function getAccountMembershipbyName($name)
    {
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::NAME(), $name);
        return $this->api->getAccountMembership($account);
    }
    
    public function getAccountMembershipbyId($id)
    {
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::ID(), $id);
        return $this->api->getAccountMembership($account);
    }

    public function getAccountInfoById($id) 
    {
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::ID(), $id);
        return $this->api->getAccountInfo($account);
    }

    public function getAccountInfoByName($name) 
    {
        $account = new \Zimbra\Struct\AccountSelector(\Zimbra\Enum\AccountBy::NAME(), $name);
        return $this->api->getAccountInfo($account);
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