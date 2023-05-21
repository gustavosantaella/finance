<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Services\CountryService;
use Exception;
use Illuminate\Http\Request;


class CountryController extends ApiController
{
    public function __construct(
        private CountryService $countryService
    ){}
   //  Methods

   public function getAll(){
    try{
        return $this->response($this->countryService->getAll());
    }catch(Exception $e){
        return $this->response($e);
    }
   }
}
