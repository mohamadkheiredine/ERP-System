<?php

namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    protected   $table          = 'usr_teams';
    public      $timestamps     = false;
    protected   $primaryKey     = "ut_id";
    
    
    public function TeamMembers()
    {
        return $this->hasMany('App\models\Users\TeamMembers', 'fk_team_id','ut_id');
    }
}
