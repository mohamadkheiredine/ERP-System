<?php
/***********************************************************
DefaultAccounts.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Default Accounts
***********************************************************/


namespace App\models\Accounting;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class DefaultAccounts extends Model
{
    protected   $table          = 'acc_default_accounts';
    public      $timestamps     = false;
    protected   $primaryKey     = "da_id";
    
}



?>



