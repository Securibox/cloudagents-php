<?php
/**
  * Cloud Agents library
  *
  * PHP version 5.4
  *
  * @author    João Rodrigues <joao.rodrigues@securibox.eu>
  * @copyright 2017 SendGrid
  * @license   https://opensource.org/licenses/MIT The MIT License
  * @version   GIT: <git_id>
  * @link      http://packagist.org/packages/securibox/cloudagents
  */

namespace Securibox\CloudAgents\Entities;
/**
 * Object representing one agent account of the user
 */
class Account {

    /** @var string account ID (either provided at creation time or automatically generated). */
    public $customerAccountId;

    /** @var string user ID to which this account belongs to. */
    public $customerUserId;

    /** @var string account name */
    public $name;

    /** @var string ID of the agent that the account belongs to. */
    public $agentId;

    /** @var array[Credentials] Set of credentials for this account */
    public $credentials;

    public static function LoadFromJson($jsonData){
        $account = new Account();
        $account->customerAccountId = $jsonData->customerAccountId;
        $account->customerUserId = $jsonData->customerUserId;
        $account->name = $jsonData->name;
        $account->agentId = $jsonData->agentId;
        $account->credentials = Credential::LoadFromJsonArray($jsonData->credentials);
        return $account;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Account::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }        
}