<?php
/***********************************************************
CostCenterBudget.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\CostCenter;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CostCenterBudget extends Model
{
    protected   $table          = 'acc_costcenter_budget';
    public      $timestamps     = false;
    protected   $primaryKey     = "cb_id";
    
    
    public function CostCenter()
    {
        return $this->hasOne('App\models\CostCenter\CostCenters', 'ac_id','fk_cost_center_id');
    }
    
}