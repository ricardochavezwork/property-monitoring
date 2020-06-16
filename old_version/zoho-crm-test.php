<?php 
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;

require_once __DIR__ . '/../vendor/autoload.php';

class RestClient{
    public function __construct()
    {
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
        $refreshToken =  "1000.66ec6097ceefe6e01307abf6c8da7f04.c85e11779e14c9254e030e89de22a905";
        $userIdentifier = "ricardo.chavez@milanostanze.it";
        $oAuthTokens = $oAuthClient->generateAccessTokenFromRefreshToken($refreshToken,$userIdentifier);*/

        /*$oAuthClient = ZohoOAuth::getClientInstance();
        $grantToken = "1000.bd4faea9109e08c26baf6edc9cb0ba00.e01114bfd6fc8335e253646a94c52237"; 
        $oAuthTokens = $oAuthClient->generateAccessToken($grantToken);*/
            
    }
    public function getCurrentUser(){
        $rest=ZCRMRestClient::getInstance();//to get the rest client
        $users=$rest->getCurrentUser()->getData();//to get the users in form of ZCRMUser instances array
        foreach($users as $userInstance){
            echo $userInstance->getId();//to get the user id
            echo $userInstance->getCountry();//to get the country of the user
            $roleInstance=$userInstance->getRole();//to get the role of the user in form of ZCRMRole instance
            echo $roleInstance->getId();//to get the role id
            echo $roleInstance->getName();//to get the role name
            $customizeInstance=$userInstance->getCustomizeInfo();//to get the customization information of the user in for of the ZCRMUserCustomizeInfo form
            if($customizeInstance!=null)
            {
                echo $customizeInstance->getNotesDesc();//to get the note description
                echo $customizeInstance->getUnpinRecentItem();//to get the unpinned recent items
                echo $customizeInstance->isToShowRightPanel();//to check whether the right panel is shown
                echo $customizeInstance->isBcView();//to check whether the business card view is enabled
                echo $customizeInstance->isToShowHome();//to check whether the home is shown
                echo $customizeInstance->isToShowDetailView();//to check whether the detail view is shows
            }
            echo $userInstance->getCity();//to get the city of the user
            echo $userInstance->getSignature();//to get the signature of the user
            echo $userInstance->getNameFormat();// to get the name format of the user
            echo $userInstance->getLanguage();//to get the language of the user
            echo $userInstance->getLocale();//to get the locale of the user
            echo $userInstance->isPersonalAccount();//to check whther this is a personal account
            echo $userInstance->getDefaultTabGroup();//to get the default tab group
            echo $userInstance->getAlias();//to get the alias of the user
            echo $userInstance->getStreet();//to get the street name of the user
            $themeInstance=$userInstance->getTheme();//to get the theme of the user in form of the ZCRMUserTheme
            if($themeInstance!=null)
            {
                echo $themeInstance->getNormalTabFontColor();//to get the normal tab font color
                echo $themeInstance->getNormalTabBackground();//to get the normal tab background
                echo $themeInstance->getSelectedTabFontColor();//to get the selected tab font color
                echo $themeInstance->getSelectedTabBackground();//to get the selected tab background
            }
            echo $userInstance->getState();//to get the state of the user
            echo $userInstance->getCountryLocale();//to get the country locale of the user
            echo $userInstance->getFax();//to get the fax number of the user
            echo $userInstance->getFirstName();//to get the first name of the user
            echo $userInstance->getEmail();//to get the email id of the user
            echo $userInstance->getZip();//to get the zip code of the user
            echo $userInstance->getDecimalSeparator();//to get the decimal separator
            echo $userInstance->getWebsite();//to get the website of the user
            echo $userInstance->getTimeFormat();//to get the time format of the user
            $profile= $userInstance->getProfile();//to get the user's profile in form of ZCRMProfile
            echo $profile->getId();//to get the profile id
            echo $profile->getName();//to get the name of the profile
            echo $userInstance->getMobile();//to get the mobile number of the user
            echo $userInstance->getLastName();//to get the last name of the user
            echo $userInstance->getTimeZone();//to get the time zone of the user
            echo $userInstance->getZuid();//to get the zoho user id of the user
            echo $userInstance->isConfirm();//to check whether it is a confirmed user
            echo $userInstance->getFullName();//to get the full name of the user
            echo $userInstance->getPhone();//to get the phone number of the user
            echo $userInstance->getDob();//to get the date of birth of the user
            echo $userInstance->getDateFormat();//to get the date format
            echo $userInstance->getStatus();//to get the status of the user
        }
        
    }
}
$obj =new RestClient();
$obj->getCurrentUser();
 