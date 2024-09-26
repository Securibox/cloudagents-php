<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');
require('CloudAgentsTestBase.php');

use Securibox\CloudAgents\Documents\ApiClient;

class CloudAgentsTestJwt extends CloudAgentsTestBase{
    
    private $privateKeyFilePath = "C:\Path\to\PEM private key";
    private $privateKeyPassPhrase = "Private key passphrase";

    protected function setUp() : void
    {
        parent::setUp();
        $this->client = ApiClient::Jwt($this->privateKeyFilePath, $this->privateKeyPassPhrase, "https://sca-multitenant.securibox.eu/api/v1");

    }
}