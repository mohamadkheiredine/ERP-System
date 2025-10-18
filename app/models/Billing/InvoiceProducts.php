<?php
/***********************************************************
InvoiceProducts.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class InvoiceProducts extends Model
{
    protected   $table          = 'billing_invoice_items';
    public      $timestamps     = false;
    protected   $primaryKey     = "ii_id";


    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','ii_warehouse_id');
    }


    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','ii_item_id');
    }
}
