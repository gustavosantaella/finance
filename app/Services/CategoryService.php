<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Services\Service;
use Exception;

class CategoryService extends Service
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ){}


    public function getCategories(string $lang){

        try{
            $categories =  $this->categoryRepository->all($lang);
            $data = array_map(function($element){
                $dt =  [
                    ...$element,
                    'label' => $element['name'],
                    'id' => $element['_id']
                ];
                unset($dt['name']);
                unset($dt['_id']);
                return $dt;
            }, $categories->toArray());

            return $data;
        }catch(Exception $e){
            throw $e;
        }
    }
}
