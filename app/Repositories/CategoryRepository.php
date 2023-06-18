<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository{

    public function __construct(
        private Category $model
    ){}

    public function all(string &$lang) {
        return $this->model->orderBy('name', 'asc')->where('lang', $lang)->get();
    }
}
