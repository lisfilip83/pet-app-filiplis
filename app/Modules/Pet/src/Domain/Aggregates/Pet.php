<?php

namespace App\Modules\Pet\src\Domain\Aggregates;

use App\Modules\Pet\src\Domain\Aggregates\Partials\Category;
use App\Modules\Pet\src\Domain\Aggregates\Partials\TagCollection;
use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;

final readonly class Pet
{
    private function __construct(
        public ?int $id,
        public string $name,
        public PetStatusEnum $status,
        public Category $category,
        public TagCollection $tags,
    ) {
    }

    public static function create(
        ?int $id = null,
        string $name,
        Category $category,
        PetStatusEnum $status = PetStatusEnum::Available,
        TagCollection $tags = null
    ): self {
        return new self(
            id: $id,
            name: $name,
            status: $status,
            category: $category,
            tags: $tags ?: new TagCollection()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'],
            PetStatusEnum::from($data['status']),
            Category::fromArray($data['category']),
            TagCollection::fromArray($data['tags'] ?? [])
        );
    }
}
