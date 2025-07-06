<?php

namespace Database\Factories;

use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CategoryEloquent>
 */
class CategoryEloquentFactory extends Factory
{
    protected $model = CategoryEloquent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
