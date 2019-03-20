# cloudagents-php
[![Packagist Version][packagist-image]][packagist-url]

A PHP client library for the [Securibox Cloud Agents API][1]

## Install Package
Securibox Cloud Agent PHP wrapper is installed via [Composer](http://getcomposer.org).
Simply run the following command:
```bash
composer require securibox/cloudagents
```

#### Alternative: Install package from zip
If you are not using Composer, simply download and install the **[latest packaged release of the library as a zip](https://github.com/Securibox/cloudagents-php/archive/master.zip)**.

## Authentication
In order to secure the Securibox Cloud Agents API, three mechanisms have been implemented. Here is a brief overview of the three mechanisms as well as code snippets to help you integrate the correct mechanism in order to call the APIs.

## Basic API Authentication w/ TLS
Basic API authentication is the easiest of the three to implement offering the lowest security options of the common protocols.
This mechanism is usually advised for testing purposes in order to test the APIs and only requires Securibox to provide a username and password.
```php
use Securibox\CloudAgents\Documents\ApiClient;

$client = ApiClient::AuthenticationBasic("username", "password");
```

### SSL Client Certificate Authentication 
The SSL client certification is a mechanism allowing your application to authenticate itself with the Securibox Cloud Agents (SCA) servers. In this case, your application will send its SSL certificate after verifing the SCA server identity. Then, the client and server use both certificates to generate a unique key used to sign requests sent between them.

This kind of authentication is implemented when the customer call your servers that will then call the Securibox Cloud Agents API.

In order to use this type of authentication, Securibox will provide a PEM certificate file containing a passphrase protected private key and a public key.
```php
use Securibox\CloudAgents\Documents\ApiClient;

$client = ApiClient::SslClientCertificate("C:\Path\to\PEM Certificate", "PEM pass phrase");
```

### JSON Web Token Authentication
[JSON Web Token (JWT)](https://jwt.io) is an open standard (RFC 7519) that defines a compact and self-contained way for securely transmitting information between parties as a JSON object. This information can be verified and trusted because it is digitally signed. JWTs can be signed using a public/private key pair using RS256 (RSA PKCS#1 signature with SHA-256).

This kind of authentication is implemented when the customer calls directly the Securibox Cloud Agents API together with [cross-origin resource sharing (CORS)](https://en.wikipedia.org/wiki/Cross-origin_resource_sharing).

In order to use this type of authentication, Securibox will provide a passphrase protected RSA private key in PEM file (.pem).
```php
use Securibox\CloudAgents\Documents\ApiClient;

$client = ApiClient::Jwt("C:\Path\to\PEM private key", "PEM pass phrase");
```

## Getting started
The following is the minimum needed code to list all agent details and fields:
```php
<?php 
// If you are using Composer (recommended)
require 'vendor/autoload.php';
use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;

// If you are not using Composer
// require("path/to/cloudagents-php/src/autoload.php");

$client = ApiClient::AuthenticationBasic("Basic Username", "Basic Password");
$agents = $client->GetAgents();
foreach($agents as $agent){
    print("\n\n\n------ Agent Details ------\n");
    print("ID: ".$agent->id."\n");
    print("Name: ".$agent->name."\n");
    print("Periodicity: ".$agent->agentPeriodicity."\n");
    print("Current Status: ".$agent->agentCurrentState."\n");
    print("Category: ".$agent->category."\n");
    foreach($agent->fields as $field){
        print("   Field[". $field->position ."]: ".$field->name."\n");
    }
}
```

The following code is the minimum code needed to configure an agents and launch a synchronization:
```php
<?php
// If you are using Composer (recommended)
require 'vendor/autoload.php';
use Securibox\CloudAgents\Documents\ApiClient;
use Securibox\CloudAgents\Documents\Entities;

// If you are not using Composer
// require("path/to/cloudagents-php/src/autoload.php");

//Configure account properties
$account = new Entities\Account();
$account->agentId = 'c42f0150d2eb47ee8fa56bce25e49b8d';
$account->customerAccountId = 'Account201708082';
$account->customerUserId = 'User123';
$account->name = 'Test Account 1';
$account->credentials = array();

//Configure credentials
$username = new Entities\Credential();
$username->position = 0;
$username->value = 'username@test.com';

//Configure credentials
$password = new Entities\Credential();
$password->position = 1;
$password->value = '###password###';

array_push($account->credentials, $username, $password);

//Setup client
$client = new ApiClient::AuthenticationBasic("Basic Username", "Basic Password");

//Create the account which automatically launches a synchronization
$returnedAccount = $client->CreateAccount($account);

//Let's wait until the synchronization has reached a final status
$synchronization = $client->GetLastSynchronizationByAccount($returnedAccount->customerAccountId);
while($synchronization->synchronizationState != "PendingAcknowledgement" &&
      $synchronization->synchronizationState != "Completed" &&
      $synchronization->synchronizationState != "ReportFailed"){
        sleep(5);
        $synchronization = $client->GetLastSynchronizationByAccount($returnedAccount->customerAccountId);
}

//Let's get the newly downloaded documents and save them locally
$documents = $client->GetDocumentsByAccount($account->customerAccountId, 'true','true');
$receivedFiles = array();
foreach($documents as $document){
    $file = fopen("C:\\Temp\\".$document->name, "wb");
    $content =  base64_decode($document->base64Content);
    fwrite($file, $content);
    fclose($file);
    array_push($receivedFiles, $document->id);   
}
$client->AcknowledgeSynchronizationForAccount($account->customerAccountId, $receivedFiles, array());
```

## Webview url
In order to use the webview and avoid having to implement the APIs to list and configure accounts, a webview has been developped.
In this webview, a customer can:
- Browse and search all agents
- Have quick access to predefined favorite agents
- List the configured agents
- Configure, modify or delete an agent account
- See the list of synchronizations for each account

To use the webview, activate it in the CloudAgents backoffice and use the provided PEM private key to sign the token.

The webview accepts the following url arguments:
- token: json web token signed with the PEM certificate
- callback: url where the user is sent on logging out <sub><sup>(ex: https://www.myapp.com - if a state has been provided: https://www.myapp.com?state={state})</sup></sub>
- lang <sup>optional</sup>: culture to display the page - By default, in fr-FR

Example:
```
https://sca-webview.azurewebsites.net?token={token}&callback={callback_url}&state={stateData}
https://sca-webview.azurewebsites.net?token={token}&callback={callback_url}&state={stateData}&lang=en-us
```
For examples in php to generate the token, [please refer to the test][4].

## License
[GNU GPL][3]

[1]: https://sca.securibox.eu
[2]: https://sca.securibox.eu/doc.html
[3]: https://github.com/Securibox/cloudagents-phpblob/master/LICENSE
[4]: https://github.com/Securibox/cloudagents-php/blob/master/test/WebView.php
[packagist-image]: https://img.shields.io/badge/packagist-1.0.2-blue.svg
[packagist-url]: https://packagist.org/packages/securibox/cloudagents