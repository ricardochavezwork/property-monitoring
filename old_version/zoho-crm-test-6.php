<?php

include 'common.php';
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
/*use zcrmsdk\crm\crud\ZCRMModule;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\crud\ZCRMException;*/

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

$moduleIns=ZCRMRestClient::getInstance()->getModuleInstance("ModuloTest");  //To get module instance
$response=$moduleIns->searchRecordsByCriteria("Website:equals:www.yoyo.it");  //To get module records that match the criteria
$records=$response->getData();  //To get response data	

foreach ($records as $key => $value) {
  echo $value->getFieldValue('Name');
}

//echo json_encode($records[0]);

/*foreach($entityResponses as $entityResponse) {
  if("success"==$entityResponse->getStatus()){
    echo "Status:".$entityResponse->getStatus();
    echo "Message:".$entityResponse->getMessage();
    echo "Code:".$entityResponse->getCode();
    $upsertData=$entityResponse->getUpsertDetails();
    echo "UPSERT_ACTION:".$upsertData["action"];
    echo "UPSERT_DUPLICATE_FIELD:".$upsertData["duplicate_field"];
    $createdRecordInstance=$entityResponse->getData();
    echo "EntityID:".$createdRecordInstance->getEntityId();
    echo "moduleAPIName:".$createdRecordInstance->getModuleAPIName();
  }else{
    echo "Status:".$entityResponse->getStatus();
    echo "Message:".$entityResponse->getMessage();
    echo "Code:".$entityResponse->getCode();
  }
}*/


?>