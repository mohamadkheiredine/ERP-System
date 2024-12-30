<?php
/***********************************************************
PayrollsAuditLogs.php
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

class PayrollsAuditLogs extends Model
{
    protected   $table          = 'payrolls_audit_logs';
    public      $timestamps     = false;
    protected   $primaryKey     = "al_id";
    
    
    public function Transaction()
    {
        return $this->hasOne('App\models\PayRolls\PayrollsTransactions', 'pt_id','al_transaction_id');
    }
    
    
    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cd_id','al_company_id');
    }
}