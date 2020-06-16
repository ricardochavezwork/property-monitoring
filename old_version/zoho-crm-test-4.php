
<?php 
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\crm\bulkcrud\ZCRMBulkCallBack;
use zcrmsdk\crm\bulkcrud\ZCRMBulkCriteria;
use zcrmsdk\crm\bulkcrud\ZCRMBulkWriteFieldMapping;
use zcrmsdk\crm\bulkcrud\ZCRMBulkWriteResource;
use zcrmsdk\crm\utility\ZCRMConfigUtil;
require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Europe/Rome");

class BulkWrite
{
    public function __construct()
    {
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
    }

    /** Create Bulk Write Job */
    /** Insert record */
    public static function createBulkWriteJob()
    {
        $writeJob = ZCRMRestClient::getInstance()->getBulkWriteInstance();// To get ZCRMBulkWrite instance
        $writeJob->setOperation("insert");//To set the type of operation you want to perform on the bulk write job.
        
        $writeJob->setCharacterEncoding("UTF-8");
        
        $callBackIns = ZCRMBulkCallBack::getInstance("https://www.zoho.com", "post");
        $writeJob->setCallback($callBackIns);
        
        $resourceIns = ZCRMBulkWriteResource::getInstance("ModuloTest", "324555225"); //Specify the ModuleAPIName and the uploaded file Id.
        $resourceIns->setType("data");// To set the type of module that you want to import. The value is data.
        $resourceIns->setIgnoreEmpty(true);//True - Ignores the empty values.The default value is false.
        
        $fieldMappings = ZCRMBulkWriteFieldMapping::getInstance("Nome");
        $fieldMappings->setDefaultValue("value", "test");//To set the default value for an empty column in the uploaded file.
        $resourceIns->setFieldMapping($fieldMappings);
        
        //$fieldMappings = ZCRMBulkWriteFieldMapping::getInstance("Nome", "0");//To get ZCRMBulkWriteFieldMapping instance using Field APIName and column index of the field in the uploaded file.
        //$resourceIns->setFieldMapping($fieldMappings);
        
        //$fieldMappings = ZCRMBulkWriteFieldMapping::getInstance("Company", "1");
        //$resourceIns->setFieldMapping($fieldMappings);
        
        //$fieldMappings = ZCRMBulkWriteFieldMapping::getInstance("Phone", "2");
        //$resourceIns->setFieldMapping($fieldMappings);
        
        //$fieldMappings = ZCRMBulkWriteFieldMapping::getInstance("Website");
        //$fieldMappings->setDefaultValue("value", "https://www.zoho.com");
        //$resourceIns->setFieldMapping($fieldMappings);

        $writeJob->setResource($resourceIns);// To set ZCRMBulkWriteFieldMapping instance
        
        $response = $writeJob->createBulkWriteJob();// To create bulk write job.
        echo "HTTP Status Code:" . $response->getHttpStatusCode()."\n"; // To get http response code
        echo "Status:" . $response->getStatus()."\n"; // To get response status
        echo "Message:" . $response->getMessage()."\n"; // To get response message
        echo "Code:" . $response->getCode()."\n"; // To get status code
        echo "Details:" . json_encode($response->getDetails())."\n";
        echo "Response Json".json_encode($response->getResponseJSON())."\n";
        
        $record = $response->getData();// To get ZCRMBulkWrite instance.
        
        echo ($record->getJobId())."\n";// To get bulk write job Id.
        
        $created_by = $record->getCreatedBy();
        echo $created_by->getId()."\n";
        echo $created_by->getName()."\n";
    }
}
$obj =new BulkWrite();
$obj->createBulkWriteJob();
 
 