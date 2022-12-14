<?php
/***********************************************************
BankEntries.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 18, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Accounting;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BankEntries extends Model
{
    protected   $table          = 'acc_bank_entries';
    public      $timestamps     = false;
    protected   $primaryKey     = "be_id";
    
    
    public function bankaccounts()
    {
        return $this->hasOne('App\models\Accounting\BankAccounts','ba_id','be_bank_id');
    }
    
    public function currency()
    {
        return $this->hasOne('App\models\System\Currency','cc_id','be_currency_id');
    }
}