<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateString
{

    public static function nexDate($periodicity, ...$args)
    {
        $date = array_key_exists('date', $args) ? $args['date'] : now();
        $tz = array_key_exists('timezone', $args) ? $args['timezone'] : "America/Caracas";


        switch ($periodicity):

            case "Daily":
                $result = Carbon::parse($date)->addDays(1);
                break;

            case "Monthly":
                $result = Carbon::parse($date)->addMonths(1);
                break;

            case "Quarterly":
                $result = Carbon::parse($date)->addMonths(3);
                break;

            case "Anually":
                $result = Carbon::parse($date)->addYears(1);
                break;


            default:

                $result = now();
                break;

        endswitch;

        return Carbon::parse(self::format(self::timezone($result, $tz)))->toDateString();
    }

    public static function format($date = null){
        return self::timezone(Carbon::parse($date)->format("d-m-Y"));
    }

    public static function timezone($date = null, $tz = "America/caracas"){
        return Carbon::parse($date)->setTimezone($tz);
    }

    public static function now(){
        return self::format(now())->toDateString();
    }
}
