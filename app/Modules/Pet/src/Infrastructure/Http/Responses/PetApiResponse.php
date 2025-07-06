<?php

namespace App\Modules\Pet\src\Infrastructure\Http\Responses;

use Illuminate\Http\JsonResponse;

final  class PetApiResponse extends JsonResponse
{
    private function __construct(
        public int $code,
        public string $type,
        public string $message,
        public mixed $data = []
    ) {
        parent::__construct(
            data: $this->data,
            status: $this->code,
            options: JSON_THROW_ON_ERROR
        );
    }

    public static function success(
        string $message,
        array $data = []
    ): self {
        return new self(
            code: 200,
            type: 'success',
            message: $message,
            data: $data
        );
    }


    public static function error(
        string $message
    ): self {
        return new self(
            code: 500,
            type: 'error',
            message: $message
        );
    }

    public static function notFound(): self
    {
        return new self(
            code: 404,
            type: 'error',
            message: 'Pet not found'
        );
    }

    public static function validationException(
        string $message
    ): self {
        return new self(
            code: 422,
            type: 'error',
            message: $message
        );
    }

    public static function invalidId(): self
    {
        return new self(
            code: 400,
            type: 'error',
            message: 'Invalid id'
        );
    }
}
