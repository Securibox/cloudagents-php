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

namespace Securibox\CloudAgents\Documents\Entities;
/**
 * Object representing a credential (username, password, etc...)
 */
class Credential {

    /** @var string credential position */
    public $position;

    /** @var string credential value */
    public $value;

    /** @var string credential encryption algorithm ("none" or "rsa") */
    public $alg;

    public static function LoadFromJson($jsonData){        
        $obj = new Credential();
        $obj->position = $jsonData->position;
        $obj->value = $jsonData->value;
        $obj->alg = $jsonData->alg;
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Credential::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }          
}
?>