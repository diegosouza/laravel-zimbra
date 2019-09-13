<?php

namespace DiegoSouza\Zimbra;

use Illuminate\Log\LogManager;
use Illuminate\Support\Traits\ForwardsCalls;
use Zimbra\Admin\AdminFactory;
use Zimbra\Admin\Struct\DistributionListSelector;
use Zimbra\Admin\Struct\DomainSelector;
use Zimbra\Enum\AccountBy;
use Zimbra\Enum\DistributionListBy;
use Zimbra\Enum\DomainBy;
use Zimbra\Struct\AccountSelector;
use Zimbra\Struct\KeyValuePair;

class ZimbraApiClient
{
    use ForwardsCalls;

    public $api;
    protected $logger;
    protected $domain;

    public function __construct($host, $emailDomain, $user, $password, LogManager $logger)
    {
        $this->api = AdminFactory::instance("https://{$host}:7071/service/admin/soap");
        $this->api->auth($user, $password);

        $this->domain = $emailDomain;
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
        $attr = $this->createKeyPair();

        return $this->api->createDistributionList("{$name}@{$this->domain}", $dynamic, [$attr]);
    }

    public function deleteDistributionList($id)
    {
        return $this->api->deleteDistributionList($id);
    }

    public function getAllDistributionLists()
    {
        $domainSelector = new DomainSelector(DomainBy::NAME(), $this->domain);
        return $this->api->getAllDistributionLists($domainSelector);
    }

    public function addDistributionListMember($listId, array $users)
    {
        return $this->api->addDistributionListMember($listId, $users);
    }

    public function getDistributionListById($id)
    {
        $limit = 0;
        $offset = 0;
        $sortAscending = true;
        $attr = $this->createKeyPair();

        $dl = new DistributionListSelector(DistributionListBy::ID(), $id);

        return $this->api->getDistributionList($dl, $limit, $offset, $sortAscending, [$attr]);
    }

    public function getDistributionListByName($name)
    {
        $limit = 0;
        $offset = 0;
        $sortAscending = true;
        $attr = $this->createKeyPair();

        $dl = new DistributionListSelector(DistributionListBy::NAME(), $name);

        return $this->api->getDistributionList($dl, $limit, $offset, $sortAscending, [$attr]);
    }

    public function getDistributionListMembership($distListName)
    {
        $limit = 0;
        $offset = 0;

        $dl = new DistributionListSelector(DistributionListBy::Name(), $distListName);

        return $this->api->getDistributionListMembership($dl, $limit, $offset);
    }

    public function getAllAccounts() {
        $serverSelector = null;
        $domainSelector = new DomainSelector(DomainBy::NAME(), $this->domain);

        return $this->api->getAllAccounts($serverSelector, $domainSelector);
    }

    public function modifyCoS($accountId, $cosId)
    {
        $attr = $this->createKeyPair('zimbraCOSId', $cosId);
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
        return $this->api->renameDistributionList($listId, $newName);
    }

    public function getAccountById($id)
    {
        $apllyCos = null;

        $attr = [
            'sn',
            'uid',
            'mail',
            'givenName',
            'zimbraMailQuota',
            'zimbraAccountStatus',
        ];

        $account = new AccountSelector(AccountBy::ID(), $id);

        return $this->api->getAccount($account, $apllyCos, $attr)->account;
    }

    public function getAccountByName($name)
    {
        $apllyCos = null;

        $attr = [
            'sn',
            'uid',
            'mail',
            'givenName',
            'zimbraMailQuota',
            'zimbraAccountStatus',
        ];

        $account = new AccountSelector(AccountBy::NAME(), $name);

        return $this->api->getAccount($account, $apllyCos, $attr)->account;
    }

    public function getAccountMembershipByName($name)
    {
        $account = new AccountSelector(AccountBy::NAME(), $name);
        return $this->api->getAccountMembership($account);
    }

    public function getAccountMembershipbyId($id)
    {
        $account = new AccountSelector(AccountBy::ID(), $id);
        return $this->api->getAccountMembership($account);
    }

    public function getAccountInfoById($id)
    {
        $account = new AccountSelector(AccountBy::ID(), $id);
        return $this->api->getAccountInfo($account);
    }

    public function getAccountInfoByName($name)
    {
        $account = new AccountSelector(AccountBy::NAME(), $name);
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

    private function createKeyPair($key = '', $value = '')
    {
        return new KeyValuePair($key, $value);
    }
}