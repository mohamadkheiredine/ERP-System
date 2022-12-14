<?php
/***********************************************************
Invoices.php
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

class Invoices extends Model
{
    protected   $table          = 'billing_invoices';
    public      $timestamps     = false;
    protected   $primaryKey     = "bi_id";   
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','bi_invoice_currency');
    }
    
    public function Account()
    {
        return $this->hasOne('App\Models\Accounting\ChartAccounts', 'aa_id','fk_account_id');
    }
    
    public function CreatedUser()
    {
        return $this->hasOne('App\Models\Users\Users', 'id','bi_created_by');
    }
    
    public function UpdatedUser()
    {
        return $this->hasOne('App\Models\Users\Users', 'id','bi_last_updated_by');
    }
    
}