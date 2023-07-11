<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemUserRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemUserRating>
 */
class ItemUserRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ItemUserRating::class;

    public function definition(): array
    {
        $userIdsRange = range(3, 1001);
        $itemIdsRange = range(1, 133);

        return [
            'user_id' => $this->faker->unique()->randomElement($userIdsRange),
            'item_id' => $this->faker->unique()->randomElement($itemIdsRange),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ItemUserRating $rating) {
            $rating->user()->associate(User::find($rating->user_id));
            $rating->item()->associate(Item::find($rating->item_id));
        });
    }
}
