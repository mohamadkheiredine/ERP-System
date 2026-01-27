<?php
/***********************************************************
ProjectTypes.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Product categories model
***********************************************************/

namespace App\models\PMP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProjectJobs extends Model
{
    protected   $table          = 'pm_project_jobs';
    public      $timestamps     = false;
    protected   $primaryKey     = "pj_id";



    public function Project()
    {
        return $this->hasOne('App\models\PMP\Project', 'pp_id','fk_project_id');
    }


    public function Phase()
    {
        return $this->hasOne('App\models\PMP\ProjectPhases', 'pp_phase_id','fk_phase_id');
    }

    public function Status()
    {
        return $this->hasOne('App\models\System\SystemStatus', 'ss_id','fk_phase_id');
    }

    public function AssignTo()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pj_owner_id');
    }
}
