<?php

namespace App\Modules\Pet\src\Application\Handlers;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Data\Collections\PetCollection;
use App\Modules\Pet\src\Domain\Data\Collections\PetStatusCollection;
use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use App\Modules\Pet\src\Domain\Repositories\PetRepository;

final readonly class GetPetsByStatusesHandler
{
    public function __construct(
        private PetRepository $repository
    ) {
    }

    /**
     * @param PetStatusCollection<int, PetStatusEnum> $statuses
     * @return PetCollection<int, Pet>
     */
    public function __invoke(PetStatusCollection $statuses): PetCollection
    {
        return $this->repository->findByStatus($statuses);
    }
}
