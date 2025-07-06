<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\CategoryEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\PetEloquent;
use App\Modules\Pet\src\Infrastructure\Persistence\Models\TagEloquent;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TagEloquent::factory()->count(6)->create();
        CategoryEloquent::factory()->count(4)->create();
        PetEloquent::factory()->count(4)->available()->create();
        PetEloquent::factory()->count(3)->pending()->create();
        PetEloquent::factory()->count(3)->sold()->create();
    }
}
