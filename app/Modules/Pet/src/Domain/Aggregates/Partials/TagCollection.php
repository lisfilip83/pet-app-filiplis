<?php

namespace App\Modules\Pet\src\Domain\Aggregates\Partials;

use Illuminate\Support\Collection;

final class TagCollection extends Collection
{
    /**
     * @param Tag[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    public static function create(Tag ...$tags): self
    {
        return new self($tags);
    }

    public static function fromArray(array $data): self
    {
        $tags = array_map(fn($item) => Tag::fromArray($item), $data);
        return new self($tags);
    }
} 