<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Club;

class Home extends Controller
{


    public function index()
    {
    	$clubs = Club::all();

    	return view ('includes.welcome');
    }
}
