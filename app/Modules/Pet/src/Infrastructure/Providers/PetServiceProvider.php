<?php

namespace App\Modules\Pet\src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;
use App\Modules\Pet\src\Infrastructure\Persistence\Repositories\PetRepositoryEloquent;

class PetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PetRepository::class, PetRepositoryEloquent::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../../routes/api.php');
    }
}
