<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
    public function index()
    {

    	$user = \Auth::user();
    	return view ('auth.profile', compact('user'));
    }

    public function updateProfile()
    {
    	dd($_POST, $_FILES);
    }
}
