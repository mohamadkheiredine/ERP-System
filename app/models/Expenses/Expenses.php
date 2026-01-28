<?php
/***********************************************************
 * Expenses.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 4/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\Expenses;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected   $table          = 'acc_expenses';
    public      $timestamps     = false;
    protected   $primaryKey     = "ac_id";

    public function Employee()
    {
        return $this->hasOne('App\models\Users\Users', 'id','ac_employee_id');
    }


    public function Category()
    {
        return $this->hasOne('App\models\Expenses\ExpensesCategories', 'ec_id','ac_category_id');
    }


    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','ac_currency_id');
    }

    public function Payment()
    {
        return $this->hasOne('App\models\Billing\PaymentTypes', 'pt_id', 'ac_payment_type');
    }

}
