<?php
/***********************************************************
InvoiceTemplateItems.php
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
    

        
    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','it_invoice_currency');
    }
    
    public function Accounts()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','it_account_id');
    }
    
    public function Customers()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','it_customer_id');
    }
    
    public function CreatedUser()
    {
        return $this->hasOne('App\models\Users\Users', 'id','it_created_by');
    }
    
    public function UpdatedUser()
    {
        return $this->hasOne('App\models\Users\Users', 'id','it_last_updated_by');
    }
    

    
}