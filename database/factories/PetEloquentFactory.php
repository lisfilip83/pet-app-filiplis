<?php

namespace Database\Factories;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PetEloquent>
 */
class PetEloquentFactory extends Factory
{
    protected $model = PetEloquent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'status' => fake()->randomElement(PetStatusEnum::cases()),
            'category_id' => CategoryEloquent::query()->pluck('id')->random(),
            'photo_urls' => [fake()->imageUrl(), fake()->imageUrl()],
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(fn (PetEloquent $pet) =>
            $pet->tags()->attach(
                TagEloquent::query()
                    ->inRandomOrder()
                    ->limit(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            )
        );
    }

    /**
     * Indicate that the pet is available.
     *
     * @return $this
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) =>
            [
                'status' => PetStatusEnum::Available,
            ]
        );
    }

    /**
     * Indicate that the pet is pending.
     *
     * @return $this
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) =>
            [
                'status' => PetStatusEnum::Pending,
            ]
        );
    }

    /**
     * Indicate that the pet is sold.
     *
     * @return $this
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) =>
            [
                'status' => PetStatusEnum::Sold,
            ]
        );
    }
}
