<?php

namespace App;

use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class OutboundEmail extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'outboundemails';
    protected $primaryKey = 'emailid';
}

