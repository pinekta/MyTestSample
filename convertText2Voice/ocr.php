<?php

require_once 'vendor/autoload.php';

for ($i = 1; $i <= 10; $i++) {
	$tesseract = new TesseractOCR("img/image{$i}.jpg");
	$tesseract->setLanguage('eng');
	$tesseract->setWhitelist(range('a','z'), range(0,9));
	echo $tesseract->recognize() . "\r";
}
