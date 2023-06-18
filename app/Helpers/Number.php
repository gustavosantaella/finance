<?php

namespace App\Helpers;

class Number {

    public static function formatDecimal(int|float $number){
        return number_format($number, 2, '.', '');
    }
}
