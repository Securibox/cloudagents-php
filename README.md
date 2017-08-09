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

## Getting started
The following is the minimum needed code to list all agent details and fields:
```php
<?php 
// If you are using Composer (recommended)
require 'vendor/autoload.php';

// If you are not using Composer
// require("path/to/cloudagents-php/src/CloudAgents.php");

$client = new Securibox\CloudAgents("Basic Username", "Basic Password");
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

// If you are not using Composer
// require("path/to/cloudagents-php/src/CloudAgents.php");

//Configure account properties
$account = new \Securibox\CloudAgents\Entities\Account();
$account->agentId = 'c42f0150d2eb47ee8fa56bce25e49b8d';
$account->customerAccountId = 'Account201708082';
$account->customerUserId = 'User123';
$account->name = 'Test Account 1';
$account->credentials = array();

//Configure credentials
$username = new \Securibox\CloudAgents\Entities\Credential();
$username->position = 0;
$username->value = 'username@test.com';

//Configure credentials
$password = new \Securibox\CloudAgents\Entities\Credential();
$password->position = 1;
$password->value = '###password###';

array_push($account->credentials, $username, $password);

//Setup client
$client = new Securibox\CloudAgents("Basic Username", "Basic Password");

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
## License
[GNU GPL][3]

[1]: https://sca.securibox.eu
[2]: https://sca.securibox.eu/doc.html
[3]: https://github.com/Securibox/cloudagents-phpblob/master/LICENSE
[packagist-image]: https://img.shields.io/badge/packagist-1.0.2-blue.svg
[packagist-url]: https://packagist.org/packages/securibox/cloudagents