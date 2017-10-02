<?php

namespace App;
use Eloquent;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class UserOrganisation extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'userorganisations';
    protected $primaryKey = 'userorganisationid';


}
