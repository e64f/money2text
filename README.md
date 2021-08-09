# money2text
Convertor Money to Text (russian).

Supports work with currencies - RUR, USD, EUR, KZT, UAH.

Supports numbers less than a trillion (1 000 000 000.00).

Installation
------------

Install with [Composer](https://getcomposer.org/);

```bash
composer require e64f/money2text
```

Example
-------



```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use e64f\money2text\Money2Text;

$money2text = new Money2Text();
$money2text->setCurrency('RUR');
$summ = 456789.45;
$text = $money2text->getText($summ);

echo $text;
```

Print
-----
```text
Четыреста пятьдесят шесть тысяч семьсот восемьдесят девять рублей сорок пять копеек
```