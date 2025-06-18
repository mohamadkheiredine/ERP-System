<?php
/***********************************************************
CRMLeads.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\CRM;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CRMLeads extends Model
{
    protected   $table          = 'crm_leads';
    public      $timestamps     = false;
    protected   $primaryKey     = "cl_id";

    public function Salesman()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cl_sales_id');
    }

    public function Telemarketing()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cl_telemarketing_id');
    }

    public function AppResult()
    {
        return $this->hasOne('App\models\CallCenter\ApptResults', 'ar_id','cl_lead_results');
    }


    public function LastAppResult()
    {
        return $this->hasOne('App\models\CallCenter\ApptResults', 'ar_id','cl_last_result_id');
    }


    public function Status()
    {
        return $this->hasOne('App\models\CRM\CRMLeadStatus', 'ls_id','fk_lead_status_id');
    }


        public function LeadType()
    {
        return $this->hasOne('App\models\CRM\CRMLeadTypes', 'lt_id','cl_lead_type_id');
    }

}
