<?php
/***********************************************************
WareHouses.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 22, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class WareHouses extends Model
{   
    protected   $table          = 'inventory_warehouses';
    public      $timestamps     = false;
    protected   $primaryKey     = "w_id";

    
    const WT_AREA_SIZE_TYPE   = 1;
    const WT_SIZE_SIZE_TYPE     = 2;
    const WT_LIQUID_SIZE_TYPE   = 3;    
}