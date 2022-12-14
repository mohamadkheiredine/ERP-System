<?php
/***********************************************************
CRMAccounts.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\CRM;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CRMAccounts extends Model
{ 
    protected   $table          = 'crm_accounts';
    public      $timestamps     = false;
    protected   $primaryKey     = "ca_id";
    
    
    
}