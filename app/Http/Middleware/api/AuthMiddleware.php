<?php

namespace App\Http\Middleware\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
       try{
        $token = $request->header('authorization');
        if(!$token){
            return $this->response(new Exception('Missing authorization token'), 403);
        }
        $content_array = explode(' ', $token);
        if(count($content_array) < 2){
            throw new Exception("Authorization token must be 2 elements");
        }
        $token = $content_array[count($content_array) - 1];
        JWTAuth::parseToken()->authenticate();
        $user = auth()->user();
        $hasRoles = array_intersect($roles, $user->roles);
        if(count($hasRoles) === 0){
            return $this->response(new Exception('Unauthorized'), 403);

        }
        return $next($request);
       }catch(Exception $e){
        return $this->response($e);
       }
    }
}
