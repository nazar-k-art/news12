<?php

namespace Drupal\add_currency\CustomClasses;

class GetCurrency
{

    public static function getCursData()
    {

        $ch = curl_init('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $html = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $html;

    }


    public static function getCurs($val)
    {
        $array = self::getCursData();

        foreach ($array as $key => $value) {
            if ($value['cc'] == $val) {
                $a = $value['rate'];
                $curs_format = number_format($a, 2, '.', '');
                return $curs_format;
            }

        }

    }

}