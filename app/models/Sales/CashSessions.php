<?php
/***********************************************************
 * CashSessions.php
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

class CashSessions extends Model
{
    protected   $table          = 'pos_cash_sessions';
    public      $timestamps     = false;
    protected   $primaryKey     = "cs_session_id";

    public function Store()
    {
        return $this->hasOne('App\models\Sales\Stores', 'ps_id','cs_store_id');
    }


    public function Terminal()
    {
        return $this->hasOne('App\models\Sales\Terminals', 'pt_id','cs_pos_id');
    }
}
