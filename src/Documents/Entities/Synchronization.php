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
 * Object representing an account synchronization.
 */
class Synchronization {

    /** @var int32 The document identifier. */
    public $id;

    /** @var string The customer account identifier. (maximum length of 128 chars) */
    public $customerAccountId;    

    /** @var boolean Specifies if the synchronization is forced (i.e. downloads all available documents even if they've already been downloaded previously). */
    public $isForced;

    /** @var int32 The number of downloaded documents during this synchronization. */
    public $downloadedDocs;

    /** @var int32 The number of detected documents to be downloaded during this synchronization.  */
    public $detectedDocs;   

    /** @var datetime The synchronization creation date.  */
    public $creationDate;   

    /** @var datetime The starting date for this synchronization. */
    public $startDate;

    /** @var datetime The ending date for this synchronization. */
    public $endDate;

    /** @var datetime The date for which the synchronization has delivered the downloaded the documents to the client. */
    public $deliveryDate; 

    /** @var datetime The date for which the client has acknowledged all documents. */
    public $acknowledgementDate; 

    /** @var string The status of the synchronization (NewAccount, Created, Running, AgentFailed, Delivering, PendingAcknowledgement, Completed or ReportFailed) */
    public $synchronizationState; 

    /** @var string A detailed status of the synchronization (NewAccount, Completed, CompletedNothingToDownload, CompletedWithMissingDocs, ...). */
    public $synchronizationStateDetails; 

    /** @var string The mode of synchronization (NewAccount, Client or Automatic). */
    public $synchronizationMode;

    /** @var string The version of the API. */
    public $apiVersion; 

    /** @var array[Document] The documents downloaded during this synchronization. */
    public $documents;

    public static function LoadFromJson($jsonData){
        $obj = new Synchronization();
        $obj->id = $jsonData->accountId;
        $obj->customerAccountId = $jsonData->customerAccountId;
        $obj->isForced = (bool)$jsonData->isForced;
        $obj->downloadedDocs = (int)$jsonData->downloadedDocs;
        $obj->detectedDocs = (int)$jsonData->detectedDocs;
        $obj->creationDate = new \DateTime($jsonData->creationDate);
        $obj->startDate = new \DateTime($jsonData->startDate);
        $obj->endDate = new \DateTime($jsonData->endDate);
        $obj->deliveryDate = new \DateTime($jsonData->deliveryDate);
        $obj->acknowledgementDate = new \DateTime($jsonData->acknowledgementDate);
        $obj->synchronizationState = Synchronization::synchronizationStateFromInt($jsonData->synchronizationState);
        $obj->synchronizationStateDetails = Synchronization::synchronizationStateDetailsFromInt($jsonData->synchronizationStateDetails);
        $obj->synchronizationMode = $jsonData->synchronizationMode;
        $obj->apiVersion = $jsonData->apiVersion;
        $obj->documents = Document::LoadFromJsonArray($jsonData->documents);
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Synchronization::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }

    private static function synchronizationStateFromInt($intValue){
        switch($intValue){
            case 0:
                return "NewAccount";
            case 1:
                return "Created";            
            case 2:
                return "Running";  
            case 3:
                return "AgentFailed";  
            case 4:
                return "Delivering";  
            case 5:
                return "PendingAcknowledgement";
            case 6:
                return "Completed";  
            case 7:
                return "ReportFailed";                                                                                    
        }
    }

    private static function synchronizationStateDetailsFromInt($intValue){
        switch($intValue){
            case 0:
                return "NewAccount";
            case 1:
                return "Completed";            
            case 2:
                return "CompletedNothingToDownload";  
            case 3:
                return "CompletedNothingNewToDownload";  
            case 4:
                return "CompletedWithMissingDocs";  
            case 5:
                return "CompletedWithErrors";
            case 6:
                return "WrongCredentials";  
            case 7:
                return "UnexpectedAccountData";
            case 8:
                return "Scheduled";
            case 9:
                return "Pending";            
            case 10:
                return "InProgress";  
            case 11:
                return "DematerialisationNeeded";  
            case 12:
                return "CheckAccount";  
            case 13:
                return "AccountBlocked";
            case 14:
                return "AdditionalAuthenticationRequired";  
            case 15:
                return "LoginPageChanged"; 
            case 16:
                return "WelcomePageChanged";
            case 17:
                return "WebsiteInMaintenance";            
            case 18:
                return "WebsiteChanged";  
            case 19:
                return "ResetPasswordWarning";  
            case 20:
                return "ResetPasswordRequired";  
            case 21:
                return "ServerUnavailable";
            case 22:
                return "PersonalNotification";  
            case 23:
                return "TemporaryServerError";
            case 24:
                return "CaptchaFound";                                                                                                                                                      
        }
    }    
}
?>