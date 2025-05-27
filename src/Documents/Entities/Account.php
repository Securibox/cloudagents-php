<?php
/**
  * Cloud Agents library
  *
  * PHP version 5.4
  *
  * @author    João Rodrigues <joao.rodrigues@securibox.eu>
  * @copyright 2017 Securibox
  * @license   https://opensource.org/licenses/MIT The MIT License
  * @version   GIT: https://github.com/Securibox/cloudagents-php
  * @link      http://packagist.org/packages/securibox/cloudagents
  */

namespace Securibox\CloudAgents\Documents\Entities;
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

    /** @var string ID of the agent that the account belongs to. */
    public $mode;

    /** @var array[Credentials] Set of credentials for this account */
    public $credentials;

    /** @var object[AdditionalAuthData] Additional authentication data for this account */ 
    public $additionalAuthenticationData;

    public static function LoadFromJson($jsonData){
        $account = new Account();
        $account->customerAccountId = $jsonData->customerAccountId;
        $account->customerUserId = $jsonData->customerUserId;
        $account->name = $jsonData->name;
        $account->agentId = $jsonData->agentId;
        $account->mode = Account::accountModeFromInt($jsonData->mode);
        $account->credentials = Credential::LoadFromJsonArray($jsonData->credentials);
        if(isset($jsonData->additionalAuthenticationData)){
            $account->additionalAuthenticationData = AdditionalAuthData::LoadFromJson($jsonData->additionalAuthenticationData);
        } else {
            $account->additionalAuthenticationData = null;
        }
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
    
    private static function accountModeFromInt($intValue){
        switch($intValue){
            case 0:
                return "Enabled";
            case 1:
                return "Disabled";
            case 2:
                return "NoAutomaticSynch";
            case 3:
                return "MfaAutoSynch";
            default:
                throw new \RuntimeException(sprintf('Unsupported accountMode code %d. Try to update SDK!', $intValue));                                                                          
        }
    }
}
?>