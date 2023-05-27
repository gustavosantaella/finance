<?php

namespace App\Repositories;

use Jenssegers\Mongodb\Eloquent\Model;

class Repository {

    public function __construct(private Model $model){}
    public function deleteByPk(string $pk){
        return $this->model->where("_id", $pk)->delete();
    }
}
