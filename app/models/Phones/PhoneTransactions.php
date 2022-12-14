<?php
/***********************************************************
PhoneTransactions.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\models\Phones;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PhoneTransactions extends Model
{
    protected   $table          = 'ph_phone_transaction';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";
    
}