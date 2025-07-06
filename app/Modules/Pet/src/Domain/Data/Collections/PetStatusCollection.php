<?php

namespace App\Modules\Pet\src\Domain\Data\Collections;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use Illuminate\Support\Collection;

class PetStatusCollection extends Collection
{
    public static function create(array $statuses): self
    {
        $statuses = array_filter(
            array: $statuses,
            callback: fn(string $status) => in_array($status, PetStatusEnum::values())
        );
        $statuses = array_unique($statuses);
        $statuses = array_values($statuses);
        $statuses = array_map(
            callback: fn (string $status) => PetStatusEnum::from($status),
            array: $statuses
        );

        return new self($statuses);
    }

    public function toArrayOfValues(): array
    {
        return $this->map(fn (PetStatusEnum $item) => $item->value)->all();

    }
}
