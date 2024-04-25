<?php
/***********************************************************
shippingOrders.php
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

class shippingOrders extends Model
{
    protected   $table          = 'sh_shipping_orders';
    public      $timestamps     = false;
    protected   $primaryKey     = "so_id";
    
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','so_currency_id');
    }
    
    
     public function Status()
    {
        return $this->hasOne('App\models\Shipment\OrderStatus', 'ss_id','fk_status_id');
    }
    
    
     public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','so_customer_id');
    }
}