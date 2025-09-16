<?php
/***********************************************************
 * StoreWarehouses.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/13/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Sales;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class StoreWarehouses extends Model
{
    protected   $table          = 'pos_store_warehouses';
    public      $timestamps     = false;
    protected   $primaryKey     = "sw_id";

        public function Company()
        {
            return $this->hasOne('App\models\System\Companies', 'cd_id','sw_company_id');
        }

    public function Store()
    {
        return $this->hasOne('App\models\Sales\Stores', 'ps_id','sw_store_id');
    }


    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','sw_warehouse_id');
    }
}
