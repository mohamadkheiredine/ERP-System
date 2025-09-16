<?php
/***********************************************************
 * Terminals.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/13/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Sales;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Terminals extends Model
{
    protected   $table          = 'pos_terminals';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";


    public function Store()
    {
        return $this->hasOne('App\models\Sales\Stores', 'ps_id','pt_store_id');
    }
}
