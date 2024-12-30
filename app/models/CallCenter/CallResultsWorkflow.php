<?php
/***********************************************************
InboundCall.php
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

class CallResultsWorkflow extends Model
{ 
    protected   $table          = 'callcenter_callresults_workflow';
    public      $timestamps     = false;
    protected   $primaryKey     = "cw_id";

    
    public function AssignedTo()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cw_assigned_to');
    }
    
    
    public function Call()
    {
        return $this->hasOne('App\models\CallCenter\InboundCall', 'ic_id','cw_call_id');
    }
    
    
    public function Result()
    {
        return $this->hasOne('App\models\CallCenter\CallResults', 'cr_id','cw_result_id');
    }
  
}