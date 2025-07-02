<?php

namespace App\Modules\Pet\src\Domain\Enums;

enum PetStatusEnum: string
{
    case Pending = 'pending';
    case Available = 'available';
    case Sold = 'sold';
}
