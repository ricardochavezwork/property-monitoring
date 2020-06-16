<?php

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\crud\ZCRMModule;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\crud\ZCRMException;

class Zoho_Api  {
  
  /**
   * method to check if a specific link exists
   */
  public static function LinkAdExists($link = "", $module = ""){
    try {
      $exists = 0;

      if(!$link)
        throw new Exception("Zoho_Api:LinkAdExists -> Link variable required", 1);

      if(!$module)
        throw new Exception("Zoho_Api:LinkAdExists -> Module variable required", 1);

      $criteria = "Link:equals:" . $link;
      $moduleIns=ZCRMRestClient::getInstance()->getModuleInstance($module);  //To get module instance
      $response=$moduleIns->searchRecordsByCriteria($criteria);  //To get module records that match the criteria
      $records=$response->getData();  //To get response data	

      if(count($records) > 0){
        $exists = $records[0]->getEntityId();
      }

      return $exists;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function fillRecordFromImmobiliare($record, $immobiliare, $existingRecordId){
    try {

      $skipStatus = false;

      /*
      if(!$record)
        throw new Exception("Zoho_Api:fillRecordFromImmobiliare -> Record variable required", 1);
      */
      if(!$immobiliare)
        throw new Exception("Zoho_Api:fillRecordFromImmobiliare -> Immobiliare variable required", 1);

      if($existingRecordId > 0)
        $skipStatus = true;

      foreach ($immobiliare as $key=>$value) {
        //echo $key . '->' . $value . '<br>';
        if($key === "Id" || !$value || ($skipStatus && $key === "Status"))
          continue;

        $record->setFieldValue($key, $value);
      }

      return $record;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getRecord($id, $module){
    try {
      if(!($id > 0))
        $id = null;

      $record = ZCRMRecord::getInstance($module, $id);

      return $record;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function upsertRecords($records, $moduleName){
    try {
      if(!$records)
        throw new Exception("Zoho_Api:upsertRecords -> Records variable required", 1);

      if(!$moduleName)
        throw new Exception("Zoho_Api:upsertRecords -> ModuleName variable required", 1);
      
      //Now, you create a module instance with ZCRMModule::getInstance. Is another object and you must provide the API name of the module. This is a dummy object, so it won't do nothing. In $zcrmModuleIns->upsertRecords($recordsArray); you parse the array to upsert the records. Upsert is for insert (or update if the record already exist) a record.
      $zcrmModuleIns = ZCRMModule::getInstance($moduleName);
      $bulkAPIResponse=$zcrmModuleIns->upsertRecords($records);
      //Here you obtain the response from the upsert. Because this was made for testing, there is a lot of "echo". The "if" clause gives the info about what happened if everything is going right, the "else" gives the info when something is wrong but the API works. For example, if the module name is invalid.
      $entityResponses = $bulkAPIResponse->getEntityResponses();

      /*$splits = Array();
      $totRecords = count($records);
      $totSplits = $totRecords/100;

      for ($x=0; $x < $totSplits; $x++) { 
        $bulkAPIResponse=$zcrmModuleIns->upsertRecords(array_splice($records, 0, 100));
        //Here you obtain the response from the upsert. Because this was made for testing, there is a lot of "echo". The "if" clause gives the info about what happened if everything is going right, the "else" gives the info when something is wrong but the API works. For example, if the module name is invalid.
        $entityResponses = $bulkAPIResponse->getEntityResponses();
      }*/

      /*foreach($entityResponses as $entityResponse){
        if("success"==$entityResponse->getStatus()){
          echo "Status:".$entityResponse->getStatus();
          echo "Message:".$entityResponse->getMessage();
          echo "Code:".$entityResponse->getCode();
          $upsertData=$entityResponse->getUpsertDetails();
          echo "UPSERT_ACTION:".$upsertData["action"];
          echo "UPSERT_DUPLICATE_FIELD:".$upsertData["duplicate_field"];
          $createdRecordInstance=$entityResponse->getData();
          echo "EntityID:".$createdRecordInstance->getEntityId();
          echo "moduleAPIName:".$createdRecordInstance->getModuleAPIName();
          echo "<br>";
        }else{
          echo "Status:".$entityResponse->getStatus();
          echo "Message:".$entityResponse->getMessage();
          echo "Code:".$entityResponse->getCode();
          echo "<br>";
        }
      }*/


    } catch (Exception $e) {
      return false;
    }
  }

  public static function deleteRecords($recordids, $moduleName){
    try {
      if(!$recordids)
        throw new Exception("recordsId undefined!", 1);

      if(!$moduleName)
        throw new Exception("moduleName undefined!", 1);
        
        
      $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance($moduleName);
      $responseIn = $moduleIns->deleteRecords($recordids); // to delete the records

    } catch (Exception $e) {
      return false;
    }
  }

}
