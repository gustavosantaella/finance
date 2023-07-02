<?php

namespace App\Services\Category;

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

    public function getCategory(string $categoryPk){
        try{
            $data = $this->categoryRepository->find($categoryPk);
            if(!$data){
                throw new Exception("Category not found");
            }
            return $data;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function create(array $payload){
        try{
            $name = $payload['name'];
            $category = $this->categoryRepository->findByNmae($name);
            if($category){
                throw new Exception("Category already exists");
            }
            $category = $this->categoryRepository->createData([
                "name" => $name,
                "lang" => $payload['lang'],
                "createdBy" => auth()->user()->toArray()
            ]);

            return $category;
        }catch(Exception $e){
            throw $e;
        }
    }


}
