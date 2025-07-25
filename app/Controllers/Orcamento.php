<?php

namespace App\Controllers;

class Orcamento extends BaseController
{
    public function index(): string
    {
        return view('orcamentos/index');
    }
}
