<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;

class ApiController extends Controller {

    protected function response( $data, int $status = 200, Array $headers = []){
        $aux_data = [
            "ok" => 'OK',
            "status" => $status,
            "data" => $data,
        ];

        if ( $data instanceof Exception){
            $aux_data['ok'] = 'error';
            $aux_data['status'] = in_array($status,config()->get('definitions.http.statusCodes.error')) ?  $status : 400;
            $aux_data['message'] =  $data instanceof Exception ? $data->getMessage() : $data;
            unset($aux_data['data']);
        }

        $response = response($aux_data, $aux_data['status'], $headers);
        return $response;
    }
}
