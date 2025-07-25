<?php

namespace App\Controllers;

class Categoria extends BaseController
{
    public function index(): string
    {
        return view('categorias/index');
    }
}
