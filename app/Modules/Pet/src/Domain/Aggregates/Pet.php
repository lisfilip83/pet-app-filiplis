<?php

namespace App\Modules\Pet\src\Domain\Aggregates;

use App\Modules\Pet\src\Domain\Aggregates\Partials\Category;
use App\Modules\Pet\src\Domain\Aggregates\Partials\PhotoUrlCollection;
use App\Modules\Pet\src\Domain\Aggregates\Partials\Tag;
use App\Modules\Pet\src\Domain\Aggregates\Partials\TagCollection;
use App\Modules\Pet\src\Domain\Data\ValueObjects\PetId;
use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;

final readonly class Pet
{
    /**
     * @param PetId|null $id
     * @param string $name
     * @param PetStatusEnum $status
     * @param Category $category
     * @param PhotoUrlCollection<int, string> $photoUrls
     * @param TagCollection<int, Tag> $tags
     */
    private function __construct(
        public ?PetId $id,
        public string $name,
        public PetStatusEnum $status,
        public Category $category,
        public PhotoUrlCollection $photoUrls,
        public TagCollection $tags,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? PetId::create($data['id']) : null,
            name: $data['name'],
            status: PetStatusEnum::from($data['status']),
            category: Category::fromArray($data['category']),
            photoUrls: PhotoUrlCollection::fromArray($data['photo_urls'] ?? []),
            tags: TagCollection::fromArray($data['tags'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name,
            'status' => $this->status,
            'photo_urls' => $this->photoUrls->toArray(),
            'category' => $this->category->toArray(),
            'tags' => $this->tags->toArray(),
        ];
    }

    public function createWithId(int $id): self
    {
        return new self(
            id: PetId::create($id),
            name: $this->name,
            status: $this->status,
            category: $this->category,
            photoUrls: $this->photoUrls,
            tags: $this->tags
        );
    }
}
