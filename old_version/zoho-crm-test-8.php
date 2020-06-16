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

$param_map=array("page"=>1,"per_page"=>100);
$moduleIns=ZCRMRestClient::getInstance()->getModuleInstance("acquisizione");  //To get module instance
$response = $moduleIns->getRecords($param_map);
$records = $response->getData();
$test = Array();
foreach ($records as $key => $value) {
  $test[] = $value->getEntityId();
}

$responseIn = $moduleIns->deleteRecords($test);

header("Content-Type: text/json");
@ob_clean();
$val = json_encode($test);
exit($val);

?>