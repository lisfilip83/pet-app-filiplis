<?php

use App\Modules\Pet\src\Infrastructure\Http\Controllers\DeletePetController;
use App\Modules\Pet\src\Infrastructure\Http\Controllers\GetPetController;
use App\Modules\Pet\src\Infrastructure\Http\Controllers\GetPetsByStatusesController;
use App\Modules\Pet\src\Infrastructure\Http\Controllers\StorePetController;
use App\Modules\Pet\src\Infrastructure\Http\Controllers\UpdatePetController;
use App\Modules\Pet\src\Infrastructure\Http\Controllers\UploadPetImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/pet')->group(function () {
    Route::get('/find-by-status', GetPetsByStatusesController::class);
    Route::get('/{id}', GetPetController::class);
    Route::post('/', StorePetController::class);
    Route::put('/', UpdatePetController::class);
    Route::delete('/{id}', DeletePetController::class);
    Route::post('/{id}/upload-image', UploadPetImageController::class);
});
