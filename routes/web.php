<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    ray('Hello World');
    return view('welcome');
});
