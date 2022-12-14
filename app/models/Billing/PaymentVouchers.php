<?php
/***********************************************************
PaymentVouchers.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PaymentVouchers extends Model
{
    protected   $table          = 'billing_payment_vouchers';
    public      $timestamps     = false;
    protected   $primaryKey     = "pv_id";   
    
    public function currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','pv_currency_id');
    }
}