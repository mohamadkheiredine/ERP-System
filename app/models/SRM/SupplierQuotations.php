<?php
/***********************************************************
SupplierQuotations.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\SRM;

use DB;
use Illuminate\Database\Eloquent\Model;

class SupplierQuotations extends Model
{
    protected   $table          = 'srm_supplier_quotations';
    public      $timestamps     = false;
    protected   $primaryKey     = "sq_id";

    public function Supplier()
    {
        return $this->hasOne('App\models\SRM\Suppliers', 'ss_id','fk_supplier_id');
    }

    public function Warehouse()
    {
        return $this->hasOne('App\models\Inventory\WareHouses', 'w_id','sq_warehouse_id');
    }
}
