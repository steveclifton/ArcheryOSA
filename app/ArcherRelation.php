<?php

namespace App;
use Eloquent;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class ArcherRelation extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'userrelationships';



}