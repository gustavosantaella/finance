<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository
{
    public function __construct(
         private Country $model
    ){}

    public function getAll(){
        return $this->model->orderBy('name', 'asc')->get();
    }

    public function getByNameOrIso(String $name): ?Country{
        return $this->model->where('name', $name)->orWhere('iso2', $name)->orWhere('iso3', $name)->first();
    }

    public function getAllNames(){
        return $this->model->orderBy('name', 'asc')->pluck('name');

    }
}
