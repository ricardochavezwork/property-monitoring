<?php 

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMModule;

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Europe/Rome");

/*$oAuthClient = ZohoOAuth::getClientInstance();
$grantToken = "1000.f16c12dfcb40911c989ada2ed4f42e7e.3f11ad7d01b7d55b5f1aacef132778a6";
$oAuthTokens = $oAuthClient->generateAccessToken($grantToken);*/

/*$configuration=array(
  "client_id" => "1000.3WT992YU7QPLJCM7836EAF5Q422KQR",
  "client_secret" => "76c7f4466e13aab3bc6a08f3ead1a88dc61ceddfe9",
  "redirect_uri" => "https://www.milanostanze.it",
  "currentUserEmail" => "ricardo.chavez@milanostanze.it",
  "token_persistence_path" => __DIR__ . "/../_gestione/TokenStorage",
  "apiBaseUrl" => "www.zohoapis.eu",
  "accounts_url" => "https://accounts.zoho.eu",
  "access_type" => "offline",
  "apiVersion"=>"v2"
);*/

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

/*$oAuthClient = ZohoOAuth::getClientInstance(); 
$grantToken = "1000.6d8a0432ef0bcebf59a6384d6972901b.cc247cf1c0fbdd37cd52b92af89dafd0"; 
$oAuthTokens = $oAuthClient->generateAccessToken($grantToken);*/

/*$oAuthClient = ZohoOAuth::getClientInstance();
$refreshToken =  "1000.ffd646914c72eaf1a4f82043a761617d.ed2c6296cd2fecebdff6589aef346312";
$userIdentifier = "ricardo.chavez@milanostanze.it";
$oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($refreshToken,$userIdentifier);*/

$zcrmModuleIns = ZCRMModule::getInstance("Contacts");
$bulkAPIResponse=$zcrmModuleIns->getRecords();
$recordsArray = $bulkAPIResponse->getData(); // $recordsArray - array of ZCRMRecord instances
