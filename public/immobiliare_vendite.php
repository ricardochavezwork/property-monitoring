<?php

include_once "common.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
ZCRMRestClient::initialize($configuration);

$exe = true;
$total_pages = 0;
$adsContainer = Array();
$adsCounter = 0;
$moduleName = "vendita";
$startTime = date("Y-m-d H:i:s");

$requests = Array(
  Array(1, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10046"),
  Array(2, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10047"),
  Array(3, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10053"),
  Array(4, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10050"),
  Array(5, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10056"),
  Array(6, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10049"),
  Array(7, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10057"),
  Array(8, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10067"),
  Array(9, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10321"),
  Array(10, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10066"),
  Array(11, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10065"),
  Array(12, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10292"),
  Array(13, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10055"),
  Array(14, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10054"),
  Array(15, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10064"),
  Array(16, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10072"),
  Array(17, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10071"),
  Array(18, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10296"),
  Array(19, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10061"),
  Array(20, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10318"),
  Array(21, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10316"),
  Array(22, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10295"),
  Array(23, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10317"),
  Array(24, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10070"),
  Array(25, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10060"),
  Array(26, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10059"),
  Array(27, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10068"),
  Array(28, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10293"),
  Array(29, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10320"),
  Array(30, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10069"),
  Array(31, "https://www.immobiliare.it/vendita-case/milano/?criterio=rilevanza&superficieMinima=90&noAste=1&idMZona[]=10319"),
  Array(32, "https://www.immobiliare.it/vendita-palazzi/milano/?criterio=rilevanza&noAste=1"),
  Array(33, "https://www.immobiliare.it/ricerca_nc.php?idCategoria=6&idContratto=&idTipologia[]=54&idTipologia[]=85&idTipologia[]=56&idTipologia[]=58&idRegione=lom&idProvincia=MI&idComune=8042&prezzoMinimo=&prezzoMassimo=&superficieMinima=&superficieMassima=&bagni=&criterio=rilevanza&ordine=desc"),
  Array(34, "https://www.immobiliare.it/Milano/vendita_immobili_commerciali/ufficio-Milano.html?criterio=rilevanza&noAste=1"),
  Array(35, "https://www.immobiliare.it/terreni/Milano/terreni_edificabili-Milano.html?criterio=rilevanza&noAste=1")
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
      $zona = $request[0];

      $ads_id = Immobiliare::getAdsLinkFromPage($linkPage);

      foreach ($ads_id as $ads_id_key => $ad_id) {
        $adsCounter++;
        $ad_link = 'https://www.immobiliare.it/annunci/' . $ad_id;

        $existingRecordId = ZohoCrmApi::LinkAdExists($ad_link, $moduleName);
        $hasErrorPage = Immobiliare::hasErrorPage($ad_link);

        if(!$hasErrorPage){
          $ad = Immobiliare::getAnnuncio($ad_link, $zona, 0);
          $record = ZohoCrmApi::getRecord($existingRecordId, $moduleName);
          $record = ZohoCrmApi::fillRecordFromImmobiliare($record, $ad, $existingRecordId);
          ZohoCrmApi::upsertRecords(Array($record), $moduleName);
        }else if($hasErrorPage && $existingRecordId > 0){
          ZohoCrmApi::deleteRecords(array($record->getEntityId()), $moduleName);
        }
      }
    }
  }
}

/*@ob_clean();
$value = json_encode($ads_id);
exit($value);*/

$endTime = date("Y-m-d H:i:s");
$logMessagge = "start : " . $startTime . " - end : " . $endTime . " - adsCounter : " . $adsCounter . " \n";
echo $logMessagge;