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

class FnbOrderTables extends Model
{
    protected $table = 'fnb_order_tables';
    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    public function Order()
    {
        return $this->belongsTo('App\models\FnB\FnbOrders', 'fo_id', 'ot_order_id');
    }

    public function Table()
    {
        return $this->belongsTo('App\models\FnB\Tables', 'ft_id', 'ot_table_id');
    }
}
