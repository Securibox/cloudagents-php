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
 * Object representing a downloaded document.
 */
class Document {

    /** @var int32 The document identifier. */
    public $id;

    /** @var string The document name. (maximum length of 256 chars) */
    public $name;

    /** @var datetime The date that the syncrhonization has been created. */
    public $synchCreationDate;

    /** @var int32 The account identifier.  */
    public $accountId;   

    /** @var string The customer account identifier. (maximum length of 128 chars) */
    public $customerAccountId;

    /** @var guid The agent identifier. */
    public $agentId;

    /** @var string The MD5 content hash. (maximum length of 128 chars) */
    public $contentHash;

    /** @var array[string] A list of documents metadatas. */
    public $metadatas; 

    /** @var string The document unique identifier. (maximum length of 2048 chars) */
    public $uniqueIdentifier; 

    /** @var string The unique identifier hash algorithm. (maximum length of 64 chars) */
    public $uniqueIdentifierHash; 

    /** @var string The document phase within the handling process (ToParse, ToDeliver, Packaged, Delivered, Acknowledged, AcknowledgementFailed, DeliveryFailed, Completed). */
    public $documentProcessPhase; 

    /** @var int32 The document size in bytes. */
    public $size;

    /** @var datetime The date for which the document has been delivered to the client. */
    public $deliveryDate; 

    /** @var datetime The date for which the document has been acknowledged to the client. */
    public $acknowledgementDate; 

    /** @var string The document content base64 enconded. */
    public $base64Content;

     public static function LoadFromJson($jsonData){
        $obj = new Document();
        $obj->id = $jsonData->id;
        $obj->name = $jsonData->name;
        $obj->synchCreationDate = $jsonData->synchCreationDate;
        $obj->accountId = $jsonData->accountId;
        $obj->customerAccountId = $jsonData->customerAccountId;
        $obj->agentId = $jsonData->agentId;
        $obj->contentHash = $jsonData->contentHash;
        $obj->metadatas = $jsonData->metadatas;
        $obj->uniqueIdentifier = $jsonData->uniqueIdentifier;
        $obj->documentProcessPhase = $jsonData->documentProcessPhase;
        $obj->size = (int)$jsonData->size;
        $obj->deliveryDate = new \DateTime($jsonData->deliveryDate);
        $obj->acknowledgementDate = new \DateTime($jsonData->acknowledgementDate);
        $obj->base64Content = $jsonData->base64Content;
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Document::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }   
}
?>