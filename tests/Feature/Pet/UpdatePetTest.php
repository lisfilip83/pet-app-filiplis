<?php

namespace Pet;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatePetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create categories and tags for testing
        CategoryEloquent::factory()->count(4)->create();
        TagEloquent::factory()->count(6)->create();
    }

    public function test_it_should_properly_update_pet(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $newCategory = CategoryEloquent::where('id', '!=', $pet->category_id)->first();
        $newTag = TagEloquent::whereNotIn('id', $pet->tags->pluck('id')->toArray())->first();

        $updateData = [
            'id' => $pet->id,
            'name' => 'Updated Pet Name',
            'status' => PetStatusEnum::Sold->value,
            'category' => [
                'id' => $newCategory->id,
                'name' => $newCategory->name,
            ],
            'photo_urls' => 'https://example.com/updated1.jpg,https://example.com/updated2.jpg',
            'tags' => [
                [
                    'id' => $newTag->id,
                    'name' => $newTag->name,
                ]
            ],
        ];

        // Act
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Updated Pet Name',
            'status' => PetStatusEnum::Sold->value,
            'category_id' => $newCategory->id,
        ]);
    }

    public function test_it_should_return_404_when_pet_not_found(): void
    {
        // Arrange
        $nonExistentId = 99999;
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $nonExistentId,
            'name' => 'Non-existent Pet',
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['id']);
        $response->assertJsonFragment(['errors' => ['id' => ['The selected id is invalid.']]]);
    }

    public function test_it_should_return_validation_error_when_id_is_missing(): void
    {
        // Arrange
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $updateData = [
            'name' => 'Updated Pet Name',
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['id']);
    }

    public function test_it_should_return_validation_error_when_name_is_missing(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $pet->id,
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_it_should_return_validation_error_when_status_is_invalid(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $pet->id,
            'name' => 'Updated Pet Name',
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    public function test_it_should_return_validation_error_when_category_is_missing(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $pet->id,
            'name' => 'Updated Pet Name',
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category']);
    }

    public function test_it_should_return_validation_error_when_pet_name_already_exists(): void
    {
        // Arrange
        $existingPet = PetEloquentFactory::new()->create(['name' => 'Existing Pet']);
        $petToUpdate = PetEloquentFactory::new()->create(['name' => 'Pet To Update']);
        $category = CategoryEloquent::first();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $petToUpdate->id,
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $response->assertJsonFragment(['name' => ['Pet with this name already exists.']]);
    }

    public function test_it_should_return_validation_error_when_category_id_does_not_exist(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $tag = TagEloquent::first();

        $updateData = [
            'id' => $pet->id,
            'name' => 'Updated Pet Name',
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
        $response = $this->putJson('/api/pet/', $updateData);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category.id']);
    }
}
