<?php
/***********************************************************
Receipts.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 15, 2019
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

class Receipts extends Model
{
    protected   $table          = 'billing_receipts';
    public      $timestamps     = false;
    protected   $primaryKey     = "br_id"; 
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','br_receipt_currency');
    } 
    
    public function Invoice()
    {
        return $this->hasOne('App\models\Billing\Invoices', 'bi_id','fk_invoice_id');
    } 
}