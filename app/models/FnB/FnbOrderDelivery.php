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

class FnbOrderDelivery extends Model
{
    protected $table = 'fnb_order_delivery';
    public $timestamps = false;
    protected $primaryKey = 'delivery_id';

    public function Order()
    {
        return $this->belongsTo('App\models\FnB\FnbOrders', 'od_order_id', 'fo_id');
    }

    public function Status()
    {
        return $this->belongsTo('App\models\System\SystemStatus', 'od_delivery_status', 'ss_id');
    }
}
