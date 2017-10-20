<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Event extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'events';
    protected $primaryKey = 'eventid';

    public function getStatusColourAttribute()
    {

        switch ($this->status) {
            case 'open' :
                return 'limegreen';

            case 'closed' :
                return 'grey';

            case 'entriesclosed' :
            case 'waitlist' :
            case 'pending' :
                return 'orange';

            case 'completed' :
            case 'cancelled' :
                return 'red';
        }

    }
}
