<?php
/***********************************************************
PayRollController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\library\ClientsCategoriesManager;
use App\models\CRM\CRMAccounts;
use App\models\Users\Users;
use App\models\CRM\CRMLeads;
use App\models\System\Industry;
use App\models\System\Countries;
use App\models\CRM\CRMAccountTypes;
use App\library\AccountsManager;
use App\models\Users\Payroll;



class PayRollController extends Controller
{
    
    /**
     * Page to display payroll for all Employees by month
     * 
     * @author Moe Mantach
     * @access public
     */
    public function EmployeesPayroll()
    { 
        
        $data = array();
        return Response()->view('payroll.employeespayroll',$data);
    }
    
    
    
    /**
     * Display List of PayRoll9o+
     * o oo    * @param Request $request
     */
    public function DisplayListPayRoll(Request $request)
    {
        $ts_date    = $request->input('ts_date');
        if(strlen($ts_date) == 0)
            $ts_date = date("Y-m");
        $date       = explode("-", $ts_date);
        $year       = $date[0];
        $month      = $date[1];
        $result_array = array();
        
        
        $lst_payroll = Payroll::whereUpMonth($month)->whereUpYear($year)->get();
        
        $data = array(
            "lst_payroll" => $lst_payroll
        );
        $result_array['display'] = view("payroll.lstemployeespayroll",$data)->render(); 
        return Response()->json($result_array);
    }
    
    public function GenerateMonthPayRoll(Request $request)
    {
        $ts_date    = $request->input('ts_date');
        if(strlen($ts_date) == 0)
            $ts_date = date("Y-m");
        $date           = explode("-", $ts_date);
        $year           = $date[0];
        $month          = $date[1];
        $result_array   = array();
        $count_payroll = Payroll::whereUpMonth($month)->whereUpYear($year)->count();
        
        if($count_payroll > 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "PayRoll Employees Already Exist";
            return Response()->json($result_array);
        }
        
        
        $lst_user_info = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        
        foreach ( $lst_user_info as $key => $user_info ) 
        {
            $PayRollItem = new Payroll();
            $PayRollItem->fk_user_id        = $user_info->id;
            $PayRollItem->up_sallary_amount = $user_info->u_user_sallary;
            $PayRollItem->up_currency_id    = session("company_currency");
            $PayRollItem->up_month          = $month;
            $PayRollItem->up_year           = $year;
            $PayRollItem->save();
        }
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
        
    }
}