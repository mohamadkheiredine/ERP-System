<?php
/***********************************************************
 * FarmCycles.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/23/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Production;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FarmCycleExpenses extends Model
{
    protected   $table          = 'prod_cycle_expenses';
    public      $timestamps     = false;
    protected   $primaryKey     = "ce_id";

    public function Cycle()
    {
        return $this->hasOne('App\models\Production\FarmCycles', 'fc_id','ce_cycle_id');
    }


    public function Voucher()
    {
        return $this->hasOne('App\models\Billing\PaymentVouchers', 'pv_id','ce_voucher_id');
    }

}
