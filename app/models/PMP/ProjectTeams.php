<?php
/***********************************************************
ProjectTeams.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 26, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\models\PMP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProjectTeams extends Model
{
    protected   $table          = 'pm_project_teams';
    public      $timestamps     = false;
    protected   $primaryKey     = "ptm_id";

    public function Team()
    {
        return $this->hasOne('App\models\Users\UserTeam', 'ut_id','fk_team_id');
    }


    public function Project()
    {
        return $this->hasOne('App\models\PMP\Project', 'pp_id','fk_project_id');
    }

}
