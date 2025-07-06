<?php

namespace App\Modules\Pet\src\Application\Handlers;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;

final readonly class StorePetHandler
{
    public function __construct(
        private PetRepository $repository
    ) {
    }

    public function __invoke(Pet $pet): Pet
    {
        return $this->repository->store($pet);
    }
}
