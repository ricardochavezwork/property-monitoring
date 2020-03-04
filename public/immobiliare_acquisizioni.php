<?php

include_once "common.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
ZCRMRestClient::initialize($configuration);

$exe = true;
$total_pages = 0;
$adsContainer = Array();
$adsCounter = 0;
$moduleName = "acquisizione";
$startTime = date("Y-m-d H:i:s");

$requests = Array(
  Array(300, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10064&idMZona[]=10292"),
  Array(350, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10317&idMZona[]=10294&idMZona[]=10319&idMZona[]=10320&idMZona[]=10293&idMZona[]=10068&idMZona[]=10067&idMZona[]=10069"),
  Array(400, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10065&idMZona[]=10066&idMZona[]=10072&idMZona[]=10071&idMZona[]=10316&idMZona[]=10295"),
  Array(400, "https://www.immobiliare.it/affitto-case/sesto-san-giovanni/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3"),
  Array(500, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10321&idMZona[]=10296&idMZona[]=10318&idMZona[]=10070"),
  Array(600, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10055&idMZona[]=10054&idMZona[]=10061&idMZona[]=10060&idMZona[]=10059&idMZona[]=10057&idMZona[]=10056"),
  Array(700, "https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3&idMZona[]=10053&idMZona[]=10050&idMZona[]=10049&idMZona[]=10047&idMZona[]=10046")
);

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
        $adsCounter++;
        $ad_link = 'https://www.immobiliare.it/annunci/' . $ad_id;

        $ad = Immobiliare::getAnnuncio($ad_link, null, $tettoMassimo);

        $existingRecordId = ZohoCrmApi::LinkAdExists($ad_link, $moduleName);
        $record = ZohoCrmApi::getRecord($existingRecordId, $moduleName);
        $record = ZohoCrmApi::fillRecordFromImmobiliare($record, $ad, $existingRecordId);
        ZohoCrmApi::upsertRecords(Array($record), $moduleName);
      }
    }
  }
}

$endTime = date("Y-m-d H:i:s");
$logMessagge = "start : " . $startTime . " - end : " . $endTime . " - adsCounter : " . $adsCounter . " \n";
echo $logMessagge;