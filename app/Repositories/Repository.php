<?php

namespace App\Repositories;

use Jenssegers\Mongodb\Eloquent\Model;

class Repository {

    public function __construct(private Model $model){}
    public function deleteByPk(string $pk){
        return $this->model->where("_id", $pk)->delete();
    }

    public function update(string $pk, array $payload){
        return $this->model->where("_id", $pk)->update($payload);
    }

    public function find(string $pk){
        return $this->model->find($pk);
    }
}
