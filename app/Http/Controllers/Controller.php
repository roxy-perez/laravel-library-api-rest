<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __contruct()
    {
        ray()->showQueries();
    }
}
