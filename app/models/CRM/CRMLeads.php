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
        return $this->hasOne('App\models\Users\Users', 'id','fk_assign_to');
    }
    
    public function Owner()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_lead_owner');
    }
    
    public function referredby()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','cl_referred_by');
    }
    
    
    public function Status()
    {
        return $this->hasOne('App\models\CRM\CRMLeadStatus', 'ls_id','fk_lead_status_id');
    }
    
}