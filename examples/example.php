<?php

require_once __DIR__ . '/../vendor/autoload.php';

use e64f\money2text\Money2Text;

$money2text = new Money2Text();
$money2text->setCurrency('RUR');
$summ = 456789.45;
$text = $money2text->getText($summ);

echo $text;






