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
 * Object representing additional authentification data for an agent account
 */
class AdditionalAuthData {

    /** @var string account ID */
    public $accountId;

    /** @var string additional authentication type */
    public $additionalAuthenticationType;

    /** @var string expiration date of the additional authentication */
    public $expirationDate;

    /** @var string message displayed to the user */
    public $message;

    public static function LoadFromJson($jsonData){
        $AdditionalAuthData = new AdditionalAuthData();
        $AdditionalAuthData->accountId = $jsonData->accountId;
        $AdditionalAuthData->additionalAuthenticationType = $jsonData->additionalAuthenticationType;
        $AdditionalAuthData->expirationDate = isset($jsonData->expirationDate) ? $jsonData->expirationDate : null;
        $AdditionalAuthData->message = isset($jsonData->message) ? $jsonData->message : null;
        return $AdditionalAuthData;
    }
}
?>