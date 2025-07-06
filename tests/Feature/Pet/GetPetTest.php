<?php

namespace Pet;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetPetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create categories and tags for testing
        CategoryEloquent::factory()->count(4)->create();
        TagEloquent::factory()->count(6)->create();
    }

    public function test_it_should_properly_retrieve_existing_pet(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();

        // Act
        $response = $this->getJson("/api/pet/{$pet->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'status',
            'photo_urls',
            'category',
            'tags'
        ]);
        $response->assertJsonFragment([
            'id' => $pet->id,
            'name' => $pet->name,
            'status' => $pet->status->value
        ]);
    }

    public function test_it_should_return_404_when_pet_not_found(): void
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $response = $this->getJson("/api/pet/{$nonExistentId}");

        // Assert
        $response->assertStatus(404);
    }

    public function test_it_should_return_error_when_invalid_id_provided(): void
    {
        // Arrange
        $invalidId = 'invalid-id';

        // Act
        $response = $this->getJson("/api/pet/{$invalidId}");

        // Assert
        $response->assertStatus(500);
        $this->assertEquals(
            expected: "TypeError",
            actual: json_decode($response->getContent())->exception
        );
    }

    public function test_it_should_include_pet_relationships_in_response(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $category = CategoryEloquent::find($pet->category_id);

        // Act
        $response = $this->getJson("/api/pet/{$pet->id}");

        // Assert
        $response->assertStatus(200);

        $response->assertJsonPath('category.id', $category->id);
        $response->assertJsonPath('category.name', $category->name);

        $response->assertJsonPath('tags', fn(array $tags) =>
            array_all(
                array: $tags,
                callback: fn (array $tag) => in_array(
                    needle: $tag['id'],
                    haystack: $pet->tags->pluck('id')->toArray()
                )
            )
        );
    }

    public function test_it_should_return_pet_with_correct_status(): void
    {
        // Arrange
        $availablePet = PetEloquentFactory::new()->available()->create();
        $pendingPet = PetEloquentFactory::new()->pending()->create();
        $soldPet = PetEloquentFactory::new()->sold()->create();

        // Act & Assert for Available pet
        $response = $this->getJson("/api/pet/{$availablePet->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('status', PetStatusEnum::Available->value);

        // Act & Assert for Pending pet
        $response = $this->getJson("/api/pet/{$pendingPet->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('status', PetStatusEnum::Pending->value);

        // Act & Assert for Sold pet
        $response = $this->getJson("/api/pet/{$soldPet->id}");
        $response->assertStatus(200);
        $response->assertJsonPath('status', PetStatusEnum::Sold->value);
    }
}
