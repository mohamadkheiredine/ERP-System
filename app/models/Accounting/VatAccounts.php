<?php
/***********************************************************
VatAccounts.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 25, 2019
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

class VatAccounts extends Model
{
    protected   $table          = 'acc_vat_accounts';
    public      $timestamps     = false;
    protected   $primaryKey     = "av_id";   
}