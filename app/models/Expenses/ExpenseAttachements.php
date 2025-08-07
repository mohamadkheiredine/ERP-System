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

class ExpenseAttachements extends Model
{
    protected   $table          = 'acc_expense_attachments';
    public      $timestamps     = false;
    protected   $primaryKey     = "ea_id";



}
