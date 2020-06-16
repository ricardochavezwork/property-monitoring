<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/Zoho_Api.php';
require_once __DIR__ . '/../lib/Immobiliare.php';
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

date_default_timezone_set("Europe/Rome");

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
$modes = Array("affitto", "vendita");
$mode = $modes[1];
$moduleName = $mode;

$requests = Array(
  Array(1, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10046"),
  Array(2, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10047"),
  Array(3, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10053"),
  Array(4, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10050"),
  Array(5, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10056"),
  Array(6, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10049"),
  Array(7, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10057"),
  Array(8, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10067"),
  Array(9, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10321"),
  Array(10, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10066"),
  Array(11, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10065"),
  Array(12, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10292"),
  Array(13, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10055"),
  Array(14, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10054"),
  Array(15, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10064"),
  Array(16, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10072"),
  Array(17, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10071"),
  Array(18, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10296"),
  Array(19, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10061"),
  Array(20, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10318"),
  Array(21, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10316"),
  Array(22, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10295"),
  Array(23, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10317"),
  Array(24, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10070"),
  Array(25, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10060"),
  Array(26, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10059"),
  Array(27, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10068"),
  Array(28, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10293"),
  Array(29, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10320"),
  Array(30, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10069"),
  Array(31, "https://www.immobiliare.it/" . $mode . "-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10319")
);

$test = Array();

foreach ($requests as $key => $request) {
  
  $reqLink = $request[1];

  if($mode === "vendita")
    $reqLink .= "&noAste=1";

  $total_pages = Immobiliare::getTotalPages($reqLink);
  if($total_pages > 0 && $exe){
    for ($i=0; $i < $total_pages; $i++) { 
      $pager = '&pag=' . ($i+1);

      if($i === 0)
        $pager = "";

      $linkPage = $reqLink . $pager;
      $zona = $request[0];

      $ads_id = Immobiliare::getAdsLinkFromPage($linkPage);

      //$test[] = $ads_id;

      foreach ($ads_id as $ads_id_key => $ad_id) {
        $ad_link = 'https://www.immobiliare.it/annunci/' . $ad_id;

        $ad = Immobiliare::getAnnuncio($ad_link, $zona, null);

        //$test[] = $ad;

        $existingRecordId = Zoho_Api::LinkAdExists($ad_link, $moduleName);

        $record = Zoho_Api::getRecord($existingRecordId, $moduleName);
        $record = Zoho_Api::fillRecordFromImmobiliare($record, $ad, 0);
    
        Zoho_Api::upsertRecords(Array($record), $moduleName);
      }
    }
  }
}

/*header("Content-Type: text/json");
@ob_clean();
$val = json_encode($test);
exit($val);*/