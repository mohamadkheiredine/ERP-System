<?php

namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    protected   $table          = 'usr_team_members';
    public      $timestamps     = false;
    
    public function Users()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_user_id');
    }
    
    public function Team()
    {
        return $this->hasOne('App\models\Users\UserTeam', 'ut_id','fk_team_id');
    }
}
