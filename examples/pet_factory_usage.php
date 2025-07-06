<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;

// Example 1: Create a single pet with random attributes
$pet = PetEloquent::factory()->create();
echo "Created pet with name: {$pet->name}, status: {$pet->status->value}\n";
echo "Number of tags: " . $pet->tags->count() . "\n";

// Example 2: Create multiple pets
$pets = PetEloquent::factory()->count(3)->create();
echo "Created " . $pets->count() . " pets\n";

// Example 3: Create a pet with a specific status
$availablePet = PetEloquent::factory()->available()->create();
echo "Created available pet: " . ($availablePet->status === PetStatusEnum::Available ? 'Yes' : 'No') . "\n";

$pendingPet = PetEloquent::factory()->pending()->create();
echo "Created pending pet: " . ($pendingPet->status === PetStatusEnum::Pending ? 'Yes' : 'No') . "\n";

$soldPet = PetEloquent::factory()->sold()->create();
echo "Created sold pet: " . ($soldPet->status === PetStatusEnum::Sold ? 'Yes' : 'No') . "\n";

// Example 4: Create a pet with specific attributes
$customPet = PetEloquent::factory()->create([
    'name' => 'Fluffy',
    'photo_urls' => ['https://example.com/fluffy.jpg'],
]);
echo "Created custom pet with name: {$customPet->name}\n";

// Example 5: Create a pet but don't persist it to the database
$unpersisted = PetEloquent::factory()->make();
echo "Created unpersisted pet with name: {$unpersisted->name}\n";

// Example 6: Create a pet and get raw attributes
$attributes = PetEloquent::factory()->raw();
echo "Generated pet attributes: " . json_encode($attributes) . "\n";
