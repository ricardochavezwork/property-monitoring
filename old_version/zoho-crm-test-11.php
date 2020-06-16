<?php

require_once __DIR__ . '/../lib/api.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Zoho_Api.php';
require_once __DIR__ . '/../lib/Immobiliare.php';
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

date_default_timezone_set("Europe/Rome");

/**
 * One-time-funtion : Aggiorna i dati dal DB locale a Zoho. Exe is false!
 */

$exe = false;
$total_pages = 0;

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

set_time_limit(0);

//$test = Array();
$test = 0;

$criteria = "Valutazione:equals:true";
$moduleName = "acquisizione";
$moduleIns=ZCRMRestClient::getInstance()->getModuleInstance($moduleName);  //To get module instance
$per_page = 200;
$page = 1;
$count_records = $per_page;


while ($count_records >= $per_page && $exe) {
  $param_map=array("page"=>$page,"per_page"=>$per_page);
  $response = $moduleIns->searchRecordsByCriteria($criteria, $param_map);  //To get module records that match the criteria
  $records = $response->getData();  //To get response data
  $count_records = count($records);
  $total_records += $count_records;
  $page++;

  foreach ($records as $record) {
    $recordLink = $record->getFieldValue("Link");
    $adStatus = Immobiliare::get_status_by_link($recordLink);
    if($adStatus->exists){
      $record->setFieldValue("Status", Immobiliare::translate_status_to_zoho($adStatus->statusNumber));
      Zoho_Api::upsertRecords(array($record), $moduleName);
      //echo $recordLink . " - " . Immobiliare::translate_status_to_zoho($adStatus->statusNumber). "<br>";
      //$test++;
    }
  }

}

//echo $test;

//echo $total_records;

/*if(count($records) > 0){
  $exists = $records[0]->getEntityId();
}*/


/*header("Content-Type: text/json");
@ob_clean();
$val = json_encode($test);
exit($val);*/