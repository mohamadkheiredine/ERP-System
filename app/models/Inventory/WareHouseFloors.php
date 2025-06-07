<?php
/***********************************************************
WareHouseFloors.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 29, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class WareHouseFloors extends Model
{
    protected   $table          = 'inventory_warehouse_floors';
    public      $timestamps     = false;
    protected   $primaryKey     = "wf_id";


    public function warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fk_warehouse_id');
    }


    public function zone()
    {
        return $this->hasOne('App\models\Inventory\WareHouseZones', 'wz_id','fk_zone_id');
    }

}
