<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository
{
    public function __construct(
         private Country $model
    ){}

    public function getAll(){
        return $this->model->all()->sortBy('name')->toArray();
    }

    public function getByNameOrIso(String $name): ?Country{
        return $this->model->where('name', $name)->orWhere('iso2', $name)->orWhere('iso3', $name)->first();
    }
}
