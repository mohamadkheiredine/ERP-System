<?php

namespace App\models\FnB;

use Illuminate\Database\Eloquent\Model;

class FnbOrderKitchen extends Model
{
    protected $table = 'fnb_kitchen_orders';
    public $timestamps = false;
    protected $primaryKey = "ko_id";

    public function Order()
    {
        return $this->hasOne('App\models\FnB\FnbOrders', 'fo_id', 'ko_order_id');
    }

    public function Status()
    {
        return $this->hasOne('App\models\System\SystemStatus', 'ss_id', 'ko_status_id');
    }

    public function Kitchen()
    {
        return $this->hasOne('App\models\FnB\KitchenStations', 'ks_id', 'ko_kitchen_id');
    }

}
