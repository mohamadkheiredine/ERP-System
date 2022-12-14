<?php
/***********************************************************
CRMLeadActivities.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
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

class CRMLeadActivities extends Model
{ 
    protected   $table          = 'crm_lead_activities';
    public      $timestamps     = false;
    protected   $primaryKey     = "ca_id";
    
    public function leads()
    {
        return $this->hasOne('App\Models\CRM\CRMLeads', 'cl_id','fk_lead_id');
    }
   
    public function users()
    {
        return $this->hasOne('App\Models\Users\Users', 'id','fk_owner_id');
    }
    
    public function contacts()
    {
        return $this->hasOne('App\Models\CRM\CRMContacts', 'cc_id','fk_contact_id');
    }
    
    public function activitytypes()
    {
        return $this->hasOne('App\Models\CRM\CRMActivityTypes', 'at_id','ca_activity_type');
    }
}