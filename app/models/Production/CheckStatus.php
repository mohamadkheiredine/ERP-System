<?php
/***********************************************************
CheckStatus.php
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

class CheckStatus extends Model
{
    protected   $table          = 'prod_check_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "cs_id";
}