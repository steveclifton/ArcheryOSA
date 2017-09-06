<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function getDivisionsView()
    {
        return view('admin.divisions.divisions');
    }

    public function getDivisionCreateView()
    {
        return view('admin.divisions.createdivision');
    }
}
