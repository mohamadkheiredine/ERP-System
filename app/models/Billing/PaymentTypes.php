<?php
/***********************************************************
PaymentTypes.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 10, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PaymentTypes extends Model
{
    protected   $table          = 'billing_payment_types';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";
    
    const PAYMENT_BANK_TRANSFER_ID  = 1;
    const PAYMENT_CASH_ID           = 2;
    const PAYMENT_CHEQUE_ID         = 3;
    const PAYMENT_CREDIT_CARD_ID    = 4;
    const PAYMENT_DEBIT_PAYMENT_ID  = 5;
}