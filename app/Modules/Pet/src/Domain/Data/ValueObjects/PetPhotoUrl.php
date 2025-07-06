<?php

namespace App\Modules\Pet\src\Domain\Data\ValueObjects;

final readonly class PetPhotoUrl
{
    private function __construct(
       private string $url,
    ) {
    }

    public static function create(string $url): self
    {
        return new self($url);
    }

    public function value(): string
    {
        return $this->url;
    }
}
