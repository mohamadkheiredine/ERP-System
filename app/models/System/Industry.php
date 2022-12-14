<?php
/***********************************************************
Industry.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
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

class Industry extends Model
{
    protected   $table          = 'sys_industry';
    public      $timestamps     = false;
    protected   $primaryKey     = "si_id";
}

