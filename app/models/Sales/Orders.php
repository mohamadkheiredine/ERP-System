<?php
/***********************************************************
Orders.php
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

class Orders extends Model
{
    protected   $table          = 'sales_orders';
    public      $timestamps     = false;
    protected   $primaryKey     = "so_id";

    public function Users()
    {
        return $this->hasOne('App\models\Users\Users', 'id','so_assign_to');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','so_order_currency');
    }

    public function Status()
    {
        return $this->hasOne('App\models\Sales\OrderStatus', 'os_id','so_order_status');
    }


    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','so_order_customer');
    }


    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fk_warehouse_id');
    }

}
