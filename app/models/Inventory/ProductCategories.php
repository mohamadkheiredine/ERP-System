<?php
/***********************************************************
ProductCategories.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Product categories model
***********************************************************/

namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    protected   $table          = 'inventory_product_categories';
    public      $timestamps     = false;
    protected   $primaryKey     = "pc_id";
}