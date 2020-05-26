<?php

$goutte_dir = ROOT . "/vendor/Goutte-master/goutte-v1.0.7.phar";

if(file_exists($goutte_dir)){
  include_once $goutte_dir;
}

use Goutte\Client;
use Spatie\Crawler\Crawler;

class Immobiliare {

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
      $jsHydration = null;

      $fields = Array();

      $crawler->filter('#js-hydration')->each(function($node, $index) use (&$jsHydration){
        $jsHydration = json_decode($node->text());
      });

      /**
       * BEGIN ; JSHYDRATATION
       */

      $jsh_listing = $jsHydration->listing;
      $jsh_property = $jsh_listing->properties[0];
      $jsh_multimedia = $jsh_property->multimedia;
      $jsh_floorplans = $jsh_multimedia->floorplans;
      $jsh_location = $jsh_property->location;
      $jsh_advertiser = $jsh_listing->advertiser;
      $jsh_agency = $jsh_advertiser->agency;
      $jsh_supervisor = $jsh_advertiser->supervisor; 
      $jsh_agency_phones = ($jsh_agency && $jsh_agency->phones) ? $jsh_agency->phones : null;
      $jsh_contract = $jsh_listing->contract;
      $jsh_trovakasa = $jsHydration->trovakasa;
      $jsh_costs = $jsh_property->costs;
      $jsh_macrozone = $jsh_location->macrozone;

      /**
       * END ; JSHYDRATATION
       */
      //Test mode
      //$ad->jsHydration = $jsHydration;

      if(count($jsh_floorplans) > 0)
        $ad->LinkPlanimetria = $jsh_floorplans[0]->urls->large;

      if($jsh_location->address){
        $ad->Indirizzo = $jsh_location->address;

        if($jsh_location->city && $jsh_location->city->name)
          $ad->Indirizzo .= ', ' . $jsh_location->city->name;

      }else{
        $crawler->filter('body > div.nd-grid.im-structure__container > section:nth-child(2) > div.im-titleBlock > h1 > span')->each(function($node, $index) use (&$ad){
          $ad->Indirizzo = $node->text();
        });
      }

      $agencyName = ($jsh_agency && $jsh_agency->displayName) ? unicodeToCharacters($jsh_agency->displayName) : $jsh_supervisor->displayName;
      $ad->Agenzia = $agencyName;
      $ad->Contratto = $jsh_contract->name;
      $ad->Locali = $jsh_trovakasa->locMax;
      $ad->SuperficieMq = str_replace(' m²', '', $jsh_property->surfaceValue);
      $ad->Bagni = ($jsh_trovakasa && $jsh_trovakasa->bagni) ? $jsh_trovakasa->bagni : 1;
      $ad->Canone = $jsh_costs->price;
      $ad->Prezzo = $jsh_costs->price;
      $ad->ZonaName = $jsh_macrozone->name;
      $ad->Titolo = $jsh_listing->title;

      if(count($jsh_agency_phones) > 0)
        $ad->Telefono = $jsh_agency_phones[0]->formattedValue;

      $crawler->filter('div.nd-grid.im-structure__container > section.im-structure__mainContent > dl.im-features__list > dt.im-features__title')->each(function($node, $index) use (&$fields){
        $field = new stdClass();
        $field->Title = $node->text();
        $field->Index = $index;
        $fields[] = $field;
      });

      for ($z=0; $z < count($fields); $z++) { 
            $crawler->filter('div.nd-grid.im-structure__container > section.im-structure__mainContent > dl.im-features__list > dd.im-features__value')->each(function($node, $index) use (&$fields, &$z){
              if($fields[$z]->Index === $index){
                $value = htmlentities($node->text(), null, 'utf-8');
                $value = str_replace("&nbsp;", "", $value);
                $value = trim(preg_replace('/\s\s+/', ' ', $value));
                //$fields[$z]->Value = $node->text();
                $fields[$z]->Value = $value;
              }
            });
      }

      //Test mode
      //$ad->Array = $fields;

      for ($zx=0; $zx < count($fields); $zx++) { 
        switch ($fields[$zx]->Title) {
          case 'piano':
            if(strpos($fields[$zx]->Value, 'con ascensore')){
              $ad->Ascensore = true;
            }

            $piano = explode("&deg;", $fields[$zx]->Value);
            $ad->Piano = $piano[0];
            break;
          case 'riscaldamento':
            $riscaldamento = $fields[$zx]->Value;
            if(strpos($riscaldamento, 'Centralizzato') === 0){
              $ad->TipoRiscaldamento = 1;
            }else if(strpos($riscaldamento, 'Autonomo') === 0){
              $ad->TipoRiscaldamento = 2;
            }
            break;
          case 'spese condominio':
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
          case 'spese riscaldamento':
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
        }
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

      $crawler->filter('body > div.nd-grid.im-structure__container > section.im-structure__mainContent > nd-read-all > div.im-readAll__container.js-readAllContainer > div.im-description__text.js-readAllText')->each(function($node, $index) use (&$ad){
        $descrizione = htmlentities($node->text(), null, 'utf-8');
        $descrizione = str_replace("&nbsp;", "", $descrizione);
        $descrizione = trim(preg_replace('/\s\s+/', ' ', $descrizione));

        if (strlen($descrizione) > 2000)
          $descrizione = substr($descrizione, 0, 1990) . '...';

        $ad->TestoAnnuncio = $descrizione;
      });

      $ad->Zona = $zona;

      if($tettoMassimo > 0){
        $reduceTettoMassimoPerc = 15;
        $reduceTettoAmount = $tettoMassimo/100*$reduceTettoMassimoPerc;
        $ad->TettoMassimo = ($tettoMassimo - $reduceTettoAmount);
      }

      return $ad;
      } catch (Exception $e) {
        return false;
      }
  }

}