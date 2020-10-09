<?php

namespace Drupal\twig_date_filter\Twig\Extension;

use Carbon\Carbon;


/**
 * Custom twig extensions.
 */
class Extensions extends \Twig_Extension
{

    public function getFilters()
    {
        $filters = [];

        $filters[] = new \Twig_SimpleFilter('date_filter', [
            $this,
            'date_filter',
        ]);

        return $filters;
    }


    public function date_filter($date)
    {

        $months = array(
            1 => 'Січня',
            2 => 'Лютого',
            3 => 'Березня',
            4 => 'Квітня',
            5 => 'Травня',
            6 => 'Червня',
            7 => 'Липня',
            8 => 'Серпня',
            9 => 'Вересня',
            10 => 'Жовтня',
            11 => 'Листопада',
            12 => 'Грудня'
        );

        $carbon = new Carbon();

        $timestamp_to_date = Carbon::createFromTimestamp($date)->toDateTimeString();
        $month = $carbon->createFromTimestamp($date)->format('n');

        $date_filter = $carbon->createFromFormat('Y-m-d H:i:s' , $timestamp_to_date)->format('H:i' . ', ' . 'd ' . $months[($month)]);

        return $date_filter;

    }

}
