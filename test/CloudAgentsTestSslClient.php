<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');


use PHPUnit\Framework\TestCase;
use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;

class CloudAgentsTestSslClient extends TestCase{
    private $certificateFilePath = 'C:\Path\to\PEM Certificate';
    private $certificateSecret = 'PEM pass phrase';
    
    public function testGetCategories(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret, "https://sca-multitenant.securibox.eu/api/v1");
        $resp = $client->getCategories();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Category::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    public function testGetSingleAgent(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAgent('d02a3ace21d6439eb9ff2b0138868eb8');
        var_dump($resp->logos);
        $this->assertInstanceOf(Entities\Agent::class, $resp);
        $this->assertObjectHasAttribute('id', $resp);
        $this->assertObjectHasAttribute('name', $resp);
        $this->assertObjectHasAttribute('description', $resp);
        var_dump($resp);
    }   

    public function testGetAgents(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAgents();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    public function testGetAgentByCategoryId(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAgentByCategoryId("f48e0f200113dc9b7dada22d7d2bf6988");
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    
    public function testSearchAgents(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SearchAgents(null, null, "amazon");
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        for($i=0; $i<sizeof($resp); $i++){
            $this->assertContains('amazon', strtolower ($resp[$i]->name));
        }
        var_dump($resp);
    }
    
    public function testCreateAccount(){
        $account = new Entities\Account();
        $account->agentId = 'c42f0150d2eb47ee8fa56bce25e49b8d';
        $account->customerAccountId = 'Account201708082';
        $account->customerUserId = 'User123';
        $account->name = 'Compte de tests 1';
        $account->credentials = array();

        $username = new Entities\Credential();
        $username->position = 0;
        $username->value = 'username@email.com';

        $password = new Entities\Credential();
        $password->position = 1;
        $password->value = 'motdepasse';

        array_push($account->credentials, $username, $password);

        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->CreateAccount($account);
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals($account->customerAccountId, $resp->customerAccountId);
        var_dump($resp);               
    }



    public function testGetAllAccounts(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAllAccounts();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        $this->assertEquals('User123', $resp[0]->customerUserId);
        var_dump($resp);  
    }

    public function testGetAccountsByAgent(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAccountsByAgent('c42f0150d2eb47ee8fa56bce25e49b8d');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        $this->assertEquals('c42f0150d2eb47ee8fa56bce25e49b8d', $resp[0]->agentId);     
        $this->assertEquals('User123', $resp[0]->customerUserId);
        var_dump($resp);          
    }

    public function testGetAccount(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAccount('Account201708082');
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals('c42f0150d2eb47ee8fa56bce25e49b8d', $resp->agentId);     
        $this->assertEquals('User123', $resp->customerUserId);
        var_dump($resp);          
    }
    public function testGetUnexistingAccount(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetAccount('#ID_Does_not_exist');
        $this->assertInstanceOf(Entities\Error::class, $resp);
        var_dump($resp);       
    }
    public function testSearchAccounts(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SearchAccounts(null, 'User1');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        $this->assertEquals('User1', $resp[0]->customerUserId);
        var_dump($resp);    
    }

    public function testModifyAccount(){        
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $account = $client->GetAccount('c');
        $account->name = $account->name.'_2';
        $resp = $client->ModifyAccount('Account_Id', $account);
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals($resp->name, $account->name);
        var_dump($resp); 
    }


    public function testDeleteAccount(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->DeleteAccount('Account_Id');
        $this->assertEquals(true, $resp);    
    }

    public function testSynchronizeAccount(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SynchronizeAccount($accountId, null, true);
        $this->assertInstanceOf(Entities\Synchronization::class, $resp);
        $this->assertEquals($accountId, $resp->customerAccountId);    
    }

    public function testSynchronizeUnexistingAccount(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SynchronizeAccount('Account_Id', null, true);        
        $this->assertInstanceOf(Entities\Error::class, $resp);
        var_dump($resp);
    }

    public function testGetSynchronizationsByAccount(){
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetSynchronizationsByAccount('Account_Id');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Synchronization::class, $resp[0]);
        $this->assertEquals('Account_Id', $resp[0]->customerAccountId);
        var_dump($resp);
    }

    public function testGetLastSynchronizationByAccount(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetLastSynchronizationByAccount($accountId);
        $this->assertInstanceOf(Entities\Synchronization::class, $resp);
        $this->assertEquals($accountId, $resp->customerAccountId);
        var_dump($resp);   
    }

    public function testSearchSynchronizations(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SearchSynchronizations(null, 'Account_Id');
        $this->assertInstanceOf(Entities\Synchronization::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);   
    }

    public function testSearchDocumentsByAccountId(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SearchDocuments($accountId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);   
    }
    public function testSearchDocumentsByUserId(){
        $userId = 'User123';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->SearchDocuments(null, $userId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);
        var_dump($resp);   
    }
    public function testGetDocumentWithoutContent(){
        $documentId = '00001';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetDocument($documentId);
        $this->assertInstanceOf(Entities\Document::class, $resp);
        $this->assertEquals($documentId, $resp->id);
        var_dump($resp);   
    }
    public function testGetDocumentWithContent(){
        $documentId = '00001';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetDocument($documentId, 'true');
        $this->assertInstanceOf(Entities\Document::class, $resp);
        $this->assertNotNull($resp->base64Content);
        var_dump($resp);   
    }

    public function testGetDocumentsByAccount(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->GetDocumentsByAccount($accountId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);          
    }

    public function testAcknowledgeDocumentDelivery(){
        $documentId = '00001';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->AcknowledgeDocumentDelivery($documentId);
        $this->assertEquals(true, $resp);
    }

    public function testAcknowledgeSynchronizationForAccount(){
        $accountId = 'Account_Id';
        $client = ApiClient::SslClientCertificate($this->certificateFilePath, $this->certificateSecret);
        $resp = $client->AcknowledgeSynchronizationForAccount($accountId);
        $this->assertEquals(true, $resp);      
    }                        
}