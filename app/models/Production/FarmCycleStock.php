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

class FarmCycleStock extends Model
{
    protected   $table          = 'prod_farm_stock';
    public      $timestamps     = false;
    protected   $primaryKey     = "fs_id";

    public function Cycle()
    {
        return $this->hasOne('App\models\Production\FarmCycles', 'fc_id','fs_cycle_id');
    }


    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fs_warehouse_id');
    }


    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','fs_product_id');
    }

}
