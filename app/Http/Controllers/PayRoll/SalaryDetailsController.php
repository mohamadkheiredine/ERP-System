<?php
/***********************************************************
SalaryDetailsController
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/




namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use App\models\PayRolls\PayrollsComissions;
use App\models\PayRolls\PayrollsDeductionsBenefits;
use App\models\PayRolls\PayrollsSalaryDetails;
use App\models\PayRolls\PayrollsTransactions;
use App\models\System\Currency;
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
use App\models\Shipment\OrderStatus;
use App\models\System\Companies;
use App\models\Users\Users;
use Carbon\Carbon;



class SalaryDetailsController extends Controller
{

    /**
     * Page to control Salary Details Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_employees" => $lst_employees
        );
        return Response()->view('payrolls.salarydetails',$data);
    }


    /**
     * Display list of Salary Details  saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $pd_user_id = $request->input('pd_user_id');
        $pd_company_id = $request->input('pd_company_id');
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        $salarydetails_cond = PayrollsSalaryDetails::wherePdIsDeleted(0);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        if( $pd_company_id > 0 )
        {
            $salarydetails_cond = $salarydetails_cond->wherePdCompanyId($pd_company_id);
        }

        if( strlen($general_search)  > 0)
        {
            $salarydetails_cond = $salarydetails_cond->where('pd_description','LIKE','%' . $general_search . '%');
        }

        $slr_count = $salarydetails_cond->count();


        $total_pages = ceil( $slr_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_salarydetails = $salarydetails_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $response_array = array();

        $data = array(
            "lst_salarydetails" => $lst_salarydetails
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;
        $response_array['display'] = view('payrolls.lstsalarydetails',$data)->render();

        return Response()->json($response_array);
    }


    /**
     * Function of Adding a new PayRoll Details
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_currencies" => $lst_currencies,
            "lst_employees" => $lst_employees
        );
        return view('payrolls.addsalarydetail',$data);
    }


    /**
     * Save Salary Details Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveSalaryDetailsInfo(Request $request)
    {
        $pd_id                      = $request->input('pd_id');
        $pd_user_id               = $request->input('pd_user_id');
        $pd_company_id               = $request->input('pd_company_id');
        $pd_basic_salary               = $request->input('pd_basic_salary');
        $pd_currency_id               = $request->input('pd_currency_id');
        $pd_allowances               = $request->input('pd_allowances');
        $pd_deductions               = $request->input('pd_deductions');
        $pd_total_comissions              = $request->input('pd_total_comissions');
        $pd_effective_date              = $request->input('pd_effective_date');
        $pd_end_date              = $request->input('pd_end_date');
        $pd_description              = $request->input('pd_description');

        $result_array = array();


        $details_info = new PayrollsSalaryDetails();
        if( $pd_id != null )
        {
            $details_info= PayrollsSalaryDetails::find($pd_id);
        }

        $details_info->pd_user_id               = $pd_user_id;
        $details_info->pd_company_id            = $pd_company_id;
        $details_info->pd_basic_salary          = $pd_basic_salary;
        $details_info->pd_currency_id           = $pd_currency_id;
        $details_info->pd_allowances            = $pd_allowances;
        $details_info->pd_deductions            = $pd_deductions;
        $details_info->pd_total_comissions      = $pd_total_comissions;
        $details_info->pd_effective_date        = $pd_effective_date;
        $details_info->pd_end_date              = $pd_end_date;
        $details_info->pd_description           = $pd_description;

        $details_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Salary Details Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Salary Details Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $pd_id )
    {
        $details_info        = PayrollsSalaryDetails::find($pd_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_currencies = Currency::all();

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_employees" => $lst_employees,
            "details_info" => $details_info,
            "lst_currencies" => $lst_currencies,
        );
        return view('payrolls.editsalarydetail',$data);
    }


    public function GetEmployeeInfo(Request $request)
    {
        $pd_user_id = $request->input('pd_employee_id');

        $user_info = Users::find($pd_user_id);

        $lst_deductions = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('deduction')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_benefits = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('benefit')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_comissions = PayrollsComissions::wherePcEmployeeId($pd_user_id)->wherePcIsPaid(0)->get();

        $basic_salary = $user_info->u_user_sallary;

        $total_deductions = 0;
        $total_benefits = 0;
        $total_comissions = 0;
        foreach ($lst_deductions as $deduction) {
            $total_deductions = $total_deductions + $deduction->db_amount;
        }

        foreach ($lst_benefits as $benefit) {
            $total_benefits = $total_benefits + $benefit->db_amount;
        }

        foreach ($lst_comissions as $comission) {
            $total_comissions = $total_comissions + $comission->pc_comission_value;
        }

        $data = array(
            'lst_comissions' => $lst_comissions,
            'total_deductions' => $total_deductions,
            'total_comissions' => $total_comissions,
            'basic_salary' => $basic_salary,
            'total_benefits' => $total_benefits
        );

        $result_array['is_error']  = 0;
        $result_array['total_deductions']  = $total_deductions;
        $result_array['total_comissions']  = $total_comissions;
        $result_array['basic_salary']  = $basic_salary;
        $result_array['total_benefits']  = $total_benefits;
        $result_array['comissions']  = view('payrolls.comissions',$data)->render();
        $result_array['salaryinfo']  = view('payrolls.salaryinfo',$data)->render();
        $result_array['error_msg'] = 'Employee Info Extract Succesfully';

        return Response()->json($result_array);
    }


    /**
     * Generate PayRoll Transaction Record and save as payslip for current month to show it for
     * @param Request $request
     * @return void
     */
    public function GeneratePayRollTransaction(Request $request)
    {
        $pd_user_id = $request->input('pd_employee_id');
        $pd_id = $request->input('pd_id');
        $result_array = array();

        $user_info = Users::find($pd_user_id);

        $lst_deductions = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('deduction')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_benefits = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('benefit')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_comissions = PayrollsComissions::wherePcEmployeeId($pd_user_id)->wherePcIsPaid(0)->get();

        $basic_salary = $user_info->u_user_sallary;

        $total_deductions = 0;
        $total_benefits = 0;
        $total_comissions = 0;
        foreach ($lst_deductions as $deduction) {
            $total_deductions = $total_deductions + $deduction->db_amount;
        }

        foreach ($lst_benefits as $benefit) {
            $total_benefits = $total_benefits + $benefit->db_amount;
        }

        foreach ($lst_comissions as $comission) {
            $total_comissions = $total_comissions + $comission->pc_comission_value;
        }

        $payroll_transaction = new PayrollsTransactions();
        $payroll_transaction->pt_company_id = session('company_id');
        $payroll_transaction->pt_employee_id = $pd_user_id;
        $payroll_transaction->pt_total_deductions = $total_deductions;
        $payroll_transaction->ot_total_comissions = $total_comissions;
        $payroll_transaction->pt_transaction_date = date('Y-m-d');
        $payroll_transaction->pt_currency_id = session('company_currency');
        $payroll_transaction->pt_status = 'processed';
        $payroll_transaction->pt_gross_salary = ($basic_salary + $total_benefits + $total_comissions - $total_deductions);
        $payroll_transaction->save();

        // update Salary Details to Add Paid and transaction id
        $details_info = PayrollsSalaryDetails::find($pd_id);
        $details_info->pd_salary_paid = 1;
        $details_info->pd_payroll_transaction = $payroll_transaction->pt_id;
        $details_info->save();


        // add records of transaction and movement in

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'PayRoll Transaction Saved Succesfully';
        return Response()->json($result_array);
    }

    /**
     * Delete Salary Details information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSalaryDetailsInfo(Request $request)
    {

        $pd_id= $request->input('pd_id');

        $details_info = PayrollsSalaryDetails::find( $pd_id);
        $details_info->pd_is_deleted          = 1;
        $details_info->pd_deleted_by          = Session('user_id');
        $details_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
