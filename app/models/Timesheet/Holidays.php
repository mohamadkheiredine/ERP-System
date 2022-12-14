<?php
/***********************************************************
Holidays.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 5, 2019
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

class Holidays extends Model
{
    protected   $table          = 'ts_holidays';
    public      $timestamps     = false;
    protected   $primaryKey     = "th_id";
    
}

