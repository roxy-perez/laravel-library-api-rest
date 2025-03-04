<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\{
    LoginController,
    RegisterController,
    LogoutController
};

use App\Http\Controllers\API\V1\Library\{AuthorController, GenreController};

Route::prefix('auth')->group(function (){
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);

    Route::group(['middleware' => 'auth:sanctum'], static function () {
        Route::post('logout', LogoutController::class);
    });
});

Route::prefix('library')->middleware('auth:sanctum')->group(function(){
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('genres', GenreController::class);
});
