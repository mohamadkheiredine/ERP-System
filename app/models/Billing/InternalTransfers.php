<?php
/***********************************************************
InternalTransfers.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 6, 2021
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

class InternalTransfers extends Model
{
    protected   $table          = 'acc_internal_notes';
    public      $timestamps     = false;
    protected   $primaryKey     = "in_id";   
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','in_credit_currency');
    }
    
    public function SenderAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','in_account_sender');
    }
    
    public function ReceivableAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','in_account_receivable');
    }
    
}