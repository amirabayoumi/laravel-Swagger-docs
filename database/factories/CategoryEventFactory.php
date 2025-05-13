<!-- factory for CategoryEvent table n:n event_id from Event table and Category_id from Category table -->


<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'category_id' => Category::factory(),
        ];
    }
}