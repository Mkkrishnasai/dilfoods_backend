<?php

namespace App\Http\Controllers;

use App\Http\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $service;
    public function __construct()
    {
        $this->service = new OrderService();
    }

    public function makeOrder(Request $request){
        return response()->json($this->service->makeOrder($request->all()));
    }
}
