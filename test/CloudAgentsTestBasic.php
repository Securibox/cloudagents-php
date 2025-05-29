<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');
require('CloudAgentsTestBase.php');

use Securibox\CloudAgents\Documents\ApiClient;

class CloudAgentsTestBasic extends CloudAgentsTestBase{
    
    private $username = "username";
    private $password = "password";

    protected function setUp() : void
    {
        parent::setUp();
        $this->client = ApiClient::AuthenticationBasic($this->username, $this->password, "http://localhost:8080/api/v1");

    }
}