<?php

namespace App\Services;

use App\Repositories\CountryRepository;
use App\Services\Service;
use Exception;

class CountryService extends Service
{
    public function __construct(
        private CountryRepository $countryRepository
    ){}

    public function getByNames(String $name): ?Array {

        return $this->countryRepository->getByNameOrIso($name)->toArray();
    }
}
