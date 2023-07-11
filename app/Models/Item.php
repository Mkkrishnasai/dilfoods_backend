<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function rating(){
        return $this->hasOne(ItemUserRating::class,'item_id','id')
            ->selectRaw('item_id, AVG(rating) as average_rating')
            ->groupBy('item_id');
    }
}
