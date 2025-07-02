<?php

namespace App\Modules\Pet\src\Domain\Aggregates\Partials;

final class Tag
{
    private function __construct(
        public int $id,
        public string $name
    ) {}

    public static function create(int $id, string $name): self
    {
        return new self($id, $name);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['id'], $data['name']);
    }
} 