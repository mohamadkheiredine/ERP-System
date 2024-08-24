<?php
/***********************************************************
CaseStatus.php
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

class CaseStatus extends Model
{ 
    protected   $table          = 'callcenter_case_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "cc_id";

     public function Status()
    {
        return $this->hasOne('App\models\CallCenter\CaseStatus', 'cc_id','fk_parent_status');
    }
    
}