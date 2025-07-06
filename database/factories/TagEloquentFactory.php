<?php

namespace Database\Factories;

use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends Factory<TagEloquent>
 */
class TagEloquentFactory extends Factory
{
    protected $model = TagEloquent::class;

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
