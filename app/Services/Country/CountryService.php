<?php

namespace App\Services\Country;

use App\Repositories\CountryRepository;
use App\Services\Service;
use Exception;

class CountryService extends Service
{
    public function __construct(
        private CountryRepository $countryRepository
    ){}

    public function getByNames(String $name): ?Array {

        return $this->countryRepository->getByNameOrIso($name)?->toArray() ?? throw new Exception('Country not found');
    }

    public function getAll(){
        try{
            return $this->countryRepository->getAllNames();
        }catch(Exception $e){
            throw $e;
        }
    }
}
