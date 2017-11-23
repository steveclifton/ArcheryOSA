<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class LeaguePoint extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'leaguepoints';
    protected $primaryKey = 'leaguepointid';
}
