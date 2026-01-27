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

class FnbOrderItemModifiers extends Model
{
    protected $table = 'fnb_order_item_modifiers';
    public $timestamps = false;
    protected $primaryKey = "im_id";

      protected $fillable = [
        'im_item_id',
        'im_order_id',
        'im_modifier_id',
        'im_quantity',
        'im_price',
        'im_modifier_name',
        'im_quantity'
    ];

    public function Item()
    {
        return $this->hasOne('App\models\FnB\FnbMenuItem', 'fi_id', 'im_item_id');
    }

    public function Modifier()
    {
        return $this->hasOne('App\models\FnB\Modifier', 'm_id', 'im_modifier_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id', 'im_currency_id');
    }

}
