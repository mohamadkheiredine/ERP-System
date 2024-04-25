<?php
/***********************************************************
shippingPrices.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Shipment;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PackingPrices extends Model
{
    protected   $table          = 'sh_packing_prices';
    public      $timestamps     = false;
    protected   $primaryKey     = "cp_id";
    
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','cp_price_currency');
    }
    
    
    public function Category()
    {
        return $this->hasOne('App\models\Inventory\ProductCategories', 'pc_id','fk_category_id');
    }
    
}