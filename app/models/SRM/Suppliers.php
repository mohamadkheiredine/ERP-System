<?php
/***********************************************************
Suppliers.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
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

class Suppliers extends Model
{ 
    protected   $table          = 'srm_suppliers';
    public      $timestamps     = false;
    protected   $primaryKey     = "ss_id";
    
    public function PurchaseAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','ss_purchase_account_id');
    }
    
    public function SalesAccount()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','ss_sale_account_id');
    }
    
}