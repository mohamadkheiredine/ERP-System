<?php
/***********************************************************
Appointments.php
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

class Appointments extends Model
{ 
    protected   $table          = 'callcenter_lead_appointments';
    public      $timestamps     = false;
    protected   $primaryKey     = "ca_id";


    public function Lead()
    {
        return $this->hasOne('App\models\CRM\CRMLeads', 'cl_id','ca_lead_id');
    }
    
    
    public function Salesman()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ca_salesman_id');
    }
    
    public function Telemarketing()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ca_telemarketing_id');
    }
    
    public function AppResult()
    {
        return $this->hasOne('App\models\CallCenter\ApptResults', 'ar_id','ca_apt_result');
    }
}