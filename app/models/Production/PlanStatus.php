<?php
/***********************************************************
PlanStatus.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Production;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PlanStatus extends Model
{
    protected   $table          = 'prod_plan_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "ps_id";
}