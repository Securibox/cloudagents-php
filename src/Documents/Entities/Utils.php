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
 * Object with utilities
 */
class Utils{

    public static function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function camelCaseArrayKeys($array){
        $retArray = array();
        foreach($array as $key => $item){
            if(is_object($item) || is_array($item)){
                $item = Utils::camelCaseArrayKeys($item);
                if(!Utils::isAssoc($item)){
                    for($i=0; $i < sizeof($item); $i++){
                        $item[$i] = (object)$item[$i];
                    }
                }
            }
            $retArray[lcfirst($key)] = $item;
        }
        return $retArray;
    }
}
 ?>