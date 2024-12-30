<?php
/***********************************************************
PayrollsTransactions.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\PayRolls;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PayrollsTransactions extends Model
{
    protected   $table          = 'payrolls_transactions';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";
    
    
    public function Employee()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pt_employee_id');
    }
    
    
    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id','pt_company_id');
    }
    
    
     public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','pt_currency_id');
    }
    
        public function Period()
    {
        return $this->hasOne('App\models\PayRolls\Currency', 'cc_id','pt_currency_id');
    } 
}