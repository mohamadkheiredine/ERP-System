<?php
/***********************************************************
InboundCall.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : 17-08-2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BillsResultsWorkflow extends Model
{
    protected   $table          = 'billing_billsresults_workflow';
    public      $timestamps     = false;
    protected   $primaryKey     = "bw_id";


    public function AssignedTo()
    {
        return $this->hasOne('App\models\Users\Users', 'id','bw_assigned_to');
    }


    public function Bill()
    {
        return $this->hasOne('App\models\Billing\InvoicePayments', 'ip_id','bw_bill_id');
    }


    public function Result()
    {
        return $this->hasOne('App\models\CallCenter\CallResults', 'cr_id','bw_result_id');
    }

}
