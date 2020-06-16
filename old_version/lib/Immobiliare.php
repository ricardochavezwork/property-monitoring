<?php

include_once __DIR__ . "/../lib/Goutte-master/goutte-v1.0.7.phar";
include_once __DIR__ . "/../lib/Annuncio.php";

use Goutte\Client;
use Spatie\Crawler\Crawler;

class Immobiliare /*extends DataClass*/ {

  public static function get_status_by_link($link){
    try {

      if(!$link)
        throw new Exception("Immobiliare:get_status_by_link -> link required!", 1);
        
      global $Database;
      $sql = sprintf("SELECT count(*) as count, Status FROM AnnunciAcquisizioni WHERE Link = '%s' AND Status > 1", $link);
      $result = new stdClass();
      $result->exists = false;

      if ($res = $Database->Query($sql)){
        if ($row = $Database->Fetch($res)){
          if(intval($row["count"]) > 0){
            $result->exists = true;
            $result->statusNumber = $row["Status"];
          }
        }
      }

      return $result;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function translate_status_to_zoho($statusNumber){
    $status = "Da valutare";

    switch ($statusNumber) {
      case 1:
      case 0:
        $status = "Da valutare";
        break;
      case 2:
        $status = "Archiviato";
        break;
      case 3:
        $status = "Da chiamare";
        break;
      case 4:
        $status = "Rifiutato";
        break;
      case 5:
        $status = "In attesa di riscontro";
        break;
      case 6:
        $status = "Ok appuntamento";
        break;
    }

    return $status;

  }

  public static function getTotalPages($link = "", $callback = null){
    try {
      if(!$link)
        throw new Exception("Immobiliare:getTotalPages -> Link required", 1);
        
      $total = 0;
      $client = new Client();
      $crawler = $client->request('GET', $link);

      $crawler->filter('#listing-pagination > ul.pagination.pagination__number > li:last-child a')->each(function ($node) use (&$total){
        $total = intval($node->text());
      });
      
      if(!$total)
        $total = 1;

      return $total;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getAdsLinkFromPage($pageLink = ""){
    try {
      if(!$pageLink)
        throw new Exception("Immobiliare:getAdsLinkFromPage", 1);
      
      $client = new Client();
      $links = Array();
      $crawler = $client->request('GET', $pageLink);

      $crawler->filter('#listing-container > li.listing-item')->each(function ($node) use (&$links) {
        $links;
        if($node->attr('data-id'))
          $links[] = $node->attr('data-id');
      });

      return $links;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function getAnnuncio($link = "", $zona, $tettoMassimo){
    try {
      if(!$link)
        throw new Exception("Immobiliare:getAnnuncio -> Link required", 1);

      if(!$zona && !$tettoMassimo)
        throw new Exception("Immobiliare:getAnnuncio -> Zona or TettoMassimo required", 1);
      
      $client = new Client();
      $crawler = $client->request('GET', $link);

      $ad = new Annuncio();
      $ad->Link = $link;

      $fields = Array();

      $crawler->filter('#sticky-contact-bottom > div.left-side > h1')->each(function($node, $index) use (&$ad){
        $ad->Indirizzo = $node->text();
      });

      $crawler->filter('#up-contact-box > div.contact-data > div > div:nth-child(2) > p > a')->each(function ($node) use (&$ad){
        $agenzia = $node->text();
        $agenzia = str_replace("&nbsp;", "", $agenzia);
        $agenzia = trim(preg_replace('/\s\s+/', ' ', $agenzia));
        $ad->Agenzia = $agenzia;
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(1) > div:nth-child(1) > span')->each(function ($node) use (&$ad){
        $locali = htmlentities($node->text(), null, 'utf-8');
        $ad->Locali = intval(str_replace("&nbsp;", "", $locali));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(2) > div:nth-child(1) > span')->each(function ($node) use (&$ad){
        $superficie = htmlentities($node->text(), null, 'utf-8');
        $ad->SuperficieMq = intval(str_replace("&nbsp;", "", $superficie));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(3) > div:nth-child(1) > span')->each(function ($node) use (&$ad){
        $bagni = htmlentities($node->text(), null, 'utf-8');
        $ad->Bagni = intval(str_replace("&nbsp;", "", $bagni));
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.im-summary.js-sticky-features > div.im-property__features > ul.list-inline.list-piped.features__list > li:nth-child(4) > div:nth-child(1) > abbr')->each(function ($node) use (&$ad){
        $piano = htmlentities($node->text(), null, 'utf-8');
        $piano = str_replace("&nbsp;", "", $piano);
        $piano = trim(preg_replace('/\s\s+/', ' ', $piano));
        $ad->Piano = $piano;
      });

      $crawler->filter('#sticky-contact-bottom > div.left-side > div.section-data > dl > dt')->each(function($node, $index) use (&$fields){
        $field = new stdClass();
        $field->Title = $node->text();
        $field->Index = $index;
        $fields[] = $field;
      });

      for ($z=0; $z < count($fields); $z++) { 
            $crawler->filter('#sticky-contact-bottom > div.left-side > div.section-data > dl > dd')->each(function($node, $index) use (&$fields, &$z){
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
              $ad->Ascensore = true;
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
            $prezzo = str_replace("Vendita &euro; ", "", $prezzo);
            $prezzo = str_replace(" al mese", "", $prezzo);
            $prezzo = str_replace(".", "", $prezzo);
            $prezzo = str_replace(" ", "", $prezzo);

            if (strpos($prezzo, "mutuo") !== false) {
              $prezzo = substr($prezzo, 0, strpos($prezzo, "mutuo"));
            }
            
            $ad->Prezzo = floatval($prezzo);
            $ad->Canone = floatval($prezzo);
            break;
        }
      }

      $crawler->filter('#down-contact-box div.contact-data.highlight div.clearfix p.contact-data__name a')->each(function($node, $index) use (&$ad){
        $agenzia = $node->text();
        $agenzia = str_replace("&nbsp;", "", $agenzia);
        $agenzia = trim(preg_replace('/\s\s+/', ' ', $agenzia));
        $ad->Agenzia = $agenzia;
      });

      $crawler->filter('#up-contact-box div.contact-data span.contact__box span.info-agenzia')->each(function($node, $index) use (&$ad){
        $val = htmlentities($node->text(), null, 'utf-8');
        $val = str_replace("&nbsp;", "", $val);
        $val = trim(preg_replace('/\s\s+/', ' ', $val));
        $ad->Telefono = $val;
      });

      $crawler->filter('#planimetria img')->each(function($node, $index) use (&$ad){
        $ad->LinkPlanimetria = $node->attr('src');
      });

      if($ad->LinkPlanimetria === ""){
        $crawler->filter('#planimetria a')->each(function($node, $index) use (&$ad){
          $ad->LinkPlanimetria = $node->attr('href');
        });
      }

      if($ad->Indirizzo){
        $indirizzo = $ad->Indirizzo;
        $indirizzo = str_replace("Bilocale ", "", $indirizzo);
        $indirizzo = str_replace("Appartamento ", "", $indirizzo);
        $indirizzo = str_replace("Trilocale ", "", $indirizzo);
        $indirizzo = str_replace("Quadrilocale ", "", $indirizzo);
        $indirizzo = str_replace("Loft ", "", $indirizzo);
        $ad->LinkMappa = "https://www.google.it/maps/search/" . urlencode($indirizzo);
      }

      $ad->Zona = $zona;
      $ad->TettoMassimo = $tettoMassimo;

      return $ad;
      } catch (Exception $e) {
        return false;
      }
  }

}