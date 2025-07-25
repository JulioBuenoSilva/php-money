<?php

namespace App\Controllers;

class Usuario extends BaseController
{
    public function index(): string
    {
        return view('usuario/index');
    }
}
