<?php
/***********************************************************
InvoiceTemplates.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 22, 2019
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

class InvoiceTemplates extends Model
{
    protected   $table          = 'billing_invoice_templates';
    public      $timestamps     = false;
    protected   $primaryKey     = "it_id";   
    
    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','it_customer_id');
    }
    
    public function Account()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'aa_id','it_account_id');
    }
 
    
}