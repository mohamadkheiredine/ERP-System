<?php
/***********************************************************
UserTypes.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Feb 24, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/



namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model
{
    protected   $table          = 'usr_users_type';
    public      $timestamps     = false;
    protected   $primaryKey     = "ut_id";
    
}
