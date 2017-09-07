<?php

namespace App;
use Eloquent;

// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Division extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'divisions';
    protected $primaryKey = 'divisionid';


}
