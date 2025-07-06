<?php

namespace App\Modules\Pet\src\Domain\Repositories;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use App\Modules\Pet\src\Domain\Data\Collections\PetCollection;
use App\Modules\Pet\src\Domain\Data\Collections\PetStatusCollection;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetPhotoUrl;
use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;

interface PetRepository
{
    public function findById(PetId $id): ?Pet;
    public function store(Pet $pet): Pet;
    public function update(Pet $pet): ?Pet;
    public function delete(PetId $id): bool;

    /**
     * @param PetStatusCollection<int, PetStatusEnum> $statuses
     * @return PetCollection<int, Pet>
     */
    public function findByStatus(PetStatusCollection $statuses): PetCollection;
    public function addImageUrl(PetId $id, PetPhotoUrl $url): bool;
}
