<?php
/***********************************************************
SuppliersBidding.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\SRM;

use DB; 
use Illuminate\Database\Eloquent\Model;

class SuppliersBidding extends Model
{ 
    protected   $table          = 'srm_supplier_bidding';
    public      $timestamps     = false;
    protected   $primaryKey     = "sb_id";
    
    
}