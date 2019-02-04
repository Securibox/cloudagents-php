<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');

use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;
use PHPUnit\Framework\TestCase;

class CloudAgentsWebView extends TestCase{

    public function testGenerateWebViewLink(){
        $pem = file_get_contents("C:\\Temp\\JWT\\key.pem");
        $token = ApiClient::BuildJwt($pem, "password","https://sca-multitenant.securibox.eu", "asdasdasd");
        $url = "https://sca-webview.azurewebsites.net?token=".$token;
        var_dump($url);
    }

}