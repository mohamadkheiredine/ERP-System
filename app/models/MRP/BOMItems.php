<?php
/***********************************************************
BOMItems.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\MRP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BOMItems extends Model
{ 
    protected   $table          = 'mrp_bom_items';
    public      $timestamps     = false;
    protected   $primaryKey     = "bi_id";
    
    
    public function products()
    {
        return $this->hasOne('App\Models\Inventory\Products', 'p_id','bi_product_id');
    }
    
    
    public function currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','bi_price_currency');
    }
}