<?php
/***********************************************************
OrderProducts.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Sales;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected   $table          = 'sales_order_products';
    public      $timestamps     = false;
    protected   $primaryKey     = "so_id"; 
    
    
    public function Orders()
    {
        return $this->hasOne('App\Models\Sales\Orders', 'so_id','fk_order_id');
    }
    
    public function Products()
    {
        return $this->hasOne('App\Models\Inventory\Products', 'p_id','fk_product_id');
    }
    
    public function stock()
    {
        return $this->hasOne('App\Models\Inventory\Stocks', 'is_id','so_stock_id');
    }
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','so_product_currency');
    }
    
}