<?php
/***********************************************************
Vendors.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 28, 2019
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

class Vendors extends Model
{
    protected   $table          = 'inventory_vendors';
    public      $timestamps     = false;
    protected   $primaryKey     = "iv_id";
    
    public function warehouses()
    { 
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','iv_warehouse_id');
    }

    
    public function accounts()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','iv_vendor_account_id');
    }
}