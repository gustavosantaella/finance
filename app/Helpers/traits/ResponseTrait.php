<?php

namespace App\Helpers\Traits;

use App\Helpers\Log;
use Exception;

trait ResponseTrait
{
    protected function response(mixed $data, int $status = 200, array $headers = [], bool $error = false)
    {
        define('errorText', 'error');
        $aux_data = [
            "ok" => 'OK',
            "status" => $status,
            "data" => $data,
        ];

        if ($data instanceof Exception) {
            $aux_data['ok'] = errorText;
            $aux_data['status'] = in_array($status, config()->get('definitions.http.statusCodes.error')) ?  $status : 400;
            $aux_data['message'] =  $data->getMessage();
            unset($aux_data['data']);
        }

        if ($error) {
            unset($aux_data['data']);
            $aux_data['ok'] = errorText;
            $aux_data['message'] =  $data;
        }

        $response = response($aux_data, $aux_data['status'], $headers);
        return $response;
    }
}
