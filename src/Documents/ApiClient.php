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

namespace Securibox\CloudAgents\Documents;
require_once __DIR__."/../../vendor/autoload.php";
use Securibox\CloudAgents\Http;
use Securibox\CloudAgents\Documents\Entities;

/**
* Class exposing the Securibox Cloud agent REST API endpoints.
*/
class ApiClient
{
    private $httpClient;

    /**
    * Initialize the client
    *
    * @param string $username     basic username
    * @param array  $password     basic password
    * @param string $apiEndpoint  the base url (e.g. https://sca-multitenant.securibox.eu/api/v1)
    */
    public function __construct($httpHeaders, $curlOptions = null, $apiEndpoint = "https://sca-multitenant.securibox.eu/api/v1"){
        $this->httpClient = new Http\HttpClient($apiEndpoint, $httpHeaders, null, null, $curlOptions);
    }
    /**
    * Initialize the client with basic authentication
    *
    * @param string $username     basic username
    * @param array  $password     basic password
    * @param string $apiEndpoint  the base url (e.g. https://sca-multitenant.securibox.eu/api/v1)
    */
    public static function AuthenticationBasic($username, $password){
        $headers = ['Authorization: basic '.base64_encode($username.':'.$password)];
        $instance = new self($headers);
        return $instance;

    }
    /**
    * Initialize the client with SSL client certificate authentication
    *
    * @param string $certificateFile        certificate file path (PEM format)
    * @param array  $certificatePassword    PEM pass phrase
    * @param string $apiEndpoint            the base url (e.g. https://sca-multitenant.securibox.eu/api/v1)
    */
    public static function SslClientCertificate($certificateFile, $certificatePassword){
        $curlOptions = array(
            CURLOPT_SSLCERT => $certificateFile ,
            CURLOPT_SSLCERTPASSWD => $certificatePassword ,            
        );
        $instance = new self(null, $curlOptions);
        return $instance;        
    }
    /**
    * Initialize the client with JSON Web Token authentication
    *
    * @param string $username     basic username
    * @param array  $password     basic password
    * @param string $apiEndpoint  the base url (e.g. https://sca-multitenant.securibox.eu/api/v1)
    */
    public static function Jwt($privateKeyFilePath, $privateKeyPassPhrase){
        if (\strpos($privateKeyFilePath, 'file://') !== 0) {
            $privateKeyFilePath = 'file://'.$privateKeyFilePath;
        }

        $key = new Http\JWT\Key($privateKeyFilePath, $privateKeyPassPhrase);

        $signer = new Http\JWT\Signer\Sha256();
        $token = (new Http\JWT\Builder())->issuedBy('SCA API SDK')
                               ->relatedTo('sca-multitenant.securibox.eu')
                               ->permittedFor('https://sca-multitenant.securibox.eu')
                               ->issuedAt(time())
                               ->expiresAt(time() + 3600)
                               ->getToken($signer, $key);
        $headers = ['Authorization: bearer '.$token];
        $instance = new self($headers, null);
        return $instance;
    }

    /**
    * Lists the agents categories.
    *
    * @param string $culture The culture of the returned information.
    *
    * @return array[Category] A list of agent categories.
    */
    public function GetCategories($culture = 'FR-fr'){
        $response = $this->httpClient->categories()->get(null, array('culture' => $culture));
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return Entities\Category::LoadFromJsonArray($jsonData);
    }

    /**
    * Lists all available agents.
    *
    * @param string $agentIdentifier The agent Guid identifier     
    *
    * @return Entities\Agent The agent
    */
    public function GetAgent($agentIdentifier){

        $response = $this->httpClient->agents()->$agentIdentifier()->get();
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code) || isset($jsonData->Code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return  Entities\Agent::LoadFromJson($jsonData);
    }

    /**
    * Lists all available agents.
    *
    * @param string $includeLogo Specifies if the response should include the agents logo in base64 enconding.
    * @param string $culture The culture of the returned information.        
    *
    * @return array[Agent] A list of agents.
    */
    public function GetAgents($culture = 'FR-fr'){
        $response = $this->httpClient->agents()->get(null, array('culture' => $culture));
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code) || isset($jsonData->Code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return  Entities\Agent::LoadFromJsonArray($jsonData);
    }


    /**
    * Lists all available agents.
    *
    * @param string $country The desired agents country.
    * @param string $culture The culture of the returned information.              
    * @param string $includeLogo Specifies if the response should include the agents logo in base64 enconding.
    * @param string $p The query string that will filter agents starting with the defined prefix                 
    *
    * @return array[Agent] A list of agents.
    */
    public function SearchAgents($country = null, $culture = 'FR-fr', $q = null){
        $queryParams = array('culture' => $culture);
        if(isset($country)){
            $queryParams['country'] = $country;
        }
        if(isset($q)){
            $queryParams['q'] = $q;
        }           
        $response = $this->httpClient->agents()->search()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return  Entities\Agent::LoadFromJsonArray($jsonData);
    }

    /**
    * Lists agents by category
    *
    * @param string $categoryId The category identifier.              
    *
    * @return array[Agent] A list of agents.
    */
    public function GetAgentByCategoryId($categoryId){
        $response = $this->httpClient->categories()->$categoryId()->agents()->get();
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return  Entities\Agent::LoadFromJsonArray($jsonData);
    }

    /**
    * Lists all accounts.
    *
    * @param string $agentId The identifier of the agents to be able to filter by agent.
    * @param string $customerUserId  The customer user identifier to be able to filter accounts by user              
    * @param string $skip The number of accounts to skip (used for pagination).
    * @param string $take The maximum number of accounts to be returned (used for pagination).                 
    *
    * @return array[Account] A list of accounts.
    */
    public function GetAllAccounts($agentId = null, $customerUserId = null, $skip = null, $take = null){
        $queryParams = array();
        if(isset($agentId)){
            $queryParams['agentId'] = $agentId;
        }
        if(isset($customerUserId)){
            $queryParams['customerUserId'] = $customerUserId;
        }
        if(isset($skip)){
            $queryParams['skip'] = $skip;
        }    
        if(isset($take)){
            $queryParams['take'] = $take;
        }    
        $response = $this->httpClient->accounts()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }        
        return  Entities\Account::LoadFromJsonArray($jsonData);
    }

    /**
    * Lists accounts by agent.
    *
    * @param string $agentId The identifier of the agents to be able to filter by agent.           
    * @param string $skip The number of accounts to skip (used for pagination).
    * @param string $take The maximum number of accounts to be returned (used for pagination).                 
    *
    * @return array[Account] A list of accounts.
    */
    public function GetAccountsByAgent($agentId, $skip = null, $take = null){
        $queryParams = array();
        if(isset($skip)){
            $queryParams['skip'] = $skip;
        }    
        if(isset($take)){
            $queryParams['take'] = $take;
        }    
        $response = $this->httpClient->agents()->$agentId()->accounts()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        return  Entities\Account::LoadFromJsonArray($jsonData);
    }

    /**
    * Gets an account by customer account identifier.
    *
    * @param string $customerAccountId The customer account identifier.                          
    *
    * @return Account An accounts.
    */
    public function GetAccount($customerAccountId){  
        $response = $this->httpClient->accounts()->$customerAccountId()->get();
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return  Entities\Account::LoadFromJson($jsonData);
    }

    /**
    * Deletes an account by customer account identifier.
    *
    * @param string $customerAccountId The customer account identifier.                          
    *
    * @return boolean true if the account has been successfully deleted.
    */
    public function DeleteAccount($customerAccountId){  
        $response = $this->httpClient->accounts()->$customerAccountId()->delete();
        if($response->statusCode() == 200)
            return true;

        return false;
    }

    /**
    * Create and synchronize an account.
    *
    * @param Account $account The account object to be created.                          
    *
    * @return Account The created account.
    */
    public function CreateAccount($account){
        $body = array(
            'synchronize' => true,
            'account' => $account
        );
        $response = $this->httpClient->accounts()->post($body);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Account::LoadFromJson($jsonData);
    }

    /**
    * Update an existing account information.
    *
    * @param string $accountId The customer account identifier for the account to be modified.         
    * @param Account $account The account object with the new values                        
    *
    * @return Account An account.
    */
    public function ModifyAccount($accountId, $updatedAccount){
        $jsonAccount = json_encode($updatedAccount);
        $response = $this->httpClient->accounts()->$accountId()->put($jsonAccount);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        return Entities\Account::LoadFromJson($jsonData);
    }

    /**
    * Launches the synchronization for a specific account or all accounts for a specific user.
    *
    * @param string $accountId The customer account identifier.     
    * @param string $userId The customer user identifier.           
    * @param string $isForced Specifies if the synchronization is forced or not.              
    *
    * @return array[Synchronization] A Synchronization object.
    */
    public function SynchronizeAccount($accountId = null, $userId = null, $isForced = false){
        if(!isset($accountId) && !isset($userId)){
            throw new Exception("Either the customerAccountId or the customerUserId must be specified.");
        }
        $body = array();
        if(isset($accountId)){
            $body['customerAccountId'] = $accountId;
        }   
        if(isset($userId)){
            $body['customerUserId'] = $userId;
        }    
        if($isForced){
            $body['forced'] = $isForced ? 'true':'false';
        }
        $response = $this->httpClient->accounts()->$accountId()->synchronizations()->post($body);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        if(is_array($jsonData)){
            return  Entities\Synchronization::LoadFromJsonArray($jsonData);
        }else{
            return  Entities\Synchronization::LoadFromJson($jsonData);
        }                
    }

    /**
    * Searches accounts by agent identifier and/or by customer account identifier.
    *
    * @param string $agentId The identifier of the agents to be able to filter by agent.    
    * @param string $customerUserId The identifier of the user to be able to filter by user.         
    * @param string $skip The number of accounts to skip (used for pagination).
    * @param string $take The maximum number of accounts to be returned (used for pagination).                        
    *
    * @return array[Account] An account.
    */
    public function SearchAccounts($agentId = null, $customerUserId = null, $skip = null, $take = null){
        $queryParams = array();
        if(isset($agentId)){
            $queryParams['agentId'] = $agentId;
        }
        if(isset($customerUserId)){
            $queryParams['customerUserId'] = $customerUserId;
        }
        if(isset($skip)){
            $queryParams['skip'] = $skip;
        }    
        if(isset($take)){
            $queryParams['take'] = $take;
        }    
        $response = $this->httpClient->accounts()->search()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Account::LoadFromJsonArray($jsonData);              
    }

    /**
    * List the synchronizations by account and optionnally filter by a date window.
    *
    * @param string $accountId The identifier of the agents to be able to filter by agent.    
    * @param string $startDate The start date filter.        
    * @param string $endDate The end date filter.                       
    *
    * @return array[Synchronization] An array of Synchronization objects.
    */
    public function GetSynchronizationsByAccount ($customerAccountId, $startDate = null, $endDate = null){
        $queryParams = array();
        if(isset($startDate)){
            $queryParams['startDate'] = $startDate;
        }
        if(isset($endDate)){
            $queryParams['endDate'] = $endDate;
        }
        $response = $this->httpClient->accounts()->$customerAccountId()->synchronizations()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        return Entities\Synchronization::LoadFromJsonArray($jsonData);                           
    }

    /**
    * Gets the last synchronization of an account. 
    *
    * @param string $accountId The customer account identifier for which you want to have the last synchronization.                   
    *
    * @return Synchronization A Synchronization object.
    */
    public function GetLastSynchronizationByAccount($accountId){
        $response = $this->httpClient->accounts()->$accountId()->synchronizations()->last()->get();
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }         
        return Entities\Synchronization::LoadFromJson($jsonData);                           
    }

    /**
    * Search synchronizations by account, user, time windows.
    *
    * @param string $accountId The customer account identifier for which you want to have the last synchronization.
    * @param string $customerUserId The identifier of the user to be able to filter by user.             
    * @param string $startDate The start date filter.        
    * @param string $endDate The end date filter.
    * @param string $skip The number of accounts to skip (used for pagination).
    * @param string $take The maximum number of accounts to be returned (used for pagination).                                          
    *
    * @return array[Synchronization] An array of Synchronization object.
    */
    public function SearchSynchronizations($accountId, $customerUserId = null, $startDate = null, $endDate = null, $skip = null, $take = null){
        $queryParams = array();
        if(isset($accountId)){
            $queryParams['customerAccountId'] = $accountId;
        }                 
        if(isset($customerUserId)){
            $queryParams['customerUserId'] = $customerUserId;
        }            
        if(isset($startDate)){
            $queryParams['startDate'] = $startDate;
        }
        if(isset($endDate)){
            $queryParams['endDate'] = $endDate;
        }
        if(isset($skip)){
            $queryParams['skip'] = $skip;
        }    
        if(isset($take)){
            $queryParams['take'] = $take;
        }                
        $response = $this->httpClient->synchronizations()->search()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Synchronization::LoadFromJsonArray($jsonData);                           
    }

    /**
    * Search document by account, user, if it's pending and if the the content of the doument..
    *
    * @param string $accountId The customer account identifier for which you want to have the last synchronization.
    * @param string $customerUserId The identifier of the user to be able to filter by user.             
    * @param boolean $pendingOnly Lists only the documents not delivered.        
    * @param boolean $includeContent Specifies if the response should include the document content in base64 enconding.                                        
    *
    * @return array[Document] An array of Document object.
    */
    public function SearchDocuments($customerAccountId = null, $customerUserId = null, $pendingOnly = false, $includeContent = false){
        $queryParams = array();
        if(isset($customerAccountId)){
            $queryParams['customerAccountId'] = $customerAccountId;
        }                 
        if(isset($customerUserId)){
            $queryParams['customerUserId'] = $customerUserId;
        }            
        if(isset($pendingOnly)){
            $queryParams['pendingOnly'] = $pendingOnly ? 'true': 'false';
        }
        if(isset($includeContent)){
            $queryParams['includeContent'] = $includeContent ? 'true': 'false';
        }          
        $response = $this->httpClient->documents()->search()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Document::LoadFromJsonArray($jsonData);    
    }          

    /**
    * Get a specific document.
    *
    * @param string $documentId The document identifier.
    * @param boolean $includeContent Specifies if the response should include the document content in base64 enconding.                                                     
    *
    * @return Document A Document object.
    */
    public function GetDocument($documentId, $includeContent = false){
        $queryParams = array();
            if(isset($includeContent)){
            $queryParams['includeContent'] = $includeContent ? 'true': 'false';
        }            
        $response = $this->httpClient->documents()->$documentId()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Document::LoadFromJson($jsonData);    
    }

    /**
    * Get all documents for an account
    *
    * @param string $customerAccountId The customer account identifier.
    * @param boolean $pendingOnly Lists only the documents not delivered.        
    * @param boolean $includeContent Specifies if the response should include the document content in base64 enconding.                                                                
    *
    * @return array[Document] An array of Document objects.
    */
    public function GetDocumentsByAccount($customerAccountId, $pendingOnly = false, $includeContent = false){
        $queryParams = array();        
        if(isset($pendingOnly)){
            $queryParams['pendingOnly'] = $pendingOnly ? 'true': 'false';
        }
        if(isset($includeContent)){
            $queryParams['includeContent'] = $includeContent ? 'true': 'false';
        }            
        $response = $this->httpClient->accounts()->$customerAccountId()->documents()->get(null, $queryParams);
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }            
        return Entities\Document::LoadFromJsonArray($jsonData);    
    }

    /**
    * Acknowledge the reception of a specific document.
    *
    * @param string $documentId The document identifier.                                                             
    *
    * @return boolean Returns true if the acknowledgement is successful.
    */
    public function AcknowledgeDocumentDelivery($documentId){           
        $response = $this->httpClient->documents()->$documentId()->ack()->put($documentId);
        if($response->statusCode() == 200)
            return true;
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        return false;              
    }

    /**
    * Acknowledges the reception of the documents retrieved through a synchronization. 
    *
    * @param string $customerAccountId The customer account identifier.
    * @param array[int] $documentIds The identifiers of the received documents.
    * @param array[int] $missingDocumentIds The identifiers of the documents not received.                                                                           
    *
    * @return boolean Returns true if the acknowledgement is successful.
    */
    public function AcknowledgeSynchronizationForAccount($customerAccountId, $documentIds = [], $missingDocumentIds = []){
        $body = array(
            'customerAccountId' => $customerAccountId,
            'documentIds' => $documentIds,
            'missingDocumentIds' => $missingDocumentIds
        );           
        $response = $this->httpClient->synchronizations()->$customerAccountId()->ack()->put($body);
        if($response->statusCode() == 200)
            return true;
        $jsonData = json_decode($response->body());
        if(isset($jsonData->code)){
            return  Entities\Error::LoadFromJson($jsonData);
        }
        return false;              
    }                                          
}
?>