<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Requests;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class GetPetsByStatusesRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statuses' => 'required|string',
        ];
    }

    public function getStatuses(): array
    {
        $statuses = $this->get('statuses') ?? '';
        return explode(',', $statuses);
    }
}
