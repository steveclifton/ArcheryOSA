<?php

namespace App;
use Eloquent;

// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $primaryKey = 'usertypesid';


}
