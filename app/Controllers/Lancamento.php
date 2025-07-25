<?php

namespace App\Controllers;

class Lancamento extends BaseController
{
    public function index(): string
    {
        return view('lancamentos/index');
    }
}
