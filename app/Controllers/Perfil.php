<?php

namespace App\Controllers;

class Perfil extends BaseController
{
    public function index(): string
    {
        return view('perfis/index');
    }
}
