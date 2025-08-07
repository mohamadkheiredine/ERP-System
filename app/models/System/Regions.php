<?php
/***********************************************************
Units.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\System;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected   $table          = 'sys_location_region';
    public      $timestamps     = false;
    protected   $primaryKey     = "lr_id";
}

