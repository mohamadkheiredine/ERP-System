<?php
/***********************************************************
Stocks.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 21, 2019
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

class Stocks extends Model
{
    protected   $table          = 'inventory_stocks';
    public      $timestamps     = false;
    protected   $primaryKey     = "is_id";

    
    
    public function products()
    { 
        return $this->hasOne('App\models\Inventory\Products', 'p_id','fk_product_id');
    }
    
    public function warehouses()
    { 
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fk_warehouse_id');
    }
    
    public function Zones()
    {
        return $this->hasOne('App\models\Inventory\WareHouseZones', 'wz_id','fk_zone_id');
    }
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','is_price_currency');
    } 

}