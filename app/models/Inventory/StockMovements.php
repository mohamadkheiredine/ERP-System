<?php
/***********************************************************
StockMovements.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 21, 2019
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

class StockMovements extends Model
{
    protected   $table          = 'inventory_stock_movements';
    public      $timestamps     = false;
    protected   $primaryKey     = "sm_id";

    public function CreatedBy()
    {
        return $this->hasOne('App\models\Users\Users', 'id','sm_created_by');
    }
    
    
    public function SourceWarehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fk_warehouse_from');
    }
    
    
    public function DestinationWarehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','fk_warehouse_to');
    }

}