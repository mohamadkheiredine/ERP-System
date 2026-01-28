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

class FnbOrders extends Model
{
    protected $table = 'fnb_orders';
    public $timestamps = false;
    protected $primaryKey = "fo_id";

    protected $fillable = [
        'fo_order_code',
        'fo_order_type',
        'fo_store_id',
        'fo_branch_id',
        'fo_customer_id',
        'fo_order_status',
        'fo_order_datetime',
        'fo_subtotal',
        'fo_discount',
        'fo_created_by',
        'fo_currency_id',
        'fo_total_amount',
        'fo_payment_status',
        'fo_paid_amount',
        'fo_order_structure',
    ];

    public function Items()
    {
        return $this->hasMany('App\models\FnB\FnbOrderItems', 'oi_order_id', 'fo_id');
    }

    public function Branch()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id', 'fo_branch_id');
    }

    public function Store()
    {
        return $this->hasOne('App\models\Sales\Stores', 'pos_stores', 'fo_store_id');
    }

    public function Table()
    {
        return $this->hasOne('App\models\FnB\Tables', 'ft_id', 'fo_table_id');
    }

    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id', 'fo_customer_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'fo_currency_id');
    }

    public function Payment()
    {
        return $this->hasOne('App\models\Billing\PaymentTypes', 'pt_id', 'fo_payment_type');
    }

    public function CreatedBy()
    {
        return $this->hasOne('App\models\Users\Users', 'id', 'fo_created_by');
    }
}
