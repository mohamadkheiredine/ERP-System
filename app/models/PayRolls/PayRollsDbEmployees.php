<?php
/***********************************************************
 * PayRollsDbEmployees.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 2/24/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


namespace App\models\PayRolls;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PayRollsDbEmployees extends Model
{
    protected   $table          = 'payrolls_deductions_benefits';
    public      $timestamps     = false;
    protected   $primaryKey     = "db_id";


    public function Employee()
    {
        return $this->hasOne('App\models\Users\Users', 'id','fk_user_id');
    }


    public function DedBenefits()
    {
        return $this->hasOne('App\models\PayRolls\PayrollsDeductionsBenefits', 'db_id','fk_db_id');
    }

}
