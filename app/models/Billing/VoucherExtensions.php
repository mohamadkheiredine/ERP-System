<?php
/***********************************************************
VoucherExtensions.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 11, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/



namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class VoucherExtensions extends Model
{
    protected   $table          = 'billing_voucher_extensions';
    public      $timestamps     = false;
    protected   $primaryKey     = "ve_id";   
    
    public function currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','ve_extension_currency');
    }
    
    public function Account()
    {
        return $this->hasOne('App\Models\Accounting\ChartAccounts', 'aa_id','ve_extention_account_id');
    }
}