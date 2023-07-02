<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends Repository{

    public function __construct(
        private Category $model
    ){
        parent::__construct($model);
    }

    public function all(string &$lang) {
        return $this->model->orderBy('name', 'asc')->where('lang', $lang)->get();
    }

    public function findByNmae(string $name){
        return $this->model->where("name", $name)->first();
    }
}
