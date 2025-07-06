<?php

namespace Pet;

use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
use Database\Factories\PetEloquentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UploadPetImageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        CategoryEloquent::factory()->count(4)->create();
        TagEloquent::factory()->count(6)->create();
    }

    public function test_it_should_properly_upload_image_to_existing_pet(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $imageUrl = 'https://example.com/new-pet-image.jpg';

        // Act
        $response = $this->postJson("/api/pet/{$pet->id}/upload-image", [
            'url' => $imageUrl
        ]);

        // Assert
        $response->assertStatus(200);

        $pet->refresh();
        $this->assertContains($imageUrl, $pet->photo_urls);
    }

    public function test_it_should_return_404_when_pet_not_found(): void
    {
        // Arrange
        $nonExistentId = 99999;
        $imageUrl = 'https://example.com/new-pet-image.jpg';

        // Act
        $response = $this->postJson("/api/pet/{$nonExistentId}/upload-image", [
            'url' => $imageUrl
        ]);

        // Assert
        $response->assertStatus(404);
    }

    public function test_it_should_return_validation_error_when_url_is_missing(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();

        // Act
        $response = $this->postJson("/api/pet/{$pet->id}/upload-image", []);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['url']);
    }

    public function test_it_should_return_error_when_invalid_id_provided(): void
    {
        // Arrange
        $invalidId = 'invalid-id';
        $imageUrl = 'https://example.com/new-pet-image.jpg';

        // Act
        $response = $this->postJson("/api/pet/{$invalidId}/upload-image", [
            'url' => $imageUrl
        ]);

        // Assert
        $response->assertStatus(500);
        $this->assertEquals(
            expected: "TypeError",
            actual: json_decode($response->getContent())->exception
        );
    }

    public function test_it_should_add_multiple_images_to_pet(): void
    {
        // Arrange
        $pet = PetEloquentFactory::new()->create();
        $initialImageCount = count($pet->photo_urls);
        $firstImageUrl = 'https://example.com/first-new-image.jpg';
        $secondImageUrl = 'https://example.com/second-new-image.jpg';

        // Act - Upload first image
        $this->postJson("/api/pet/{$pet->id}/upload-image", [
            'url' => $firstImageUrl
        ]);

        // Act - Upload second image
        $response = $this->postJson("/api/pet/{$pet->id}/upload-image", [
            'url' => $secondImageUrl
        ]);

        // Assert
        $response->assertStatus(200);

        // Refresh the pet from the database
        $pet->refresh();

        $this->assertContains($firstImageUrl, $pet->photo_urls);
        $this->assertContains($secondImageUrl, $pet->photo_urls);
        $this->assertCount($initialImageCount + 2, $pet->photo_urls);
    }
}
