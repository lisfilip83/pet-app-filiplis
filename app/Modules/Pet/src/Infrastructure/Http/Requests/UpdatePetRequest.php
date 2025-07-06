<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Requests;

use App\Modules\Pet\src\Domain\Enums\PetStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:pets,id',
            'name' => 'required|string|unique:pets,name,' . $this->get('id'),
            'status' => 'required|string|in:'.PetStatusEnum::valuesAsString(),
            'category' => 'required|array',
            'category.id' => 'required|integer|exists:categories,id',
            'category.name' => 'required|string',
            'photo_urls' => 'string',
            'tags' => 'array',
            'tags.*.id' => 'required|integer|exists:tags,id',
            'tags.*.name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Pet with this name already exists.',
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->get('id'),
            'name' => $this->get('name'),
            'status' => $this->get('status'),
            'category' => [
                'id' => $this->get('category')['id'] ?? null,
                'name' => $this->get('category')['name'] ?? null,
            ],
            'photo_urls' => explode(',', $this->get('photo_urls')),
            'tags' => $this->get('tags'),
        ];
    }
}
