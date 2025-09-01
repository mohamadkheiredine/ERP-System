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

class Project extends Model
{
    protected   $table          = 'pm_projects';
    public      $timestamps     = false;
    protected   $primaryKey     = "pp_id";


    public function Status()
    {
        return $this->hasOne('App\models\PMP\ProjectStatus', 'ps_id','pp_status_id');
    }


    public function Type()
    {
        return $this->hasOne('App\models\PMP\ProjectTypes', 'pt_id','fk_project_type_id');
    }


    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cc_id','fk_company_id');
    }
}
