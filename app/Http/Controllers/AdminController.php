<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDivisionsView()
    {
        return view('admin.divisions');
    }

    public function getDivisionsCreateView()
    {
        return view();
    }
}
