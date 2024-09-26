<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');
require('CloudAgentsTestBase.php');

use Securibox\CloudAgents\Documents\ApiClient;

class CloudAgentsTestSslClient extends CloudAgentsTestBase{
    
    private $certificateFilePath = 'C:\Path\to\PEM Certificate';
    private $certificateSecret = 'PEM pass phrase';

    protected function setUp() : void
    {
        parent::setUp();
        $this->client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret, "https://sca-multitenant.securibox.eu/api/v1");

    }      
}