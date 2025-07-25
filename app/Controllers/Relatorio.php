<?php

namespace App\Controllers;

class Relatorio extends BaseController
{
    public function index(): string
    {
        return view('relatorio/index');
    }
}
