<?php
/***********************************************************
PaymentTerms.php
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

class PaymentTerms extends Model
{
    protected   $table          = 'billing_payment_terms';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";   
}