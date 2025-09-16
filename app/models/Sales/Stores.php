<?php
/***********************************************************
 * Stores.php
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

class Stores extends Model
{
    protected   $table          = 'pos_stores';
    public      $timestamps     = false;
    protected   $primaryKey     = "ps_id";


    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id','ps_company_id');
    }


    public function Manager()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ps_manager_id');
    }

}
