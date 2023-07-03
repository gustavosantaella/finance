<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CreateCategoryRequest;
use App\Services\Category\CategoryService;
use Exception;
use Illuminate\Http\Request;


class CategoryController extends ApiController
{
    public function __construct(
        private CategoryService $categoryService
    ){}

    public function getAll(){
        try{
            $data  = $this->categoryService->getCategories($this->req()->get('lang'));

            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }

    public function create(CreateCategoryRequest $payload){
        try{
            Log::write($payload);
            $data  = $this->categoryService->create($payload->all());

            return $this->response($data);
        }catch(Exception $e){
            return $this->response($e);
        }
    }
}
