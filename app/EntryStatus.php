<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class EntryStatus extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'entrystatuses';
    protected $primaryKey = 'entrystatusid';
}

