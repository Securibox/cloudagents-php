<?php
/**
  * Cloud Agents library
  *
  * PHP version 5.4
  *
  * @author    João Rodrigues <joao.rodrigues@securibox.eu>
  * @copyright 2017 Securibox
  * @license   https://opensource.org/licenses/MIT The MIT License
  * @version   GIT: <git_id>
  * @link      http://packagist.org/packages/securibox/cloudagents
  */

namespace Securibox\CloudAgents\Entities;
/**
 * Object representing an agent.
 */
class Agent {

    /** @var guid agent identifier */
    public $id;

    /** @var boolean A value indicating whether this instance is enabled. */
    public $isEnabled;

    /** @var string The agent name. (maximum length of 256 chars) */
    public $name;    

    /** @var string The agent display name. (maximum length of 64 chars) */
    public $displayName;

    /** @var string The agent description. (maximum length of 256 chars) */
    public $description;

    /** @var string The agent periodicity. */
    public $agentPeriodicity;    

    /** @var string The agent current state. */
    public $agentCurrentState;

    /** @var string The agent default category (that was initially specified by Securibox). */
    public $defaultCategory;       

     /** @var guid The agent category identifier. */
    public $categoryId;

     /** @var guid The agent category name. */
    public $category;

    /** @var boolean A value indicating whether this instance is tracked. */
    public $isTracked;

    /** @var array The agent logos. */
    public $logos;

    /** @var string The agent base64 encoded logo. */
    public $base64Logo;

    /** @var array[string] The country codes. */
    public $countryCodes;

    /** @var string The agent login URL. */
    public $url;                    

    /** @var string The document types returned by this agent. */
    public $documentTypes;  

    /** @var The fields that must be filled to perform the login. */
    public $fields;  

    /** @var DateTime The agent creation date. */
    public $creationDate;

    public static function LoadFromJson($jsonData){
        $jsonData = (object)Utils::camelCaseArrayKeys($jsonData);
        $obj = new Agent();
        $obj->id = $jsonData->id;
        $obj->isEnabled = $jsonData->isEnabled;
        $obj->name = $jsonData->name;
        $obj->displayName = $jsonData->displayName;
        $obj->description = $jsonData->description;
        $obj->agentPeriodicity = Agent::periodicityFromInt($jsonData->agentPeriodicity);
        $obj->agentCurrentState = Agent::currentStateFromInt($jsonData->agentCurrentState);
        $obj->defaultCategory = $jsonData->defaultCategory;
        $obj->categoryId = $jsonData->categoryId;
        $obj->category = $jsonData->category;
        $obj->logos = AgentLogo::LoadFromAssociativeArray($jsonData->logos);
        $obj->base64Logo = $jsonData->base64Logo;
        $obj->countryCodes = $jsonData->countryCodes;
        $obj->url = $jsonData->url;
        $obj->documentTypes = $jsonData->documentTypes;
        $obj->fields = Field::LoadFromJsonArray($jsonData->fields);
        $obj->creationDate = $jsonData->creationDate;
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Agent::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }

    private static function currentStateFromInt($intValue){
        switch($intValue){
            case 0:
                return "IsRunning";
            case 1:
                return "InMaintenance";            
            case 2:
                return "Unavailable";  
            case 3:
                return "Zombie";                                                                                  
        }
    }


    private static function periodicityFromInt($intValue){
        switch($intValue){
            case 0:
                return "Undefined";
            case 1:
                return "None";            
            case 2:
                return "Daily";  
            case 3:
                return "Weekly";                                                                                  
            case 4:
                return "Biweekly";                     
            case 5:
                return "Monthly";             
            case 6:
                return "Bimonthly";     
            case 7:
                return "Trimonthly";     
            case 8:
                return "Sixmonthly";
            case 9:
                return "Yearly";                                                     
        }
    }

}
?>