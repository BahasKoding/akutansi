<?php

namespace App\Helpers;

class FormatHelper
{
    public static function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function parseRupiah($rupiahString)
    {
        return (float) str_replace(['Rp ', '.', ','], '', $rupiahString);
    }

    public static function formatDate($date, $format = 'd M Y')
    {
        return date($format, strtotime($date));
    }
} 
