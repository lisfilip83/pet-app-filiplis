<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPetImageRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|string'
        ];
    }

    public function getUrl(): ?string
    {
        return $this->get('url');
    }
}
