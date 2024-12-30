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
    
    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_RECEPTION = 2;
    const USER_TYPE_AGENT = 3;
    const USER_TYPE_ACCOUNTING = 4;
    const USER_TYPE_TECHNICIAN = 5;
    const USER_TYPE_CLEANER = 6;
    const USER_TYPE_DRIVERS = 7;
    const USER_TYPE_SECURITY = 8;
    const USER_TYPE_TELEMARKETING = 9;
    const USER_TYPE_MAINTAINER = 10;
    const USER_TYPE_SALES = 11;
    const USER_TYPE_SUPERVISOR = 12;
    const USER_TYPE_ASSISTANT = 13;
    const USER_TYPE_ASSISTANT_DIRECTOR = 14;
    const USER_TYPE_COLLECTOR = 15;
    
}
