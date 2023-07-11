<?php

namespace App\Http\Controllers;

use App\Http\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new CategoryService();
    }

    public function getCategories(Request $request){
        return $this->service->getCategories($request->all());
    }
}
