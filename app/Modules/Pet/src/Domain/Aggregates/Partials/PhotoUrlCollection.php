<?php

namespace App\Modules\Pet\src\Domain\Aggregates\Partials;

use Illuminate\Support\Collection;

final class PhotoUrlCollection extends Collection
{
    /**
     * @param string[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    public static function create(string ...$urls): self
    {
        return new self($urls);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
} 