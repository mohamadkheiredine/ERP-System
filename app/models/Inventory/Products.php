<?php
/***********************************************************
WareHouses.php
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

class Products extends Model
{
    //use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
    protected   $table          = 'inventory_products';
    public      $timestamps     = false;
    protected   $primaryKey     = "p_id";

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','p_product_currency');
    }
    
    
    public function Category()
    {
        return $this->hasOne('App\models\Inventory\ProductCategories', 'pc_id','fk_pc_id');
    }
    

}