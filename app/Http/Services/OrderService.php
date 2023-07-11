<?php

namespace App\Http\Services;

use App\Models\OrderHistory;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderService
{
    public function makeOrder($data){
        try {
            $validatior = Validator::make($data,[
                'total_amount' => 'required|int'
            ]);
            if($validatior->fails()){
                throw new \Exception($validatior->errors()->first());
            }
            $order = Orders::create([
                'customer_id' => Auth::user()->id,
                'total_amount' => $data['total_amount'],
                'order_date' => Carbon::now(),
                'status' => 'confirmed',
            ]);
            foreach ($data['items'] as $item) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'restaurant_id' => $item['restaurant_id'],
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            $this->trainDataSet();
            return [
                'status' => true,
                'message' => 'updated successfully'
            ];
        }catch (\Exception $exception){
            Log::info($exception);
            return [
                'status' => false,
                'message' => 'failed to create order'
            ];
        }
    }

    public function trainDataSet(){
        $py_path = base_path('python_scripts/environment/bin/python');
        $scriptPath = base_path('python_scripts/main.py');
        $command = $py_path . ' ' . $scriptPath;

        $output = [];
        $returnCode = 0;

        exec($command, $output, $returnCode);
    }
}
