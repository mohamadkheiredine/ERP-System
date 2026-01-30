<?php

/***********************************************************
TransactionMovements.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
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

class TransactionMovements extends Model
{
    protected   $table          = 'acc_transaction_movements';
    public      $timestamps     = false;
    protected   $primaryKey     = "tm_id";

    public function currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'tm_currency_id');
    }

    public function Payable()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id', 'tm_ledger_account');
    }

    public function Receivable()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id', 'tm_sub_ledger_account');
    }

    public function Transaction()
    {
        return $this->belongsTo('App\models\Accounting\Transactions','fk_tran_id','at_id');
    }
}
