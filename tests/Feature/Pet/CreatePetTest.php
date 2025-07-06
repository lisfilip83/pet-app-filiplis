<?php

namespace Pet;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        CategoryEloquent::factory()->count(4)->create();
        TagEloquent::factory()->count(6)->create();
    }

    public function test_it_should_properly_create_pet(): void
    {
        // Arrange
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $petData = [
            'name' => 'Test Pet',
            'status' => PetStatusEnum::Available->value,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'photo_urls' => 'https://example.com/image1.jpg,https://example.com/image2.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('pets', [
            'name' => 'Test Pet',
            'status' => PetStatusEnum::Available->value,
            'category_id' => $category->id,
        ]);
    }

    public function test_it_should_return_validation_error_when_name_is_missing(): void
    {
        // Arrange
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $petData = [
            'status' => PetStatusEnum::Available->value,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'photo_urls' => 'https://example.com/image1.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_it_should_return_validation_error_when_status_is_invalid(): void
    {
        // Arrange
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $petData = [
            'name' => 'Test Pet',
            'status' => 'invalid_status',
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'photo_urls' => 'https://example.com/image1.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    public function test_it_should_return_validation_error_when_category_is_missing(): void
    {
        // Arrange
        $tag = TagEloquent::first();

        $petData = [
            'name' => 'Test Pet',
            'status' => PetStatusEnum::Available->value,
            'photo_urls' => 'https://example.com/image1.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category']);
    }

    public function test_it_should_return_validation_error_when_pet_name_already_exists(): void
    {
        // Arrange
        $existingPet = PetEloquentFactory::new()->create();
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $petData = [
            'name' => $existingPet->name,
            'status' => PetStatusEnum::Available->value,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'photo_urls' => 'https://example.com/image1.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonFragment(['name' => ['Pet with this name already exists.']]);
    }

    public function test_it_should_return_validation_error_when_category_id_does_not_exist(): void
    {
        // Arrange
        $tag = TagEloquent::first();

        $petData = [
            'name' => 'Test Pet',
            'status' => PetStatusEnum::Available->value,
            'category' => [
                'id' => 9999, // Non-existent ID
                'name' => 'Non-existent Category',
            ],
            'photo_urls' => 'https://example.com/image1.jpg',
            'tags' => [
                [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]
            ],
        ];

        // Act
        $response = $this->postJson('/api/pet/', $petData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category.id']);
    }
}
