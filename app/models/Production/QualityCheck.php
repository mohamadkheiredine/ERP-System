<?php
/***********************************************************
QualityCheck.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 2, 2020
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

class QualityCheck extends Model
{
    protected   $table          = 'prod_quality_check';
    public      $timestamps     = false;
    protected   $primaryKey     = "qc_id";
    
    
    public function Plan()
    {
        return $this->hasOne('App\models\Production\ProductionPlan', 'pp_id','fk_plan_id');
    }
    
    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','qc_product_id');
    }
    
 
    public function Team()
    {
        return $this->hasOne('App\models\Users\UserTeam', 'ut_id','qc_team_id');
    }
}