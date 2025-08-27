<?php

namespace App\Controllers;

class Lancamento extends BaseController
{
    public function __construct()
    {
        helper(['funcoes']);
    }

    public function index(): string
    {
        return view('lancamentos/index');
    }
}
