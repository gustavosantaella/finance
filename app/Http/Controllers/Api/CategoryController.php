<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Log;
use App\Http\Controllers\Api\ApiController;
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
}
