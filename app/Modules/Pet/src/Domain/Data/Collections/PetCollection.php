<?php

namespace App\Modules\Pet\src\Domain\Data\Collections;

use App\Modules\Pet\src\Domain\Aggregates\Pet;
use Illuminate\Support\Collection;

class PetCollection extends Collection
{
    /**
     * @param Collection<int, mixed> $pets
     * @return self
     */
    public static function create(Collection $pets): self
    {
        return new self($pets->filter(fn (mixed $item) => $item instanceof Pet));
    }

    public function toArray(): array
    {
        return $this->map(fn (Pet $pet) => $pet->toArray())->all();
    }
}
