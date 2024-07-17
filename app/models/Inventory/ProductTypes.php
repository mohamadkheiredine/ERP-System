<?php
/***********************************************************
ProductTypes.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 22, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProductTypes extends Model
{
    protected   $table          = 'inventory_product_types';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";
}