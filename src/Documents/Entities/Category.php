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
 * Object representing an agent logo.
 */
class Category{

    /** @var int32 The actegory identifier. */
    public $id;

    /** @var string The category's name. */
    public $name;

    /** @var string The category's description. */
    public $description;

    public static function LoadFromJson($jsonData){
        $category = new Category();
        $category->id = $jsonData->id;
        $category->name = $jsonData->name;
        $category->description = $jsonData->description;
        return $category;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $objects = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $object = Category::LoadFromJson($jsonObjects[$i]);
            array_push($objects, $object);
        }
        return $objects;
    }     
}
?>