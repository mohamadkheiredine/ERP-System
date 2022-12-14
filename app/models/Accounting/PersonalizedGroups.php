<?php
/***********************************************************
PersonalizedGroups.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 18, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/




namespace App\models\Accounting;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PersonalizedGroups extends Model
{ 
    protected   $table          = 'acc_personalized_groups';
    public      $timestamps     = false;
    protected   $primaryKey     = "pg_id";
   
}