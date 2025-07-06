<?php

namespace App\Modules\Pet\src\Domain\Data\ValueObjects;

final readonly class PetId
{
    private function __construct(
       private int $id,
    ) {
    }

    public static function create(int $id): self
    {
        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }
}
