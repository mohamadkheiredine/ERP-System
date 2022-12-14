<?php
/***********************************************************
BankAccounts.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 22, 2019
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

class BankAccounts extends Model
{
    protected   $table          = 'acc_bank_accounts';
    public      $timestamps     = false;
    protected   $primaryKey     = "ba_id";
    
    
    public function currency()
    {
        return $this->hasOne('App\models\System\Currency','cc_id','ba_account_currency');
    }
}