<?php

include_once "common.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
ZCRMRestClient::initialize($configuration);

$exe = false;
$total_pages = 0;
$adsContainer = Array();
$adsCounter = 0;
$moduleName = "vendita";
$startTime = date("Y-m-d H:i:s");
$ad_id = '79807845';
//setlocale(LC_ALL, 'en_US.UTF-8');

if($exe){
  $ad_link = 'https://www.immobiliare.it/annunci/' . $ad_id;
  $tettoMassimo = 300;
  $zona = 4;//porta venezia

  //$hasError = Immobiliare::hasErrorPage($ad_link);

  $ad = Immobiliare::getAnnuncio($ad_link, $zona, null);
  /*$existingRecordId = ZohoCrmApi::LinkAdExists($ad_link, $moduleName);
  $record = ZohoCrmApi::getRecord($existingRecordId, $moduleName);
  $record = ZohoCrmApi::fillRecordFromImmobiliare($record, $ad, $existingRecordId);
  //$record->setFieldValue("TestoAnnuncio", "Test");
  ZohoCrmApi::upsertRecords(Array($record), $moduleName);*/

  @ob_clean();
  $value = json_encode($ad);
  exit($value);

  $endTime = date("Y-m-d H:i:s");
  $logMessagge = "start : " . $startTime . " - end : " . $endTime . " - adsCounter : " . $adsCounter . " \n";
  //echo $logMessagge;
}