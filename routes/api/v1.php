<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\{
    LoginController,
    RegisterController,
    LogoutController
};

Route::prefix('auth')->group(function (){
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);

    Route::group(['middleware' => 'auth:sanctum'], static function () {
        Route::post('logout', LogoutController::class);
    });
});
