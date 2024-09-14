<?php
/***********************************************************
CRMServicesPaymentTypes.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 5, 2022
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2022

Page Description :

***********************************************************/


namespace App\models\CRM;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CRMServicesPaymentTypes extends Model
{ 
    protected   $table          = 'crm_services_payment_types';
    public      $timestamps     = false;
    protected   $primaryKey     = "st_id";
    
    
    
    public function Services()
    {
        return $this->hasOne('App\models\CRM\CRMServicesPaymentTypes', 'cs_id','st_service_id');
    }
    
    public function PaymentType()
    {
        return $this->hasOne('App\models\Billing\PaymentTypes', 'pt_id','st_payment_type_id');
    }
    
    public function IncomeAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','st_account_income_id');
    }
    
    
    public function PurchaseAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','st_account_purchase_Id');
    }
    
}