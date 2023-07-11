<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function getCategories($filter = []){
        try {
            $categories = Category::where($filter)->get();
            return [
                'status' => true,
                'message' => 'data fetched successfully',
                'data' => $categories
            ];
        }catch (\Exception $exception){
            Log::info($exception);
            return [
                'status' => false,
                'message' => 'Failed to fetch',
                'data' => []
            ];
        }
    }
}
