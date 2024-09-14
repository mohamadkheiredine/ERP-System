<?php
/***********************************************************
Customers.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\Inventory;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected   $table          = 'inventory_customers';
    public      $timestamps     = false;
    protected   $primaryKey     = "ic_id";
    
    
    public function Account()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','ic_account_number');
    }
    
    
}