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

class FarmCycleOrders extends Model
{
    protected   $table          = 'prod_cycle_orders';
    public      $timestamps     = false;
    protected   $primaryKey     = "co_id";

    public function Cycle()
    {
        return $this->hasOne('App\models\Production\FarmCycles', 'fc_id','co_cycle_id');
    }


    public function Customers()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','co_customer_id');
    }


    public function Orders()
    {
        return $this->hasOne('App\models\Sales\Orders', 'so_id','co_order_id');
    }

}
