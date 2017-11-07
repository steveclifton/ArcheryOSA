<?php
namespace App\Classes;

use App\ArcherRelation;
use Illuminate\Support\Facades\Auth;

class UserExtended {


    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function isAdmin()
    {
        if ($this->user->usertype == 1) {
            return true;
        }
        return false;
    }

    static public function getUserRelations()
    {
        return ArcherRelation::where('userid', Auth::id())->get();
    }

    static public function getUserRelationIDs()
    {
        $archers = ArcherRelation::where('userid', Auth::id())->pluck('relationuserid')->toArray();
        $archers[] = Auth::id();

        return array_map('intval', $archers);

    }
}