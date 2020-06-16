<?php

include_once __DIR__ . "/../lib/Goutte-master/goutte-v1.0.7.phar";
require_once __DIR__ . '/../lib/api.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Zoho_Api.php';
require_once __DIR__ . '/../lib/Immobiliare.php';
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use Goutte\Client;
use Spatie\Crawler\Crawler;

date_default_timezone_set("Europe/Rome");

/**
 * Elimina gli annunci che non sono più presenti su Immobiliare.
 */

$exe = true;
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

$moduleName = "vendita";
$moduleIns=ZCRMRestClient::getInstance()->getModuleInstance($moduleName);  //To get module instance
$per_page = 100;
$page = 1;
$count_records = $per_page;

while ($count_records >= $per_page) {
  $param_map=array("page"=>$page,"per_page"=>$per_page);
  $response = $moduleIns->getRecords($param_map);  //To get module records that match the criteria
  $records = $response->getData();  //To get response data
  $count_records = count($records);
  $total_records += $count_records;
  $page++;

  foreach ($records as $record) {
    $recordLink = $record->getFieldValue("Link");

    $client = new Client();
    $crawler = $client->request('GET', $recordLink);
    $crawler->filter('body > div.body-content.error-page.error-block > div > div.error-block__error-404')->each(function ($node) use (&$record, $moduleName) {
      Zoho_Api::deleteRecords(array($record->getEntityId()), $moduleName);
    });
  }

}

/*header("Content-Type: text/json");
@ob_clean();
$val = json_encode($test);
exit($val);*/