<?php
/***********************************************************
DayTypes.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Timesheet;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class DayTypes extends Model
{
    protected   $table          = 'ts_days_type';
    public      $timestamps     = false;
    protected   $primaryKey     = "dt_id";
    
}

