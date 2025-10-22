<?php
/***********************************************************
WareHouseZones.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class WareHouseMovement extends Model
{
    protected   $table          = 'inventory_warehouse_movement';
    public      $timestamps     = false;
    protected   $primaryKey     = "wm_id";

    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','wm_warehouse_id');
    }

    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','wm_product_id');
    }

}
