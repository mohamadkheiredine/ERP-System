<?php
/***********************************************************
 * BillsRvs.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/15/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BillsRvs extends Model
{
    protected   $table          = 'billing_bills_rvs';
    public      $timestamps     = false;
    protected   $primaryKey     = "br_id";

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','br_currency_id');
    }

    public function Client()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','br_client_id');
    }

    public function Deals()
    {
        return $this->hasOne('App\models\CRM\CRMDeals', 'ad_id','br_deal_id');
    }


    public function Bill()
    {
        return $this->hasOne('App\models\Billing\InvoicePayments', 'ip_id','br_bill_id');
    }
}
