<?php
/***********************************************************
WareHouseZones.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class WareHouseZones extends Model
{
    //use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
    protected   $table          = 'inventory_warehouse_zones';
    public      $timestamps     = false;
    protected   $primaryKey     = "wz_id";



}