<?php
/***********************************************************
 * AssetsTransfers.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 4/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Assets;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class AssetsTransfers extends Model
{
    protected   $table          = 'inventory_asset_transfers';
    public      $timestamps     = false;
    protected   $primaryKey     = "at_id";

    public function LocationFrom()
    {
        return $this->hasOne('App\models\Assets\AssetLocations', 'il_id','at_from_location_id');
    }

    public function LocationTo()
    {
        return $this->hasOne('App\models\Assets\AssetLocations', 'il_id','at_to_location_id');
    }


    public function FromDepartment()
    {
        return $this->hasOne('App\models\System\Departments', 'sd_id','at_from_department_id');
    }


    public function ToDepartment()
    {
        return $this->hasOne('App\models\System\Departments', 'sd_id','at_to_department_id');
    }

    public function Asset()
    {
        return $this->hasOne('App\models\Assets\Assets', 'aa_id','at_asset_id');
    }


}
