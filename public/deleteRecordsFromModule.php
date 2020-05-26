<?php

include_once "common.php";

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
ZCRMRestClient::initialize($configuration);

$exe = true;//on-off
$moduleName = "vendita";
$moduleIns= ZCRMRestClient::getInstance()->getModuleInstance($moduleName);  //To get module instance
$param_map=array("page"=>1,"per_page"=>100); // key-value pair containing all the parameters - optional

$max = 10;
for ($i=0; $i < $max && $exe; $i++) { 
  $response = $moduleIns->getRecords($param_map); // to get the records($param_map - parameter map,$header_map - header map
  $records = $response->getData(); // To get response data
  $recordIds = array();

  foreach ($records as $key => $record) {
    $recordIds[] = $record->getEntityId();
  }

  ZohoCrmApi::deleteRecords($recordIds, $moduleName);
}

/*@ob_clean();
$value = json_encode($recordIds);
exit($value);*/