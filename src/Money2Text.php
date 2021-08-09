<?php

namespace e64f\money2text;
use \Exception as Exception;

class Money2Text
{
    var $Amount = 0.00;
    var $MaxAmount = 1000000000000;
    var $arCurrencies = array('RUR', 'USD', 'EUR', 'KZT', 'UAH');
    var $Currency = 'RUR';
    var $arCurrencyMorph = array(1 => 'рубль ', 2 => 'рубля ', 3 => 'рублей ');
    var $CurrencyGender = 'male';
    var $arCoinsMorph = array(1 => 'копейка ', 2 => 'копейки ', 3 => 'копеек ');
    var $CoinGender = 'female';
    var $text = '';

    /**
     * @param string $Currency ('RUR', 'USD', 'EUR', 'KZT', 'UAH')
     */
    public function setCurrency($Currency)
    {
        try {
            if (!in_array($Currency, $this->arCurrencies)) {
                throw new Exception('Your currency code (' . $Currency . ') is BAD. You must use, this one: '. implode(', ', $this->arCurrencies) . "!");
            } else {
                $this->Currency = $Currency;
                switch ($Currency) {
                    case 'RUR':
                        $this->arCurrencyMorph = array(1 => 'рубль ', 2 => 'рубля ', 3 => 'рублей ');
                        $this->CurrencyGender = 'male';
                        $this->arCoinsMorph = array(1 => 'копейка ', 2 => 'копейки ', 3 => 'копеек ');
                        $this->CoinGender = 'female';
                        break;
                    case 'USD':
                        $this->arCurrencyMorph = array(1 => 'доллар ', 2 => 'доллара ', 3 => 'долларов ');
                        $this->CurrencyGender = 'male';
                        $this->arCoinsMorph = array(1 => 'цент ', 2 => 'цента ', 3 => 'центов ');
                        $this->CoinGender = 'male';
                        break;
                    case 'EUR':
                        $this->arCurrencyMorph = array(1 => 'евро ', 2 => 'евро ', 3 => 'евро ');
                        $this->CurrencyGender = 'male';
                        $this->arCoinsMorph = array(1 => 'евроцент ', 2 => 'евроцента ', 3 => 'евроцентов ');
                        $this->CoinGender = 'male';
                        break;
                    case 'KZT':
                        $this->arCurrencyMorph = array(1 => 'тенге ', 2 => 'тенге ', 3 => 'тенге ');
                        $this->CurrencyGender = 'male';
                        $this->arCoinsMorph = array(1 => 'тиын ', 2 => 'тиын ', 3 => 'тиын ');
                        $this->CoinGender = 'male';
                        break;
                    case 'UAH':
                        $this->arCurrencyMorph = array(1 => 'гривна ', 2 => 'гривны ', 3 => 'гривен ');
                        $this->CurrencyGender = 'female';
                        $this->arCoinsMorph = array(1 => 'копейка ', 2 => 'копейки ', 3 => 'копеек ');
                        $this->CoinGender = 'female';
                        break;
                    default:
                        throw new Exception('Your currency code (' . $Currency . ') is BAD. You must create code - CASE section!');
                        break;

                }

            }
        } catch (Exception $e) {
            echo "Error message: {$e->getMessage()}<br>            
            File: {$e->getFile()} Line: {$e->getLine()}";
            die();
        }

    }

    /**
     * @return string
     */
    public function getText($Amount)
    {
        try {
            if (!settype($Amount, "double")) {
                throw new Exception('Your param Amount - Must be numeric!');
            }
            if ($Amount < 0) {
                throw new Exception('Your param Amount - Must be positive!');
            }
            if ($Amount > $this->MaxAmount) {
                throw new Exception('Your param Amount ('. $Amount .') - The param should be less then ' . $this->MaxAmount . '!');
            }

            $this->Amount = round($Amount, 2, PHP_ROUND_HALF_UP);


            $this->text = '';

            #$rouded = strval(floor($this->Amount));
            #$coins = substr(strval(floor($this->Amount * 100)), -2);
            $rouded = number_format($this->Amount, 0, '.', '');
            $coins = number_format(($this->Amount - $rouded) * 100, 0, '.', '');

            if (strlen($rouded) % 3 == 1) {
                $rouded = "00" . $rouded;
            }
            if (strlen($rouded) % 3 == 2) {
                $rouded = "0" . $rouded;
            }

            if (strlen($coins) == 0) {
                $coins = "00";
            }
            if (strlen($coins) == 1) {
                $coins = "0" . $coins;
            }

            $arTriades = str_split($rouded, 3);
            foreach ($arTriades as $item => $value) {
                $this->createText($item, $value, count($arTriades));
            }


            $this->createCoins($coins);

            $fc = mb_strtoupper(mb_substr($this->text, 0, 1));
            return $fc.mb_substr($this->text, 1);
        }  catch (Exception $e) {
            echo "Error message: {$e->getMessage()}<br>            
            File: <b>{$e->getFile()}</b> Line: <b>{$e->getLine()}</b>";
            die();
        }
    }



    public function createCoins($coins) {
        switch ($coins[0]) {
            case '0':
                if ($coins[1] == '0') $str = "ноль " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '1':
                if ($coins[1] == '0') $str = "десять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1') $str = "одинадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '2') $str = "двенадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '3') $str = "тринадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '4') $str = "четырнадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '5') $str = "пятнадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "шестнадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "семнадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "восемнадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "девятнадцать " . $this->arCoinsMorph[3] . " ";
                break;
            case '2':
                if ($coins[1] == '0') $str = "двадцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "двадцать одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "двадцать один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "двадцать две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "двадцать два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "двадцать три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "двадцать четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "двадцать пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "двадцать шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "двадцать семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "двадцать восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "двадцать девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '3':
                if ($coins[1] == '0') $str = "тридцать " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "тридцать одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "тридцать один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "тридцать две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "тридцать два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "тридцать три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "тридцать четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "тридцать пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "тридцать шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "тридцать семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "тридцать восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "тридцать девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '4':
                if ($coins[1] == '0') $str = "сорок " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "сорок одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "сорок один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "сорок две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "сорок два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "сорок три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "сорок четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "сорок пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "сорок шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "сорок семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "сорок восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "сорок девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '5':
                if ($coins[1] == '0') $str = "пятьдесят " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "пятьдесят одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "пятьдесят один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "пятьдесят две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "пятьдесят два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "пятьдесят три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "пятьдесят четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "пятьдесят пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "пятьдесят шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "пятьдесят семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "пятьдесят восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "пятьдесят девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '6':
                if ($coins[1] == '0') $str = "шестьдесят " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "шестьдесят одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "шестьдесят один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "шестьдесят две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "шестьдесят два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "шестьдесят три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "шестьдесят четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "шестьдесят пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "шестьдесят шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "шестьдесят семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "шестьдесят восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "шестьдесят девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '7':
                if ($coins[1] == '0') $str = "семьдесят " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "семьдесят одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "семьдесят один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "семьдесят две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "семьдесят два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "семьдесят три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "семьдесят четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "семьдесят пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "семьдесят шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "семьдесят семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "семьдесят восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "семьдесят девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '8':
                if ($coins[1] == '0') $str = "восемьдесят " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "восемьдесят одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "восемьдесят один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "восемьдесят две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "восемьдесят два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "восемьдесят три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "восемьдесят четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "восемьдесят пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "восемьдесят шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "восемьдесят семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "восемьдесят восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "восемьдесят девять " . $this->arCoinsMorph[3] . " ";
                break;
            case '9':
                if ($coins[1] == '0') $str = "девяноста " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'female') $str = "девяноста одна " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '1' and $this->CoinGender == 'male') $str = "девяноста один " . $this->arCoinsMorph[1] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'female') $str = "девяноста две " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '2' and $this->CoinGender == 'male') $str = "девяноста два " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '3') $str = "девяноста три " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '4') $str = "девяноста четыре " . $this->arCoinsMorph[2] . " ";
                if ($coins[1] == '5') $str = "девяноста пять " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '6') $str = "девяноста шесть " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '7') $str = "девяноста семь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '8') $str = "девяноста восемь " . $this->arCoinsMorph[3] . " ";
                if ($coins[1] == '9') $str = "девяноста девять " . $this->arCoinsMorph[3] . " ";
                break;
        }

        $this->text .= $str;

    }

    public function createText($index, $triade, $cnt) {
        $str = '';
        switch ($triade[0]) {
            case '0':
                break;
            case '1':
                $str .= 'сто ';
                break;
            case '2':
                $str .= 'двести ';
                break;
            case '3':
                $str .= 'триста ';
                break;
            case '4':
                $str .= 'четыреста ';
                break;
            case '5':
                $str .= 'пятсот ';
                break;
            case '6':
                $str .= 'шестьсот ';
                break;
            case '7':
                $str .= 'семьсот ';
                break;
            case '8':
                $str .= 'восемсот ';
                break;
            case '9':
                $str .= 'девятсот ';
                break;
            default:
                //TODO вывести ошибку
                $str  = "ERROR";
        }

        if ($triade[0] != '0' and $triade[1] == '0' and $triade[2] == 0) {
            $str .= $this->getTriadeDescription($cnt-$index-1, 3);
        }

        switch ($triade[1]) {
            case '0':
                if ($triade[2] == '0') ;
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '1':
                if ($triade[2] == '0') $str .= "десять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1') $str .= "одиннадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '2') $str .= "двенадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '3') $str .= "тринадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '4') $str .= "четырнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '5') $str .= "пятнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "шестнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "семнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "восемнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "девятнадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '2':
                if ($triade[2] == '0') $str .= "двадцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "двадцать один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "двадцать одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "двадцать два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "двадцать две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "двадцать три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "двадцать четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "двадцать пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "двадцать шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "двадцать семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "двадцать восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "двадцать девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '3':
                if ($triade[2] == '0') $str .= "тридцать " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "тридцать один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "тридцать одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "тридцать два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "тридцать две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "тридцать три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "тридцать четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "тридцать пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "тридцать шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "тридцать семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "тридцать восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "тридцать девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '4':
                if ($triade[2] == '0') $str .= "сорок " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "сорок один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "сорок одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "сорок два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "сорок две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "сорок три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "сорок четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "сорок пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "сорок шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "сорок семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "сорок восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "сорок девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '5':
                if ($triade[2] == '0') $str .= "пятьдесят " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1'  and $this->CurrencyGender == 'male') $str .= "пятьдесят один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1'  and $this->CurrencyGender == 'female') $str .= "пятьдесят одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2'  and $this->CurrencyGender == 'male') $str .= "пятьдесят два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2'  and $this->CurrencyGender == 'female') $str .= "пятьдесят две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "пятьдесят три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "пятьдесят четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "пятьдесят пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "пятьдесят шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "пятьдесят семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "пятьдесят восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "пятьдесят девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '6':
                if ($triade[2] == '0') $str .= "шестьдесят " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "шестьдесят один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "шестьдесят одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "шестьдесят два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "шестьдесят две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "шестьдесят три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "шестьдесят четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "шестьдесят пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "шестьдесят шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "шестьдесят семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "шестьдесят восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "шестьдесят девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '7':
                if ($triade[2] == '0') $str .= "семьдесят " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "семьдесят один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "семьдесят одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "семьдесят два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "семьдесят две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "семьдесят три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "семьдесят четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "семьдесят пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "семьдесят шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "семьдесят семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "семьдесят восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "семьдесят девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '8':
                if ($triade[2] == '0') $str .= "восемьдесят " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "восемьдесят один " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "восемьдесят одна " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "восемьдесят два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "восемьдесят две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "восемьдесят три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "восемьдесят четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "восемьдесят пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "восемьдесят шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "восемьдесят семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "восемьдесят восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "восемьдесят девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            case '9':
                if ($triade[2] == '0') $str .= "девяноста " . $this->getTriadeDescription($cnt-$index-1, 1);
                if ($triade[2] == '1' and $this->CurrencyGender == 'male') $str .= "девяноста один " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '1' and $this->CurrencyGender == 'female') $str .= "девяноста одна " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'male') $str .= "девяноста два " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '2' and $this->CurrencyGender == 'female') $str .= "девяноста две " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '3') $str .= "девяноста три " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '4') $str .= "девяноста четыре " . $this->getTriadeDescription($cnt-$index-1, 2);
                if ($triade[2] == '5') $str .= "девяноста пять " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '6') $str .= "девяноста шесть " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '7') $str .= "девяноста семь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '8') $str .= "девяноста восемь " . $this->getTriadeDescription($cnt-$index-1, 3);
                if ($triade[2] == '9') $str .= "девяноста девять " . $this->getTriadeDescription($cnt-$index-1, 3);
                break;
            default:
                $str  = "ERROR";
        }

        if ($cnt == 1 and $triade == '000')  $str .= "ноль " . $this->getTriadeDescription(0, 3);
        $this->text .= $str;
    }

    private function getTriadeDescription($stage, $morph) {
        switch ($stage) {
            case '3':  //Миллиарды
                if ($morph == 3) $result = 'миллиардов ';
                if ($morph == 2) $result = 'миллиарда ';
                if ($morph == 1) $result = 'миллиард ';
                break;
            case '2':  //Миллионы
                if ($morph == 3) $result = 'миллионов ';
                if ($morph == 2) $result = 'миллиона ';
                if ($morph == 1) $result = 'миллион ';
                break;
            case '1':  //Тысячи
                if ($morph == 3) $result = 'тысяч ';
                if ($morph == 2) $result = 'тысячи ';
                if ($morph == 1) $result = 'тысяча ';
                break;
            case '0':  //Валюта
                if ($morph == 3) $result = $this->arCurrencyMorph[3];
                if ($morph == 2) $result = $this->arCurrencyMorph[2];
                if ($morph == 1) $result = $this->arCurrencyMorph[1];
                break;

        }

        return $result;
    }
}