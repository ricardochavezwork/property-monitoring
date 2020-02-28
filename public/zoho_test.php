<?php

include_once "../api.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMModule;

set_time_limit(0);

$configuration=array(
  "client_id" => "1000.3WT992YU7QPLJCM7836EAF5Q422KQR",
  "client_secret" => "76c7f4466e13aab3bc6a08f3ead1a88dc61ceddfe9",
  "redirect_uri" => "https://www.milanostanze.it",
  "currentUserEmail" => "ricardo.chavez@milanostanze.it",
  "token_persistence_path" => "../api/zohocrm/tokenPersistence",
  "apiBaseUrl" => "www.zohoapis.eu",
  "accounts_url" => "https://accounts.zoho.eu",
  "access_type" => "offline",
  "apiVersion"=>"v2"
);

/*ZCRMRestClient::initialize($configuration);

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
exit($val);*/

/*ZCRMRestClient::initialize($configuration);
$oAuthClient = ZohoOAuth::getClientInstance();
$grantToken = "1000.b4b266cf321fa9af383dbc120850ebc0.a8017eb0eab8138c7b6a8453848be8d6";
$oAuthTokens = $oAuthClient->generateAccessToken($grantToken);
var_dump($oAuthTokens);*/

ZCRMRestClient::initialize($configuration);
$oAuthClient = ZohoOAuth::getClientInstance(); 
$refreshToken = "1000.ab91e3a62c5050c109ba6752baff9921.18388c48f2bd4fa47eba2105cb52ec35"; 
$userIdentifier = "ricardo.chavez@milanostanze.it"; 
$oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($refreshToken,$userIdentifier);
var_dump($oAuthClient);

?>



