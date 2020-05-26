<?php

define ("LIBROOT", dirname(__FILE__));

function unicodeToCharacters($string){
  $string = preg_replace("/\\\\u([0-9a-fA-F]{4})/", "&#x\\1;", $string);
  $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
  $string = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $string);
  return $string;
}

include_once LIBROOT . "/ZohoCrmApi.php";
include_once LIBROOT . "/Immobiliare.php";
include_once LIBROOT . "/Annuncio.php";