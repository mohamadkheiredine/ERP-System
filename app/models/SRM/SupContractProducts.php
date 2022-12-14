<?php
/***********************************************************
SupContractProducts.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\SRM;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class SupContractProducts extends Model
{ 
    protected   $table          = 'srm_contract_products';
    public      $timestamps     = false;
    
}