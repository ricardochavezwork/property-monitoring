<?php

include_once "common.php";

$goutte_dir = ROOT . "/vendor/Goutte-master/goutte-v1.0.7.phar";

if(file_exists($goutte_dir)){
  include_once $goutte_dir;
}

use Goutte\Client;
use Spatie\Crawler\Crawler;

$adsContainer = Array();
$request = "https://www.idealista.it/affitto-case/milano/garibaldi-porta-venezia/con-dimensione_70,trilocali-3,quadrilocali-4,5-locali-o-piu/";

$client = new Client();
/*$crawler = $client->request('GET', $request);
$totalAds = 0;

$crawler->filter('#h1-container')->each(function ($node) use (&$totalAds){
  $totalAds = intval($node->text());
});

echo $totalAds;*/

$src = "vuoto";
$r2 = "https://www.idealista.it/immobile/17751687/";
$crawler = $client->request('GET', $r2);
$crawler->filter('#sMap')->each(function ($node) use (&$src){
  $src = $node->attr('src');
});

echo $src;