<?php
/***********************************************************
CostCenters.php
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

class CostCenters extends Model
{
    protected   $table          = 'acc_costcenter';
    public      $timestamps     = false;
    protected   $primaryKey     = "ac_id";
    
    
    public function Category()
    {
        return $this->hasOne('App\models\CostCenter\Categories', 'cca_id','ac_category_id');
    }
    
    
    public function Type()
    {
        return $this->hasOne('App\models\CostCenter\CostCenterTypes', 'at_id','ac_type_id');
    }
    
    
     public function Manager()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ac_manager_id');
    }
}