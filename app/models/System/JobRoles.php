<?php
/***********************************************************
JobRoles.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 30, 2019
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

class JobRoles extends Model
{ 
    protected   $table          = 'sys_job_roles';
    public      $timestamps     = false;
    protected   $primaryKey     = "jr_id";
    
    
    
}

