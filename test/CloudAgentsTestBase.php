<?php
namespace Securibox;
require(__DIR__.'/../vendor/autoload.php');

use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;
use PHPUnit\Framework\TestCase;

class CloudAgentsTestBase extends TestCase{
    private $customerAccountId = "TestAccountSdkPhp";
    private $customerUserId = "UserSdkPhp";
    protected $client;

    public function testGetCategories(){
        $resp = $this->client->getCategories();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Category::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
    }

    public function testGetSingleAgent(){
        $resp = $this->client->GetAgent('93FDDB673A2D4FB49406F21A5937DC90');
        $this->assertInstanceOf(Entities\Agent::class, $resp);
        $this->assertObjectHasAttribute('id', $resp);
        $this->assertObjectHasAttribute('name', $resp);
        $this->assertObjectHasAttribute('description', $resp);
    }   

    public function testGetAgents(){
        $resp = $this->client->GetAgents();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
    }

    public function testGetAgentByCategoryId(){
        $resp = $this->client->GetAgentByCategoryId("e906d5d7b822a2087b2dacbd8e4f8379");
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
    }

    public function testSearchAgents(){
        $resp = $this->client->SearchAgents(null, null, "amazon");
        $this->assertInstanceOf(Entities\Agent::class, $resp[0]);
        $this->assertObjectHasAttribute('id', $resp[0]);
        $this->assertObjectHasAttribute('name', $resp[0]);
        $this->assertObjectHasAttribute('description', $resp[0]);
        for($i=0; $i<sizeof($resp); $i++){
            $this->assertStringContainsStringIgnoringCase('amazon', strtolower ($resp[$i]->name));
        }
    }

    public function testCreateAccount(){
        $account = new Entities\Account();
        $account->agentId = '93fddb673a2d4fb49406f21a5937dc90';
        $account->customerAccountId = $this->customerAccountId;
        $account->customerUserId = $this->customerUserId;
        $account->name = 'Compte de tests 1';
        $account->credentials = array();
        $username = new Entities\Credential();
        $username->position = 0;
        $username->value = 'username@email.com';
        $password = new Entities\Credential();
        $password->position = 1;
        $password->value = 'motdepasse';
        array_push($account->credentials, $username, $password);
        $resp = $this->client->CreateAccount($account);  
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals($account->customerAccountId, $resp->customerAccountId);
        $this->assertEquals($account->customerUserId, $resp->customerUserId);           
    }

    public function testGetAllAccounts(){
        $resp = $this->client->GetAllAccounts();
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        foreach($resp as $account){
            if($account->customerUserId == $this->customerUserId){
                $this->assertEquals($this->customerUserId, $account->customerUserId);
            }
        }
    }

    public function testGetAccountsByAgent(){
        $resp = $this->client->GetAccountsByAgent('93fddb673a2d4fb49406f21a5937dc90');
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        $this->assertEquals('93fddb673a2d4fb49406f21a5937dc90', $resp[0]->agentId);
        foreach($resp as $account){
            if($account->customerUserId == $this->customerUserId){
                $this->assertEquals($this->customerUserId, $account->customerUserId);
            }
        }       
    }

    public function testGetAccount(){
        $resp = $this->client->GetAccount($this->customerAccountId);
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals('93fddb673a2d4fb49406f21a5937dc90', $resp->agentId);     
        $this->assertEquals($this->customerUserId, $resp->customerUserId);       
    }

    public function testGetUnexistingAccount(){
        $resp = $this->client->GetAccount('ID_Does_not_exist');
        $this->assertInstanceOf(Entities\Error::class, $resp);      
    }

    public function testSearchAccounts(){
        $resp = $this->client->SearchAccounts(null, $this->customerUserId);
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Account::class, $resp[0]);
        $this->assertEquals($this->customerUserId, $resp[0]->customerUserId); 
    }

    public function testGetLastSynchronizationByAccount(){
        $resp = $this->client->GetLastSynchronizationByAccount($this->customerAccountId);
        $this->assertInstanceOf(Entities\Synchronization::class, $resp);
        $this->assertEquals($this->customerAccountId, $resp->customerAccountId);  
    }

    public function testModifyAccount(){
        $account = $this->client->GetAccount($this->customerAccountId);
        $account->name = $account->name.'_2';
        $resp =  $this->client->ModifyAccount($this->customerAccountId, $account);
        $this->assertInstanceOf(Entities\Account::class, $resp);
        $this->assertEquals($resp->name, $account->name);
    }

    public function testSynchronizeAccount(){
        $resp = $this->client->SynchronizeAccount($this->customerAccountId, null, true);

        if($resp instanceof Entities\Error){
            $this->assertEquals("20", $resp->code);
        }else{
            $this->assertInstanceOf(Entities\Synchronization::class, $resp);
            $this->assertEquals($this->customerAccountId, $resp->customerAccountId);  
        }
    }

    public function testGetSynchronizationsByAccount(){
        $resp = $this->client->GetSynchronizationsByAccount($this->customerAccountId);
        $this->assertGreaterThan(0, sizeof($resp));
        $this->assertInstanceOf(Entities\Synchronization::class, $resp[0]);
        $this->assertEquals($this->customerAccountId, $resp[0]->customerAccountId);
    }

    public function testGetAdditionalAuthenticationDataByAccount(){
        $resp = $this->client->GetSynchronizationAdditionalAuthDataByCustomerAccountId($this->customerAccountId);
        $this->assertInstanceOf(Entities\AdditionalAuthData::class, $resp);
    }

    public function testUpdateAdditionalAuthenticationDataByAccount(){
        $authData = new Entities\AdditionalAuthRequest();
        $authData->accountId = $this->customerAccountId;
        $authData->SbxSecretCode = '123456';
        $resp = $this->client->UpdateSynchronizationAdditionalAuth($authData);
        $this->assertInstanceOf(Entities\Synchronixation::class, $resp);
        $this->assertEquals($this->customerAccountId, $resp->customerAccountId);  
    }

    public function testSynchronizeUnexistingAccount(){
        $resp = $this->client->SynchronizeAccount('Account_Id', null, true);        
        $this->assertInstanceOf(Entities\Error::class, $resp);
    }

    public function testSearchDocumentsByAccountId(){
        $resp = $this->client->SearchDocuments($this->customerAccountId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);
        $this->assertEquals($this->customerAccountId, $resp[0]->customerAccountId);
    }

    public function testSearchDocumentsByUserId(){
        $resp = $this->client->SearchDocuments(null, $this->customerUserId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);  
    }

    public function testGetDocumentsByAccount(){
        $resp = $this->client->GetDocumentsByAccount($this->customerAccountId);
        $this->assertInstanceOf(Entities\Document::class, $resp[0]);
        $this->assertEquals($this->customerAccountId, $resp[0]->customerAccountId);        
    }

    public function testGetDocumentWithContent(){
        $resp = $this->client->GetDocumentsByAccount($this->customerAccountId);
        if(sizeof($resp) == 0){
            $this->assertEmpty($resp);
            return;
        }
        $resp = $this->client->GetDocument(strval($resp[0]->id), 'true');
        $this->assertInstanceOf(Entities\Document::class, $resp);
        $this->assertNotNull($resp->base64Content);  
    }

    public function testAcknowledgeDocumentDelivery(){
        $respDoc = $this->client->GetDocumentsByAccount($this->customerAccountId);
        if(sizeof($respDoc) == 0){
            $this->assertEmpty($respDoc);
            return;
        }
        $resp = $this->client->AcknowledgeDocumentDelivery(strval($respDoc[0]->id));
        $this->assertEquals(true, $resp);

         $resp = $this->client->AcknowledgeDocumentDelivery(strval($respDoc[1]->id), refused: true);
        $this->assertEquals(true, $resp);

         $resp = $this->client->AcknowledgeDocumentDelivery(strval($respDoc[2]->id), failed: true);
        $this->assertEquals(true, $resp);
    }

    public function testAcknowledgeSynchronizationForAccount(){
        $resp = $this->client->AcknowledgeSynchronizationForAccount($this->customerAccountId);
        $this->assertEquals(true, $resp);      
    }

    public function testDeleteAccount(){
        $resp = $this->client->DeleteAccount($this->customerAccountId);
        $this->assertEquals(true, $resp);    
    }      
}