<?php
/***********************************************************
StockIds.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 2, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class StockIds extends Model
{
    protected   $table          = 'inventory_stock_ids';
    public      $timestamps     = false; 

    
    
    public function stock()
    { 
        return $this->hasOne('App\Models\Inventory\Stocks', 'is_id','si_stock_id');
    }
}