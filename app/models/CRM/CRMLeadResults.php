<?php
/***********************************************************
CRMLeadNotes.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 28, 2019
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

class CRMLeadResults extends Model
{ 
    protected   $table          = 'crm_lead_results';
    public      $timestamps     = false;
    protected   $primaryKey     = "lr_id";
    
    
    public function Salesman()
    {
        return $this->hasOne('App\models\Users\Users', 'id','lr_sales_id');
    }
    
    public function Telemarketing()
    {
        return $this->hasOne('App\models\Users\Users', 'id','lr_telemarketing_id');
    }
    
    
        public function AppResult()
    {
        return $this->hasOne('App\models\CallCenter\ApptResults', 'ar_id','lr_text_result');
    }
    
    
}