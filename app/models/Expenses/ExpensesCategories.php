<?php
/***********************************************************
 * ExpensesCategories.php
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

class ExpensesCategories extends Model
{
    protected   $table          = 'acc_expense_categories';
    public      $timestamps     = false;
    protected   $primaryKey     = "ec_id";

    public function Account()
    {
        return $this->hasOne('App\models\Accounting\ChartAccounts', 'aa_id','ec_gl_account_id');
    }


    public function Category()
    {
        return $this->hasOne('App\models\Expenses\ExpensesCategories', 'ec_id','ec_parent_category');
    }

    public function Currency()
    {
        return $this->hasOne('App\models\System\Currency', 'cc_id','ec_currency_id');
    }

}
