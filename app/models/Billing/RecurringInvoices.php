<?php
/***********************************************************
RecurringInvoices.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 14, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

namespace App\models\Billing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class RecurringInvoices extends Model
{
    protected   $table          = 'billing_recurring_invoices';
    public      $timestamps     = false;
    protected   $primaryKey     = "ri_id";   
    
    public function Template()
    {
        return $this->hasOne('App\models\Billing\InvoiceTemplates', 'it_id','ri_template_id');
    }

    public function Customer()
    {
        return $this->hasOne('App\models\Inventory\Customers', 'ic_id','ri_customer_id');
    }

    
    public function Account()
    {
        return $this->hasOne('App\models\CRM\CRMAccounts', 'ca_id','ri_customer_id');
    }

    
    public function CreatedUser()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ri_created_by');
    }
    
    public function UpdatedUser()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ri_last_updated_by');
    }
    
}