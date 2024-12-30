<?php
/***********************************************************
StockMovements.php
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

class StockMovementItems extends Model
{
    protected   $table          = 'inventory_movement_products';
    public      $timestamps     = false;
    protected   $primaryKey     = "mp_id";


    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','mp_product_id');
    }
    
    public function Movement()
    {
        return $this->hasOne('App\models\Inventory\StockMovements', 'sm_id','mp_movement_id');
    }

}