<?php 

include_once "../api.php";

$goutte_dir = ROOT . "/vendor/Goutte-master/goutte-v1.0.7.phar";

if(file_exists($goutte_dir)){
  include_once $goutte_dir;
}

use Goutte\Client;
$client = new Client();

// Go to the symfony.com website
$crawler = $client->request('GET', 'http://www.symfony.com/blog/');

// Click on the "Security Advisories" link
$link = $crawler->selectLink('Security Advisories')->link();
$crawler = $client->click($link);

// Get the latest post in this category and display the titles
$crawler->filter('h2 > a')->each(function ($node) {
  print $node->text()."\n";
});
