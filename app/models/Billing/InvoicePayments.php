<?php
/***********************************************************
InvoicePayments.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 14, 2019
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

class InvoicePayments extends Model
{
    protected   $table          = 'billing_invoice_payments';
    public      $timestamps     = false;
    protected   $primaryKey     = "ip_id";

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','ip_currency_id');
    }

    public function Collector()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ip_collector_id');
    }


        public function Client()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','ip_client_id');
    }
}
