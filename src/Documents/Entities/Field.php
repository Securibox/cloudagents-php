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
 * Object representing an agent field.
 */
class Field {

    /** @var string The name of the field. (maximum length of 128 chars) */
    public $name;

    /** @var int32 The position of the field. */
    public $position;

    /** @var string The hint of the field usually used when the mouse goes over the field to provide more information. (maximum length of 512 chars)  */
    public $hint;   

    /** @var string The regex to validate a field value. (maximum length of 128 chars) */
    public $regex;

    /** @var string The input type that should be used to let the user fill the value (Public, Private or NumberPad). */
    public $fieldInputType;

    /** @var string The value type of the field that should be provided bythe user (Fulltext, Password, Email or Telephone). */
    public $fieldValueType;

    public static function LoadFromJson($jsonData){
        $obj = new Field();
        $obj->name = $jsonData->name;
        $obj->position = $jsonData->position;
        $obj->hint = $jsonData->hint;
        $obj->regex = $jsonData->regex;
        $obj->fieldInputType = Field::fieldInputTypeFromInt($jsonData->fieldInputType);
        $obj->fieldValueType = Field::fieldValueTypeFromInt($jsonData->fieldValueType);
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $fields = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $field = Field::LoadFromJson($jsonObjects[$i]);
            array_push($fields, $field);
        }
        return $fields;
    }

    private static function fieldInputTypeFromInt($intValue){
        switch($intValue){
            case 0:
                return "Public";
            case 1:
                return "Private";            
            case 2:
                return "NumberPad";                                                                            
        }        
    }
    private static function fieldValueTypeFromInt($intValue){
        switch($intValue){
            case 0:
                return "Fulltext";
            case 1:
                return "Password";            
            case 2:
                return "Email";  
            case 3:
                return "Telephone";                                                                                                 
        }        
    }                       
}
?>