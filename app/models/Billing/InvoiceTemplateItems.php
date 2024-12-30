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

class InvoiceTemplateItems extends Model
{
    protected   $table          = 'billing_invoice_template_items';
    public      $timestamps     = false;
    protected   $primaryKey     = "ti_id";   
    
    
    public function Template()
    {
        return $this->hasOne('App\models\Billing\InvoiceTemplates', 'it_id','fk_template_id');
    }
    
    public function Service()
    {
        return $this->hasOne('App\models\CRM\CRMServices', 'cs_id','tl_item_id');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','ti_currency_id');
    }
       
}