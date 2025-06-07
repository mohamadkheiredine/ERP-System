<?php
/***********************************************************
PayrollsPaymentMethods.php
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

class PayrollsPaymentMethods extends Model
{
    protected   $table          = 'payrolls_payment_methods';
    public      $timestamps     = false;
    protected   $primaryKey     = "pm_id";


    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id','pm_company_id');
    }


    public function Employee()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pm_employee_id');
    }


}
