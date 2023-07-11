<?php

namespace App\Http\Controllers;

use App\Http\Services\ItemsService;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new ItemsService();
    }

    public function getItems(Request $request){
        $result = $this->service->getItems($request->except('search'),$request->search);
        return [
            'status' => true,
            'message' => 'data fetched successfully',
            'data' => $result
        ];
    }
}
