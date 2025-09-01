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

class ProjectPhases extends Model
{
    protected   $table          = 'pm_project_phases';
    public      $timestamps     = false;
    protected   $primaryKey     = "pp_phase_id";



    public function Project()
    {
        return $this->hasOne('App\models\PMP\Project', 'pp_id','fk_project_id');
    }


    public function Department()
    {
        return $this->hasOne('App\models\System\Departments', 'sd_id','pp_department_id');
    }
}
