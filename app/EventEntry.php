<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class EventEntry extends Eloquent implements Authenticatable
{
    use AuthenticableTrait;

    protected $table = 'evententry';
    protected $primaryKey = 'evententryid';

    public function getPaidLabel() {
        switch ($this->paid) {
            case 0 :
                return 'Not Paid';
                break;
            case 1 :
                return 'Paid';
                break;
            case 2 :
                return 'N/A';
                break;
            default :
                return '';
                break;
        }
    }
}
