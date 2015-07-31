<?php

require_once 'vendor/autoload.php';

use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'http://yahoo.co.jp');
$crawler->filter('p')->each(function ($element) {
	echo $element->text();
});

unset($client, $crawler);
