<?php

namespace Pet;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetPetsByStatusesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        CategoryEloquent::factory()->count(4)->create();
        TagEloquent::factory()->count(6)->create();
    }

    public function test_it_should_properly_retrieve_pets_by_single_status(): void
    {
        // Arrange
        $availablePet1 = PetEloquentFactory::new()->available()->create();
        $availablePet2 = PetEloquentFactory::new()->available()->create();

        // Act
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . PetStatusEnum::Available->value);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['id' => $availablePet1->id]);
        $response->assertJsonFragment(['id' => $availablePet2->id]);
    }

    public function test_it_should_properly_retrieve_pets_by_multiple_statuses(): void
    {
        // Arrange
        $availablePet = PetEloquentFactory::new()->available()->create();
        $pendingPet = PetEloquentFactory::new()->pending()->create();

        // Act
        $statuses = PetStatusEnum::Available->value . ',' . PetStatusEnum::Pending->value;
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . $statuses);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['id' => $availablePet->id]);
        $response->assertJsonFragment(['id' => $pendingPet->id]);
    }

    public function test_it_should_return_all_pets_when_all_statuses_provided(): void
    {
        // Arrange
        $availablePet = PetEloquentFactory::new()->available()->create();
        $pendingPet = PetEloquentFactory::new()->pending()->create();
        $soldPet = PetEloquentFactory::new()->sold()->create();

        // Act
        $statuses = PetStatusEnum::valuesAsString();
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . $statuses);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $availablePet->id]);
        $response->assertJsonFragment(['id' => $pendingPet->id]);
        $response->assertJsonFragment(['id' => $soldPet->id]);
    }

    public function test_it_should_return_validation_error_when_statuses_parameter_is_missing(): void
    {
        // Act
        $response = $this->getJson('/api/pet/find-by-status');

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['statuses']);
    }

    public function test_it_should_ignore_invalid_statuses_and_only_use_valid_ones(): void
    {
        // Arrange
        $availablePet = PetEloquentFactory::new()->available()->create();
        $pendingPet = PetEloquentFactory::new()->pending()->create();

        // Act
        $statuses = PetStatusEnum::Available->value . ',invalid_status';
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . $statuses);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $availablePet->id]);
    }

    public function test_it_should_return_empty_array_when_no_pets_match_criteria(): void
    {
        // Arrange
        PetEloquentFactory::new()->available()->create();
        PetEloquentFactory::new()->pending()->create();

        // Act
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . PetStatusEnum::Sold->value);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_it_should_return_empty_array_when_all_statuses_are_invalid(): void
    {
        // Arrange
        PetEloquentFactory::new()->available()->create();

        // Act
        $response = $this->getJson('/api/pet/find-by-status?statuses=invalid_status1,invalid_status2');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_it_should_include_pet_relationships_in_response(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->available()->create();
        $category = CategoryEloquent::find($pet->category_id);

        // Act
        $response = $this->getJson('/api/pet/find-by-status?statuses=' . PetStatusEnum::Available->value);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('0.category.id', $category->id);
        $response->assertJsonPath('0.category.name', $category->name);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'status',
                'category',
                'photo_urls',
                'tags'
            ]
        ]);
    }
}
