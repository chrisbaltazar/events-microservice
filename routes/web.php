<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ApiController::class)->prefix('api')->group(function () {
    Route::get('/search', 'search');
});
