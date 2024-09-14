<?php
/***********************************************************
ProductionPlan.php
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

class ProductionPlan extends Model
{
    protected   $table          = 'prod_production_plan';
    public      $timestamps     = false;
    protected   $primaryKey     = "pp_id";
    
    
    public function Users()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pp_production_manager');
    }
    
    
    public function Status()
    {
        return $this->hasOne('App\models\Production\PlanStatus', 'ps_id','pp_plan_status');
    }
    
    
    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','pp_customer_id');
    }
    
}