<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Log as FacadesLog;

class Log{
    public static function write(mixed $message, String $method = 'info' ): void {
        FacadesLog::channel('stderr')->$method($message);
    }
}
