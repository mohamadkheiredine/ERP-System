<?php
/***********************************************************
CRMDeals.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 29, 2019
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

class CRMDeals extends Model
{
    protected   $table          = 'crm_account_deals';
    public      $timestamps     = false;
    protected   $primaryKey     = "ad_id";
    
    public function Account()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','fk_account_id');
    }
   
    
    public function Salesman()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_sales_id');
    }
    
    public function Telemarketing()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_telemarketing_id');
    }
    
}