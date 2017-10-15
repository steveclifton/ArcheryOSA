<?php
namespace App\Classes;

use Illuminate\Support\Facades\Auth;

class UserExtended {


    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function isAdmin()
    {
        if ($this->user->usertype == 1) {
            return true;
        }
        return false;
    }
}