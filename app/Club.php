<?php

namespace App;
use Eloquent;

// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Club extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'clubs';
    protected $primaryKey = 'clubid';


}