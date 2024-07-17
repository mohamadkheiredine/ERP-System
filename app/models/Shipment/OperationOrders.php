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

class OperationOrders extends Model
{
    protected   $table          = 'lg_operations_orders';
    public      $timestamps     = false;
    
    
    public function Order()
    {
        return $this->hasOne('App\models\Shipment\ShippingOrders', 'so_id','fk_oo_order_id');
    }
    
    
     public function Operation()
    {
        return $this->hasOne('App\models\Shipment\ShipOperations', 'so_id','fk_oo_operation_id');
    }
}