<?php
/***********************************************************
PlanItems.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 14, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/




namespace App\models\Production;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PlanItems extends Model
{
    protected   $table          = 'prod_plan_items';
    public      $timestamps     = false;
    protected   $primaryKey     = "pi_id";
    
    
    public function Products()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','pi_product_id');
    }
    
    
    public function Plan()
    {
        return $this->hasOne('App\models\Production\ProductionPlan', 'p_id','pi_product_id');
    }
    
}