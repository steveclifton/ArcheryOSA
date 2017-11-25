<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class LeagueAverage extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'leagueaverages';

}
