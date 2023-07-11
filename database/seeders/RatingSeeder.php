<?php

namespace Database\Seeders;

use App\Models\ItemUserRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = range(3, 1001);
        $itemIds = range(1, 133);
        $ratings = [];

        foreach ($userIds as $userId) {
            foreach ($itemIds as $itemId) {
                ItemUserRating::create([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'rating' => rand(1,5),
                ]);
            }
        }
    }
}
