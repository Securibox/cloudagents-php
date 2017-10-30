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
class Error {

    /** @var int The error code. */
    public $code;

    /** @var string The error message. */
    public $message;

    /** @var string The detailed description of the error  */
    public $description;   

    public static function LoadFromJson($jsonData){
        $jsonData = Utils::camelCaseArrayKeys($jsonData);

        $jsonData = (object)$jsonData;

        $obj = new Error();
        $obj->code = $jsonData->code;
        $obj->message = $jsonData->message;
        $obj->description = $jsonData->description;
        return $obj;
    }               
}
?>