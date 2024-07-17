<?php
/***********************************************************
TransportationMode.php
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

class TransportationMode extends Model
{
    protected   $table          = 'lg_transportation_mode';
    public      $timestamps     = false;
    protected   $primaryKey     = "tm_id";
    
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','cp_price_currency');
    }
    
    public function Orders()
    {
        return $this->hasOne('App\models\Shipment\ShippingOrders', 'so_id','fk_status_id');
    }
}