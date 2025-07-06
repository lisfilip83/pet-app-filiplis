<?php

use App\Http\Controllers\PetsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pets', [PetsController::class, 'index']);
Route::get('/pets/details/new', [PetsController::class, 'create']);
Route::get('/pets/details/{id}', [PetsController::class, 'edit']);
