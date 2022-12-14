<?php
/***********************************************************
AccountCategories.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 9, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\models\Accounting;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class AccountCategories extends Model
{ 
    protected   $table          = 'acc_account_categories';
    public      $timestamps     = false;
    protected   $primaryKey     = "ac_id";    
}