<?php

namespace App\Http\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemsService
{
    public function getItems($filter = [],$search = ''){
        $recommended = $this->getRecommended();
        $items = Item::with(['category:id,name'])
            ->leftJoin('item_user_ratings', 'items.id', '=', 'item_user_ratings.item_id')
            ->select('items.*', DB::raw('ROUND(AVG(item_user_ratings.rating), 1) as average_rating'))
            ->where($filter)
            ->when($search != '',function ($q) use ($search){
                return $q->where('name','LIKE','%'.$search.'%');
            })
            ->groupBy('items.id')
            ->orderByDesc('average_rating')
            ->get();
        $items->map(function ($i) {
           $i['image_name'] = config('app.url').'/food.webp';
           $i['title'] = $i['name'];
           $i['prepTimeValue'] = '40-60';
           return $i;
        });
        return [
            'items' => $items,
            'recommended' => json_decode($recommended)
        ];
    }

    public function getRecommended(){
        $py_path = base_path('python_scripts/environment/bin/python');
        $scriptPath = base_path('python_scripts/recommend.py '.base_path('python_scripts/model.dump').' '.Auth::id());
        $command = $py_path . ' ' . $scriptPath;

        $output = [];
        $returnCode = 0;
        Log::info($command);
        exec($command, $output, $returnCode);
        Log::info(json_encode($output));
        Log::info($this->processPythonOutput($output));
        if ($returnCode === 0) {
            return $this->processPythonOutput($output);
        } else {
            return '[]';
        }
    }

    private function processPythonOutput($output)
    {
        return implode(", ", $output);
    }
}
