<?php
/***********************************************************
Languages.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 13, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page of model for languages 
***********************************************************/

namespace App\models\System;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected   $table          = 'language_management';
    public      $timestamps     = false;
    protected   $primaryKey     = "lm_id";
}

