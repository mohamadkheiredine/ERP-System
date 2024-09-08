<?php
/***********************************************************
MaintenanceCase.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : 17-08-2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\CallCenter;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class MaintenanceCase extends Model
{ 
    protected   $table          = 'callcenter_cases';
    public      $timestamps     = false;
    protected   $primaryKey     = "cc_id";

    public function Status()
    {
        return $this->hasOne('App\models\CallCenter\CaseStatus', 'cc_id','cc_case_status');
    }
    
    
    public function Agent()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cc_assigned_agent_id');
    }
    
    
    public function Technician()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cc_technician_id');
    }
}