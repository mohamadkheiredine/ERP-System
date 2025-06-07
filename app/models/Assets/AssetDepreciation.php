<?php
/***********************************************************
 * AssetLocations.php
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

class AssetDepreciation extends Model
{
    protected   $table          = 'inventory_asset_depreciation';
    public      $timestamps     = false;
    protected   $primaryKey     = "ad_id";


    public function Asset()
    {
        return $this->hasOne('App\models\Assets\Assets', 'aa_id','ad_asset_id');
    }
}
