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
 * Object representing an agent logo.
 */
class AgentLogo{
    /** @var int32 The width of the logo. */
    public $width;

    /** @var int32 The height of the logo. */
    public $height;

    /** @var string The URL to download the logo. */
    public $url;

    public function __construct(){
        $this->height = 0;
        $this->width = 0;
        $this->url = null;
    }

    public static function LoadFromJson($jsonData){
        $obj = new AgentLogo();

        if(isset($jsonData->width))
        {
            $obj->width = $jsonData->width;
        }
            
        if(isset($jsonData->width))
        {
            $obj->height = $jsonData->height;
        }
        if(isset($jsonData->url))
        {
            $obj->url = $jsonData->url;
        }       
        return $obj;
    }

    public static function LoadFromJsonArray($jsonObjects){
        $agentLogos = array();
        for($i = 0; $i < sizeof($jsonObjects); $i++){
            $agentLogo = AgentLogo::LoadFromJson($jsonObjects[$i]);
            array_push($agentLogos, $agentLogo);
        }
        return $agentLogos;
    }

    public static function LoadFromAssociativeArray($jsonObjects){
        $agentLogos = array();
        foreach ($jsonObjects as $key => $value) {
            $agentLogos[$key] = AgentLogo::LoadFromJson($value);
        }
        return $agentLogos;
    }              
}
?>