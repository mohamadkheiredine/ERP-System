<?php
/***********************************************************
 * Tables.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/27/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\FnB;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class FnbOrderItems extends Model
{
    protected $table = 'fnb_order_items';
    public $timestamps = false;
    protected $primaryKey = "oi_id";

    public function Order()
    {
        return $this->hasOne('App\models\FnB\FnbOrders', 'fo_id', 'oi_order_id');
    }

    public function Item()
    {
        return $this->hasOne('App\models\FnB\FnbItem', 'fi_id', 'oi_item_id');
    }

    public function Station()
    {
        return $this->hasOne('App\models\FnB\KitchenStations', 'ks_id', 'oi_station_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'oi_currency_id');
    }

}
