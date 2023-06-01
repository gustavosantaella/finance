<?php

namespace App\Providers;

use App\Helpers\Log;
use Illuminate\Support\ServiceProvider;

class LogClientProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //


    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $ip = request()->ip();
        $host = '';
        $body = request()?->header();
        $urlToQuery = request()->url();
        if(array_key_exists('host', $body) && count($body['host']) > 0){
            $host = $body['host'][0];
        }
        $message = "\n--------------------------\nClient IP: $ip\nUrl: $urlToQuery\n--------------------------";
        Log::write($message);

    }
}
