<?php

define ("ROOT", dirname(dirname(__FILE__)));
define ("LIBROOT", dirname(__FILE__));
if (!defined("PHP_SELF")) define ("PHP_SELF", $_SERVER["PHP_SELF"]);

include_once dirname(__FILE__) . "/constants.php";
include_once dirname(__FILE__) . "/config.php";

// Base classes
include_once LIBROOT . "/Database.php";
include_once LIBROOT . "/Utils.php";
include_once LIBROOT . "/FileUpload.php";
include_once LIBROOT . "/AppLog.php";
include_once LIBROOT . "/Mail.php";
include_once LIBROOT . "/Encryption.php";
//include_once LIBROOT . "/tcpdf/tcpdf.php";
//include_once LIBROOT . "/tcpdf/tcpdf_import.php";
//include_once LIBROOT . "/tcpdf/config/tcpdf_config.php";

// Database classes
include_once LIBROOT . "/Auth.php";
include_once LIBROOT . "/AdminAccount.php";
include_once LIBROOT . "/ServerSettings.php";
include_once LIBROOT . "/Nazione.php";
include_once LIBROOT . "/Zona.php";
include_once LIBROOT . "/Attribuzione.php";
include_once LIBROOT . "/Proprietario_Agenzia.php";
include_once LIBROOT . "/Appartamento.php";
include_once LIBROOT . "/Intestatario.php";
include_once LIBROOT . "/Cliente.php";
include_once LIBROOT . "/Fornitore.php";
include_once LIBROOT . "/Inquilino.php";
include_once LIBROOT . "/AltroCliente.php";
include_once LIBROOT . "/Richiedente.php";
include_once LIBROOT . "/Transazione.php";
include_once LIBROOT . "/RamoMovimento_DocumentoFiscale.php";
include_once LIBROOT . "/Movimento_Intestatario.php";
include_once LIBROOT . "/Fattura.php";
include_once LIBROOT . "/Ticket.php";
include_once LIBROOT . "/CodiceFiscale.php";
include_once LIBROOT . "/Cities.php";
include_once LIBROOT . "/ActivityType.php";
include_once LIBROOT . "/Logs.php";
include_once LIBROOT . "/EmailLogs.php";
include_once LIBROOT . "/Intestatario_DocumentoFiscale.php";
include_once LIBROOT . "/TagCategories.php";
include_once LIBROOT . "/TagTicket.php";
include_once LIBROOT . "/TagTicket_TagCategories.php";
include_once LIBROOT . "/Ticket_TagTicket.php";
include_once LIBROOT . "/TagMovimento.php";
include_once LIBROOT . "/TagFornitore.php";
include_once LIBROOT . "/TagDocumentoFiscale.php";
include_once LIBROOT . "/TagServizio.php";
include_once LIBROOT . "/TagServizio_TagCategories.php";
include_once LIBROOT . "/TagMovimenti_TagCategories.php";
include_once LIBROOT . "/TagFornitori_TagCategories.php";
include_once LIBROOT . "/TagDocumentiFiscali_TagCategories.php";
include_once LIBROOT . "/Servizio_TagServizio.php";
include_once LIBROOT . "/RamoMovimento_TagMovimenti.php";
include_once LIBROOT . "/MovimentoDettagli_TagMovimenti.php";
include_once LIBROOT . "/Fornitore_TagFornitore.php";
include_once LIBROOT . "/Servizio_TagServizio.php";
include_once LIBROOT . "/DocumentiFiscali_TagDocumentiFiscali.php";
include_once LIBROOT . "/Booking_Details.php";
include_once LIBROOT . "/FE_CustomerNotification.php";
include_once LIBROOT . "/ArticleNews.php";
include_once LIBROOT . "/Crawler_Ad.php";
include_once LIBROOT . "/Crawler_Ad_Sell.php";
include_once LIBROOT . "/Crawler_Ad_Rent.php";

ob_start();
session_start();

$LINGUE = array("it", "en");
$LINGUE_TEXT = array(
    "it" => "Italiano",
    "en" => "English"
    );

// Cambio lingua
$newLanguage = filter_input(INPUT_GET, "lang");
if (!$newLanguage)
    $newLanguage = filter_input(INPUT_POST, "lang");
if ($newLanguage && in_array($newLanguage, $LINGUE)) {
    $_SESSION["Language"] = $newLanguage;
}

$Language = "it";
if (!isset($isAdmin)) {
    if (isset($_SESSION["Language"]) && in_array($_SESSION["Language"], $LINGUE))
        $Language = $_SESSION["Language"];
    if (isset($_REQUEST["Language"]) && in_array($_REQUEST["Language"], $LINGUE)) {
        $Language = stripslashes($_REQUEST["Language"]);
        $_SESSION["Language"] = $Language;
    }
}

switch ($Language) {
    case "en":
        setlocale(LC_TIME, "en_GB", "eng");
        break;
    default:
        setlocale(LC_TIME, "it_IT", "ita");
        break;
}

// Initialize the application logging utility
AppLog::Initialize();

// Initialize the global Database
global $Database;
$Database = new Database(DB_SERVER, DB_NAME, DB_USER, DB_PASS);

// Initialize the global logged account (back-end)
global $AdminLogged;
$AdminLogged = AdminAccount::GetSession();

// Initialize the game server settings
global $ServerSettings;
$ServerSettings = ServerSettings::GetCurrentSettings();

global $includeInvisible;
