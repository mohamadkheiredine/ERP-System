<?php
/***********************************************************
AppConfig.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 8, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/






namespace App\models\System;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected   $table          = 'sys_appconfig';
    public      $timestamps     = false;
    protected   $primaryKey     = "sa_id";
}

