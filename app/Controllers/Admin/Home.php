<?php

namespace App\Controllers\Admin;

class Home extends BaseController
{
    public function index()
    {
        echo view('admin/home/index');
    }
}