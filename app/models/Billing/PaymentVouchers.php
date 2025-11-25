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
        return $this->hasOne('App\models\System\Currency', 'cc_id','pv_currency_id');
    }

     public function AccountPayable()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','pv_account_payable');
    }


      public function AccountReceivable()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','pv_account_receivable');
    }


    public function User()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pv_user_id');
    }
}
