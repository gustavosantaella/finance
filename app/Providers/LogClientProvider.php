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
        $method = request()->method();
        $payload = json_encode(request()->all());
        if(array_key_exists('host', $body) && count($body['host']) > 0){
            $host = $body['host'][0];
        }
        $user = request()->header('authorization');
        $message = "\n--------------------------\nClient IP: $ip\nUrl: ($method) $urlToQuery\nPayload:$payload \n user: $user \n--------------------------";
        Log::write($message);

    }
}
