<?php
/***********************************************************
 * SysGovernorates.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


namespace App\models\Locations;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class SysGovernorates extends Model
{
    protected   $table          = 'sys_governorates';
    public      $timestamps     = false;
    protected   $primaryKey     = "sg_id";
}
