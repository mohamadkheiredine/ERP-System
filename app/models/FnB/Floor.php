<?php
/***********************************************************
 * Floor.php
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

class Floor extends Model
{
    protected $table = 'fnb_floor';
    public $timestamps = false;
    protected $primaryKey = "fl_id";

    public function Branch()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id', 'fl_branch_id');
    }

}
