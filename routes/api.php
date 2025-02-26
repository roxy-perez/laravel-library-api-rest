<?php

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(ThrottleRequests::with(10, 1)) // Limite de 10 peticiones por minuto
    ->group(function () {
        // Agregamos todas las rutas de la API V1 aquí
        include __DIR__ . '/api/v1.php';
    });
