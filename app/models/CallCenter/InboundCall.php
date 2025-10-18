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

    public function Salesman()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ic_sales_id');
    }

    public function MaintenanceType()
    {
        return $this->hasOne('App\models\CallCenter\MaintenanceTypes', 'mt_id','ic_maintenance_type');
    }

    public function Telemarketing()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ic_telemarketing_id');
    }    public function Technician()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ic_technician_id');
    }

    public function Client()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','fk_customer_id');
    }

     public function CallResult()
    {
        return $this->hasOne('App\models\CallCenter\CallResults', 'cr_id','ic_result_id');
    }


}
