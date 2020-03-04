<?php

include_once "common.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;

$exe = true;
$total_pages = 0;
$test = Array();

$configuration=array(
  "client_id" => "1000.3WT992YU7QPLJCM7836EAF5Q422KQR",
  "client_secret" => "76c7f4466e13aab3bc6a08f3ead1a88dc61ceddfe9",
  "redirect_uri" => "https://www.milanostanze.it",
  "currentUserEmail" => "ricardo.chavez@milanostanze.it",
  "token_persistence_path" => ROOT . "/api/zohocrm/tokenPersistence",
  "apiBaseUrl" => "www.zohoapis.eu",
  "accounts_url" => "https://accounts.zoho.eu",
  "access_type" => "offline",
  "apiVersion"=>"v2"
);

ZCRMRestClient::initialize($configuration);
set_time_limit(0);
$moduleName = "acquisizione";
$startTime = date("Y-m-d H:i:s");
$endTime = date("Y-m-d H:i:s");
$logMessagge = "start : " . $startTime . " - end : " . $endTime . " - adsCounter : 25";

$requests = Array(
  Array(300, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10064&idMZona[]=10292"),
  Array(350, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10317&idMZona[]=10294&idMZona[]=10319&idMZona[]=10320&idMZona[]=10293&idMZona[]=10068&idMZona[]=10067&idMZona[]=10069"),
  Array(400, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10065&idMZona[]=10066&idMZona[]=10072&idMZona[]=10071&idMZona[]=10316&idMZona[]=10295"),
  Array(400, "https://www.immobiliare.it/affitto-case/sesto-san-giovanni/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3"),
  Array(500, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10321&idMZona[]=10296&idMZona[]=10318&idMZona[]=10070"),
  Array(600, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10055&idMZona[]=10054&idMZona[]=10061&idMZona[]=10060&idMZona[]=10059&idMZona[]=10057&idMZona[]=10056"),
  Array(700, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10053&idMZona[]=10050&idMZona[]=10049&idMZona[]=10047&idMZona[]=10046")
);

$tettoMassimo = 200;
$ad_link = 'https://www.immobiliare.it/annunci/79105475';

$ad = Immobiliare::getAnnuncio($ad_link, null, $tettoMassimo);

$existingRecordId = ZohoCrmApi::LinkAdExists($ad_link, $moduleName);
$record = ZohoCrmApi::getRecord($existingRecordId, $moduleName);
$record = ZohoCrmApi::fillRecordFromImmobiliare($record, $ad, $existingRecordId);
ZohoCrmApi::upsertRecords(Array($record), $moduleName);

echo $logMessagge;

/*$test = Array();

foreach ($requests as $key => $request) {
  
  $reqLink = $request[1];

  $total_pages = Immobiliare::getTotalPages($reqLink);
  if($total_pages > 0 && $exe){
    for ($i=0; $i < $total_pages; $i++) { 
      $pager = '&pag=' . ($i+1);

      if($i === 0)
        $pager = "";

      $linkPage = $reqLink . $pager;
      $tettoMassimo = $request[0];

      $ads_id = Immobiliare::getAdsLinkFromPage($linkPage);

      foreach ($ads_id as $ads_id_key => $ad_id) {
        $ad_link = 'https://www.immobiliare.it/annunci/' . $ad_id;

        $ad = Immobiliare::getAnnuncio($ad_link, null, $tettoMassimo);

        $existingRecordId = Zoho_Api::LinkAdExists($ad_link, $moduleName);

        $record = Zoho_Api::getRecord($existingRecordId, $moduleName);
        $record = Zoho_Api::fillRecordFromImmobiliare($record, $ad, $existingRecordId);
    
        Zoho_Api::upsertRecords(Array($record), $moduleName);
      }
    }
  }
}*/

/*header("Content-Type: text/json");
@ob_clean();
$val = json_encode($test);
exit($val);*/