<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');

use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;
use PHPUnit\Framework\TestCase;

class CloudAgentsWebView extends TestCase{

    /**
    * Example to generate webview url
    *
    * This is an example to generate the webview URL with the needed arguments:
    *   - state: data that is sent in argument to the callback url.
    *   - callback: url where the user is sent on logging out (ex: https://www.myapp.com - if a state has been provided: https://www.myapp.com?state={state})
    *   - lang [optional: by default fr-fr]: culture to display the page
    * URL examples:
    *   https://sca-webview.azurewebsites.net?token={token}&callback={callback_url}&state={stateData} => Displays page in french
    *   https://sca-webview.azurewebsites.net?token={token}&callback={callback_url}&state={stateData}&lang=en-us => Displays page in english
    */
    public function testGenerateWebViewLink(){
        $pem = file_get_contents("C:\\Temp\\JWT\\key.pem");
        $token = ApiClient::BuildJwt($pem, "private_key_password", "customer1234");
        $url = "https://sca-webview.azurewebsites.net?token=".$token."&callback=https://www.myapp.com&state=customerData";
        var_dump($url);
    }

}