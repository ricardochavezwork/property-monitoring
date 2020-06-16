<?php

include 'common.php';
include_once LIBROOT . "/Goutte-master/goutte-v1.0.7.phar";

use Goutte\Client;
use Spatie\Crawler\Crawler;

set_time_limit(900);

if (isset($_REQUEST["page"]))
  $pageNumber = intval($_REQUEST["page"]);

$total_pages;
$testArray = Array();
$testAds = Array();
$client = new Client();
$exe = true;

if(!($pageNumber >= 0))
  $exe = false;

$requests = Array(
  Array(1, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10046"),
  Array(2, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10047"),
  Array(3, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10053"),
  Array(4, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10050"),
  Array(5, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10056"),
  Array(6, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10049"),
  Array(7, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10057"),
  Array(8, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10067"),
  Array(9, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10321"),
  Array(10, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10066"),
  Array(11, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10065"),
  Array(12, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10292"),
  Array(13, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10055"),
  Array(14, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10054"),
  Array(15, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10064"),
  Array(16, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10072"),
  Array(17, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10071"),
  Array(18, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10296"),
  Array(19, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10061"),
  Array(20, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10318"),
  Array(21, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10316"),
  Array(22, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10295"),
  Array(23, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10317"),
  Array(24, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10070"),
  Array(25, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10060"),
  Array(26, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10059"),
  Array(27, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10068"),
  Array(28, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10293"),
  Array(29, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10320"),
  Array(30, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10069"),
  Array(31, "https://www.immobiliare.it/affitto-case/milano/?criterio=rilevanza&superficieMinima=90&idMZona[]=10319")
);

$req = $requests[$pageNumber];
$pageLimit = 1;

//$crawler = $client->request('GET', 'https://www.immobiliare.it/affitto-case/milano/?criterio=dataModifica&ordine=desc&superficieMinima=70&localiMinimo=3');

$crawler = $client->request('GET', $req[1]);

$crawler->filter('#listing-pagination > ul.pagination.pagination__number > li:last-child a')->each(function ($node){
  global $total_pages;
  $total_pages = intval($node->text());
});

if(!$total_pages)
  $total_pages = 1;

if($total_pages > 0 && $exe){
  for ($i=0; $i < $total_pages; $i++) {
    $pager = '&pag=' . ($i+1);

    if($i === 0)
      $pager = "";
      
    $crawler = $client->request('GET', $req[1] . $pager);

    $crawler->filter('#listing-container > li.listing-item')->each(function ($node) {
      global $testArray;
      if($node->attr('data-id'))
        $testArray[] = $node->attr('data-id');
    });
  }


  if(count($testArray) > 0){
    for ($x=0; $x < count($testArray); $x++) {
      $adLink = 'https://www.immobiliare.it/annunci/' . $testArray[$x];

      $crawler = $client->request('GET', $adLink);  

      $id = Crawler_Ad_Rent::SearchByLink($adLink);
      $ad = new Crawler_Ad_Rent($id);

      //$ad->Id = Crawler_Ad::SearchByLink($adLink);
      $ad->Link = $adLink;

      $fields = Array();

      $crawler->filter('#sticky-contact-bottom > div.left-side > h1')->each(function($node, $index){
        global $ad;
        $ad->Indirizzo = $node->text();
      });

      $crawler->filter('#up-contact-box > div.contact-data.highlight > div.clearfix > div:nth-child(1) > p > a')->each(function ($node){
        global $ad;
        $ad->Agenzia = $node->text();
      });

      /*$crawler->filter('#maps-container > div.map-block > div.map.leaflet-container.leaflet-touch.leaflet-retina.leaflet-grab.leaflet-touch-drag.leaflet-touch-zoom > div.leaflet-control-container > div.leaflet-top.leaflet-left > span')->each(function ($node){
        global $ad;
        $ad->Indirizzo = $node->text();
      });*/

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(1) > div:nth-child(1) > span')->each(function ($node){
        global $ad;
        $locali = htmlentities($node->text(), null, 'utf-8');
        $ad->Locali = intval(str_replace("&nbsp;", "", $locali));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(2) > div:nth-child(1) > span')->each(function ($node){
        global $ad;
        $superficie = htmlentities($node->text(), null, 'utf-8');
        $ad->SuperficieM2 = intval(str_replace("&nbsp;", "", $superficie));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(3) > div:nth-child(1) > span')->each(function ($node){
        global $ad;
        $bagni = htmlentities($node->text(), null, 'utf-8');
        $ad->Bagni = intval(str_replace("&nbsp;", "", $bagni));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(4) > div:nth-child(1) > abbr')->each(function ($node){
        global $ad;
        $piano = htmlentities($node->text(), null, 'utf-8');
        $piano = str_replace("&nbsp;", "", $piano);
        $piano = trim(preg_replace('/\s\s+/', ' ', $piano));
        $ad->Piano = $piano;
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.section-data > dl > dt')->each(function($node, $index){
        global $fields;
        $field = new stdClass();
        $field->Title = $node->text();
        $field->Index = $index;
        $fields[] = $field;
      });

      for ($z=0; $z < count($fields); $z++) { 
            $crawler->filter('#sticky-contact-bottom > div.left-side > div.section-data > dl > dd')->each(function($node, $index){
              global $fields;
              global $z;
              if($fields[$z]->Index === $index){
                $value = htmlentities($node->text(), null, 'utf-8');
                $value = str_replace("&nbsp;", "", $value);
                $value = trim(preg_replace('/\s\s+/', ' ', $value));
                //$fields[$z]->Value = $node->text();
                $fields[$z]->Value = $value;
              }
            });
      }

      for ($zx=0; $zx < count($fields); $zx++) { 
        switch ($fields[$zx]->Title) {
          case 'Contratto':
            $ad->Contratto = $fields[$zx]->Value;
            break;
          case 'Piano':
            if(strpos($fields[$zx]->Value, 'con ascensore')){
              $ad->Ascensore = 1;
            }
            break;
          case 'Riscaldamento':
            $riscaldamento = $fields[$zx]->Value;
            if(strpos($riscaldamento, 'Centralizzato') === 0){
              $ad->TipoRiscaldamento = 1;
            }else if(strpos($riscaldamento, 'Autonomo') === 0){
              $ad->TipoRiscaldamento = 2;
            }
            break;
          case 'Spese condominio':
            $speseCondominio = str_replace("&euro; ", "", $fields[$zx]->Value);
            $speseCondominio = str_replace(".", "", $speseCondominio);

            if(strpos($speseCondominio, 'mese')){
              $speseCondominio = str_replace("/mese", "", $speseCondominio);
            }else if(strpos($speseCondominio, 'anno')){
              $speseCondominio = str_replace("/anno", "", $speseCondominio);
              $speseCondominio = floatval($speseCondominio)/12;
            }

            

            $ad->SpeseCondominio = floatval($speseCondominio) > 1 ? floatval($speseCondominio) : 0;
            break;
          case 'Spese riscaldamento':
            $speseRiscaldamento = str_replace("&euro; ", "", $fields[$zx]->Value);
            $speseRiscaldamento = str_replace(".", "", $speseRiscaldamento);

            if(strpos($speseRiscaldamento, 'mese')){
              $speseRiscaldamento = str_replace("/mese", "", $speseRiscaldamento);
            }else if(strpos($speseRiscaldamento, 'anno')){
              $speseRiscaldamento = str_replace("/anno", "", $speseRiscaldamento);
              $speseRiscaldamento = floatval($speseRiscaldamento)/12;
            }
            
            $ad->SpeseRiscaldamento = floatval($speseRiscaldamento) > 1 ? floatval($speseRiscaldamento) : 0;
            break;
          case 'Prezzo':
            $prezzo = str_replace("Affitto &euro; ", "", $fields[$zx]->Value);
            $prezzo = str_replace(" al mese", "", $prezzo);
            $prezzo = str_replace(".", "", $prezzo);
            
            $ad->Prezzo = floatval($prezzo);
            break;
        }
      }

      $crawler->filter('#down-contact-box div.contact-data.highlight div.clearfix p.contact-data__name a')->each(function($node, $index){
        global $ad;
        $ad->Agenzia = $node->text();
      });

      $crawler->filter('#up-contact-box div.contact-data span.contact__box span.info-agenzia')->each(function($node, $index){
        global $ad;
        $val = htmlentities($node->text(), null, 'utf-8');
        $val = str_replace("&nbsp;", "", $val);
        $val = trim(preg_replace('/\s\s+/', ' ', $val));
        $ad->Telefono = $val;
      });

      $crawler->filter('#planimetria img')->each(function($node, $index){
        global $ad;
        $ad->LinkPlanimetria = $node->attr('src');
      });

      //$ad->Fields = $fields;

      $ad->Zona = $req[0];

      $ad->Save();

      $testAds[] = $ad;

    }
  }

}


// Click on the "Security Advisories" link
/*$link = $crawler->selectLink('Prezzi immobili')->link();
$crawler = $client->click($link);*/



//Utils::PrintJson($testAds, true);