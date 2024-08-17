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

class InboundCall extends Model
{ 
    protected   $table          = 'callcenter_inbound_calls';
    public      $timestamps     = false;
    protected   $primaryKey     = "ic_id";

     public function Agent()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_agent_id');
    }
    
    
    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','fk_customer_id');
    }
}