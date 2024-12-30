<?php
/***********************************************************
PayrollsPeriods.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\PayRolls;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PayrollsPeriods extends Model
{
    protected   $table          = 'payrolls_periods';
    public      $timestamps     = false;
    protected   $primaryKey     = "pp_id";
}