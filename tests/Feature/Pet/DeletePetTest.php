<?php

namespace Pet;

use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePetTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_properly_delete_existing_pet(): void
    {
        // Arrange
        $model = $this->createPet();

        // Act
        $response = $this->deleteJson("/api/pet/{$model->id}");

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseMissing(
            table: 'pets',
            data: ['id' => $model->id]
        );
    }

    public function test_it_should_return_404_when_pet_not_found(): void
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $response = $this->deleteJson("/api/pet/{$nonExistentId}");

        // Assert
        $response->assertStatus(404);
    }

    public function test_it_should_return_error_when_invalid_id_provided(): void
    {
        // Arrange
        $invalidId = 'invalid-id';

        // Act
        $response = $this->deleteJson("/api/pet/{$invalidId}");

        // Assert
        $response->assertStatus(500);
        $this->assertEquals(
            expected: "TypeError",
            actual: json_decode($response->getContent())->exception
        );
    }

    private function createPet(): PetEloquent
    {
        TagEloquent::factory()->count(6)->create();
        CategoryEloquent::factory()->count(4)->create();
        return PetEloquentFactory::new()->create();
    }
}
