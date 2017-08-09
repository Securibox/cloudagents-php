<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');



use PHPUnit\Framework\TestCase;
use Securibox;

class CloudAgentsTest extends TestCase{
    private $username = "AssoSynergie";

    private $password = "vgy5DsJj2GXBuQ7D";
    public function testGetCategories(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->getCategories();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Category::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    public function testGetAgents(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAgents();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    public function testGetAgentByCategoryId(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAgentByCategoryId("f48e0f200113dc9b7dada22d7d2bf6988");
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        var_dump($resp);
    }

    
    public function testSearchAgents(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SearchAgents(null, null, "amazon");
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        for($i=0; $i<sizeof($resp); $i++){
            $this->assertContains('amazon', strtolower ($resp[$i]->name));
        }
        var_dump($resp);
    }

    public function testCreateAccount(){
        $account = new \Securibox\CloudAgents\Entities\Account();
        $account->agentId = 'c42f0150d2eb47ee8fa56bce25e49b8d';
        $account->customerAccountId = 'Account201708082';
        $account->customerUserId = 'User123';
        $account->name = 'Compte de tests 1';
        $account->credentials = array();

        $username = new \Securibox\CloudAgents\Entities\Credential();
        $username->position = 0;
        $username->value = 'username@bbox.fr';

        $password = new \Securibox\CloudAgents\Entities\Credential();
        $password->position = 1;
        $password->value = 'motdepasse';

        array_push($account->credentials, $username, $password);

        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->CreateAccount($account);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp);
        $this->assertEquals($account->customerAccountId, $resp->customerAccountId);
        var_dump($resp);               
    }



    public function testGetAllAccounts(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAllAccounts();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp[0]);
        $this->assertEquals('User123', $resp[0]->customerUserId);
        var_dump($resp);  
    }

    public function testGetAccountsByAgent(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAccountsByAgent('c42f0150d2eb47ee8fa56bce25e49b8d');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp[0]);
        $this->assertEquals('c42f0150d2eb47ee8fa56bce25e49b8d', $resp[0]->agentId);     
        $this->assertEquals('User123', $resp[0]->customerUserId);
        var_dump($resp);          
    }

    public function testGetAccount(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAccount('Account201708082');
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp);
        $this->assertEquals('c42f0150d2eb47ee8fa56bce25e49b8d', $resp->agentId);     
        $this->assertEquals('User123', $resp->customerUserId);
        var_dump($resp);          
    }
    public function testGetUnexistingAccount(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetAccount('#ID_Does_not_exist');
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Error::class, $resp);
        var_dump($resp);       
    }
    public function testSearchAccounts(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SearchAccounts(null, 'User123');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp[0]);
        $this->assertEquals('User123', $resp[0]->customerUserId);
        var_dump($resp);    
    }

    public function testModifyAccount(){        
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $account = $client->GetAccount('c');
        $account->name = $account->name.'_2';
        $resp = $client->ModifyAccount('Account201708082', $account);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Account::class, $resp);
        $this->assertEquals($resp->name, $account->name);
        var_dump($resp); 
    }


    public function testDeleteAccount(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->DeleteAccount('Account201708082');
        $this->assertEquals(true, $resp);    
    }

    public function testSynchronizeAccount(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SynchronizeAccount($accountId, null, true);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Synchronization::class, $resp);
        $this->assertEquals($accountId, $resp->customerAccountId);    
    }

    public function testSynchronizeUnexistingAccount(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SynchronizeAccount('201708', null, true);        
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Error::class, $resp);
        var_dump($resp);
    }

    public function testGetSynchronizationsByAccount(){
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetSynchronizationsByAccount('Account201708082');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Synchronization::class, $resp[0]);
        $this->assertEquals('Account201708082', $resp[0]->customerAccountId);
        var_dump($resp);
    }

    public function testGetLastSynchronizationByAccount(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetLastSynchronizationByAccount($accountId);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Synchronization::class, $resp);
        $this->assertEquals($accountId, $resp->customerAccountId);
        var_dump($resp);   
    }

    public function testSearchSynchronizations(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SearchSynchronizations(null, 'Joao123');
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Synchronization::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);   
    }

    public function testSearchDocumentsByAccountId(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SearchDocuments($accountId);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Document::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);   
    }
    public function testSearchDocumentsByUserId(){
        $userId = 'User123';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->SearchDocuments(null, $userId);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Document::class, $resp[0]);
        var_dump($resp);   
    }
    public function testGetDocumentWithoutContent(){
        $documentId = '704360';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetDocument($documentId);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Document::class, $resp);
        $this->assertEquals($documentId, $resp->id);
        var_dump($resp);   
    }
    public function testGetDocumentWithContent(){
        $documentId = '704360';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetDocument($documentId, 'true');
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Document::class, $resp);
        $this->assertNotNull($resp->base64Content);
        var_dump($resp);   
    }

    public function testGetDocumentsByAccount(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->GetDocumentsByAccount($accountId);
        $this->assertInstanceOf(\Securibox\CloudAgents\Entities\Document::class, $resp[0]);
        $this->assertEquals($accountId, $resp[0]->customerAccountId);
        var_dump($resp);          
    }

    public function testAcknowledgeDocumentDelivery(){
        $documentId = '704362';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->AcknowledgeDocumentDelivery($documentId);
        $this->assertEquals(true, $resp);
    }

    public function testAcknowledgeSynchronizationForAccount(){
        $accountId = 'Account201708082';
        $client = new Securibox\CloudAgents($this->username, $this->password);
        $resp = $client->AcknowledgeSynchronizationForAccount($accountId);
        $this->assertEquals(true, $resp);      
    }                        
}