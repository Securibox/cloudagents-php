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

use DateTime;
use RuntimeException;

/**
 * Object representing an account synchronization.
 */
class Synchronization
{
    const SYNCHRONIZATION_STATES = [
        'NewAccount',
        'Created',
        'Running',
        'AgentFailed',
        'Delivering',
        'PendingAcknowledgement',
        'Completed',
        'ReportFailed',
    ];

    const SYNCHRONIZATION_STATE_DETAILS = [
        'NewAccount',
        'Completed',
        'CompletedNothingToDownload',
        'CompletedNothingNewToDownload',
        'CompletedWithMissingDocs',
        'CompletedWithErrors',
        'WrongCredentials',
        'UnexpectedAccountData',
        'Scheduled',
        'Pending',
        'InProgress',
        'DematerialisationNeeded',
        'CheckAccount',
        'AccountBlocked',
        'AdditionalAuthenticationRequired',
        'LoginPageChanged',
        'WelcomePageChanged',
        'WebsiteInMaintenance',
        'WebsiteChanged',
        'ResetPasswordWarning',
        'ResetPasswordRequired',
        'ServerUnavailable',
        'PersonalNotification',
        'TemporaryServerError',
        'CaptchaFound',
        'WrongOptionalCredentials',
        'WrongMFACode',
    ];

    /** @var int The document identifier. */
    public $id;

    /** @var string The customer account identifier. (maximum length of 128 chars) */
    public $customerAccountId;

    /** @var bool Specifies if the synchronization is forced (i.e. downloads all available documents even if they've already been downloaded previously). */
    public $isForced;

    /** @var int The number of downloaded documents during this synchronization. */
    public $downloadedDocs;

    /** @var int The number of detected documents to be downloaded during this synchronization. */
    public $detectedDocs;

    /** @var DateTime The synchronization creation date. */
    public $creationDate;

    /** @var DateTime The starting date for this synchronization. */
    public $startDate;

    /** @var DateTime The ending date for this synchronization. */
    public $endDate;

    /** @var DateTime The date for which the synchronization has delivered the downloaded the documents to the client. */
    public $deliveryDate;

    /** @var DateTime The date for which the client has acknowledged all documents. */
    public $acknowledgementDate;

    /** @var string The status of the synchronization (NewAccount, Created, Running, AgentFailed, Delivering, PendingAcknowledgement, Completed or ReportFailed) */
    public $synchronizationState;

    /** @var string A detailed status of the synchronization (NewAccount, Completed, CompletedNothingToDownload, CompletedWithMissingDocs, ...). */
    public $synchronizationStateDetails;

    /** @var string The mode of synchronization (NewAccount, Client or Automatic). */
    public $synchronizationMode;

    /** @var string The version of the API. */
    public $apiVersion;

    /** @var Document[] The documents downloaded during this synchronization. */
    public $documents;

    public static function LoadFromJsonArray($jsonObjects)
    {
        $objects = array();
        for ($i = 0; $i < sizeof($jsonObjects); $i++) {
            $object = Synchronization::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }

        return $objects;
    }

    public static function LoadFromJson($jsonData)
    {
        $obj = new Synchronization();
        $obj->id = $jsonData->accountId;
        $obj->customerAccountId = $jsonData->customerAccountId;
        $obj->isForced = (bool) $jsonData->isForced;
        $obj->downloadedDocs = (int) $jsonData->downloadedDocs;
        $obj->detectedDocs = (int) $jsonData->detectedDocs;
        $obj->creationDate = new DateTime($jsonData->creationDate);
        $obj->startDate = new DateTime($jsonData->startDate);
        $obj->endDate = new DateTime($jsonData->endDate);
        $obj->deliveryDate = new DateTime($jsonData->deliveryDate);
        $obj->acknowledgementDate = new DateTime($jsonData->acknowledgementDate);
        $obj->synchronizationState = Synchronization::synchronizationStateFromInt($jsonData->synchronizationState);
        $obj->synchronizationStateDetails = Synchronization::synchronizationStateDetailsFromInt($jsonData->synchronizationStateDetails);
        $obj->synchronizationMode = $jsonData->synchronizationMode;
        $obj->apiVersion = $jsonData->apiVersion;
        $obj->documents = Document::LoadFromJsonArray($jsonData->documents);

        return $obj;
    }

    private static function synchronizationStateFromInt($intValue)
    {
        $states = self::SYNCHRONIZATION_STATES;
        if (!isset($states[$intValue])) {
            throw new RuntimeException(sprintf('Unsupported synchronizationState code %d. Try to update SDK!', $intValue));
        }

        return $states[$intValue];
    }

    private static function synchronizationStateDetailsFromInt($intValue)
    {
        $states = self::SYNCHRONIZATION_STATE_DETAILS;
        if (!isset($states[$intValue])) {
            throw new RuntimeException(sprintf('Unsupported synchronizationStateDetails code %d. Try to update SDK!', $intValue));
        }

        return $states[$intValue];
    }
}
