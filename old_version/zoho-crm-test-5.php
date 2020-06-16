<?php

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMModule;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\crud\ZCRMException;

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Europe/Rome");

$configuration=array(
  "client_id" => "1000.3WT992YU7QPLJCM7836EAF5Q422KQR",
  "client_secret" => "76c7f4466e13aab3bc6a08f3ead1a88dc61ceddfe9",
  "redirect_uri" => "https://www.milanostanze.it",
  "currentUserEmail" => "ricardo.chavez@milanostanze.it",
  "token_persistence_path" => __DIR__ . "/../_gestione/TokenStorage",
  "apiBaseUrl" => "www.zohoapis.eu",
  "accounts_url" => "https://accounts.zoho.eu",
  "access_type" => "offline",
  "apiVersion"=>"v2"
);

ZCRMRestClient::initialize($configuration);

try {

//This part creates an object called $records. With ZCRMRecord::getInstance("ModuloTest",null) we are creating a dummy record from the given module (ModuloTest in this case). You must use the API name of the module you want to use. Also, if you want to edit a specific record, you must provide the record ID and put it instead of "null".
$records = ZCRMRecord::getInstance("ModuloTest",null);
$records->setFieldValue("Name","Luana");
$records->setFieldValue("Website","www.yoyo.it");
$records->setFieldValue("Surname","Manunza");
//This array contains the records for the creation/update/upsert of the records. You can't parse the record object to the createRecords/updateRecords/upsertRecords, you must parse an array made of the record objects you create with ZCRMRecord::getInstance. Of course, if you want to add more than one record, you must create them and add them to the array.
$recordsArray = array($records);

//Now, you create a module instance with ZCRMModule::getInstance. Is another object and you must provide the API name of the module. This is a dummy object, so it won't do nothing. In $zcrmModuleIns->upsertRecords($recordsArray); you parse the array to upsert the records. Upsert is for insert (or update if the record already exist) a record.
$zcrmModuleIns = ZCRMModule::getInstance("ModuloTest");
$bulkAPIResponse=$zcrmModuleIns->upsertRecords($recordsArray);
//Here you obtain the response from the upsert. Because this was made for testing, there is a lot of "echo". The "if" clause gives the info about what happened if everything is going right, the "else" gives the info when something is wrong but the API works. For example, if the module name is invalid.
$entityResponses = $bulkAPIResponse->getEntityResponses();
foreach($entityResponses as $entityResponse)
{
if("success"==$entityResponse->getStatus())
{
echo "Status:".$entityResponse->getStatus();
echo "Message:".$entityResponse->getMessage();
echo "Code:".$entityResponse->getCode();
$upsertData=$entityResponse->getUpsertDetails();
echo "UPSERT_ACTION:".$upsertData["action"];
echo "UPSERT_DUPLICATE_FIELD:".$upsertData["duplicate_field"];
$createdRecordInstance=$entityResponse->getData();
echo "EntityID:".$createdRecordInstance->getEntityId();
echo "moduleAPIName:".$createdRecordInstance->getModuleAPIName();
}
else
{
echo "Status:".$entityResponse->getStatus();
echo "Message:".$entityResponse->getMessage();
echo "Code:".$entityResponse->getCode();
}
}
}

//The "catch" is for debuging the code in the server side. That's why the code have two places where errors can be seen.
catch (ZCRMException $e)
{
echo $e->getCode();
echo $e->getMessage();
echo $e->getExceptionCode();
}

?>