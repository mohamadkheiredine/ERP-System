<?php
/***********************************************************
JournalVouchers.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 21, 2021
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

class JournalVouchers extends Model
{
    protected   $table          = 'billing_journal_vouchers';
    public      $timestamps     = false;
    protected   $primaryKey     = "pj_id";   
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','pj_currency_id');
    }
    
    public function CreditAccount()
    {
        return $this->hasOne('App\Models\Accounting\ChartAccounts', 'aa_id','pj_account_credit');
    }
    
    public function DebitAccount()
    {
        return $this->hasOne('App\Models\Accounting\ChartAccounts', 'aa_id','pj_account_debit');
    }
    
    public function Journal()
    {
        return $this->hasOne('App\Models\Accounting\Journaltypes', 'ty_id','pj_journal_id');
    }
    
    
}