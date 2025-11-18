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

}
