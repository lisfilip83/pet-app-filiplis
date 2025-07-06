<?php

namespace App\Modules\Pet\src\Domain\Enums;

enum PetStatusEnum: string
{
    case Pending = 'pending';
    case Available = 'available';
    case Sold = 'sold';

    public static function values(): array
    {
        return array_map(
            callback: fn (PetStatusEnum $case) => $case->value,
            array: self::cases()
        );
    }

    public static function valuesAsString(): string
    {
        return implode(',', self::values());
    }
}
