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
use App\models\Accounting\TransactionMovements;
use App\models\Accounting\Transactions;
use App\models\Billing\PaymentTypes;
use App\models\PayRolls\PayrollsComissions;
use App\models\PayRolls\PayrollsDeductionsBenefits;
use App\models\PayRolls\PayrollsPaymentMethods;
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

        // First day of current month
        $first_day_month = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Last day of current month
        $last_day_month = Carbon::now()->endOfMonth()->format('Y-m-d');

        $data = array(
            "lst_companies" => $lst_companies,
            "first_day_month" => $first_day_month,
            "last_day_month" => $last_day_month,
            "lst_currencies" => $lst_currencies,
            "lst_employees" => $lst_employees
        );
        return view('payrolls.addsalarydetail',$data);
    }

    /**
     * Generate All Sallary details record for this month
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return void
     */
    public function GenerateAllRecords(Request $request)
    {
        $result_array = array();

        $lst_employees = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();

        foreach ($lst_employees as $index => $employee_info)
        {
            // check if we have record for current month
            $count_salary_details = PayrollsSalaryDetails::where('pd_user_id',$employee_info->id)->whereMonth('pd_end_date', Carbon::now()->month)->count();
            if($count_salary_details > 0)
            {
                continue;
            }

            // calculation comissions and deduction and bonus
            $pd_user_id = $employee_info->id;
            $user_info = Users::find($pd_user_id);

            $lst_deductions = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('deduction')->whereMonth('db_effective_date', Carbon::now()->format('m'))->whereYear('db_effective_date', Carbon::now()->format('Y'))->get();
            $lst_benefits = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('benefit')->whereMonth('db_effective_date', Carbon::now()->format('m'))->whereYear('db_effective_date', Carbon::now()->format('Y'))->get();
            $lst_comissions = PayrollsComissions::wherePcEmployeeId($pd_user_id)->whereBetween('pc_effective_date', [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])->wherePcIsPaid(0)->get();

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

            $details_info = new PayrollsSalaryDetails();

            // First day of current month
            $first_day_month = Carbon::now()->startOfMonth()->format('Y-m-d');

            // Last day of current month
            $last_day_month = Carbon::now()->endOfMonth()->format('Y-m-d');

            $details_info->pd_user_id               = $pd_user_id;
            $details_info->pd_company_id            = $user_info->fk_company_id;
            $details_info->pd_basic_salary          = $basic_salary;
            $details_info->pd_currency_id           = session('company_currency');
            $details_info->pd_allowances            = $total_benefits;
            $details_info->pd_deductions            = $total_deductions;
            $details_info->pd_total_comissions      = $total_comissions;
            $details_info->pd_effective_date        = $first_day_month;
            $details_info->pd_end_date              = $last_day_month;
            $details_info->pd_description           = "";
            $details_info->save();


        }



        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Generate Records Successfully';
        return Response()->json($result_array);
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
        $pd_effective_date              = $request->input('pd_effective_date');
        $pd_end_date              = $request->input('pd_end_date');

        $user_info = Users::find($pd_user_id);

        $lst_deductions = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('deduction')->whereMonth('db_effective_date', $pd_effective_date)->whereYear('db_effective_date', $pd_effective_date)->get();
        $lst_benefits = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('benefit')->whereMonth('db_effective_date', $pd_effective_date)->whereYear('db_effective_date', $pd_effective_date)->get();
        $lst_comissions = PayrollsComissions::wherePcEmployeeId($pd_user_id)->whereBetween('pc_effective_date', [$pd_effective_date, $pd_end_date])->wherePcIsPaid(0)->get();

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
        $detailed_info = PayrollsSalaryDetails::find($pd_id);


        $payroll_paymentmethod = PayrollsPaymentMethods::where('pm_employee_id', $pd_user_id)->get();
        if(count($payroll_paymentmethod) == 0)
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'Paymemtn Method for this Employee Not Exist Please Validate inside Employee management Before';
            return Response()->json($result_array);
        }

        $pt_id = $payroll_paymentmethod[0]->pm_payment_method;
        $payment_type = PaymentTypes::find($pt_id);
        $account_id  = $payment_type->pt_payment_account;

        $lst_deductions = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('deduction')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_benefits = PayrollsDeductionsBenefits::whereDbEmployeeId($pd_user_id)->whereDbType('benefit')->whereMonth('db_effective_date', Carbon::now()->month)->whereYear('db_effective_date', Carbon::now()->year)->get();
        $lst_comissions = PayrollsComissions::wherePcEmployeeId($pd_user_id)->whereBetween('pc_effective_date', [$detailed_info->pd_effective_date, $detailed_info->pd_end_date])->wherePcIsPaid(0)->get();

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

        foreach ($lst_comissions as $index => $comission_info)
        {
                $com_info = PayrollsComissions::find($comission_info->pc_id);
                $com_info->pc_is_paid = 1;
                $com_info->save();
        }

        $creation_date = date('Y-m-d');
        $currency_id = session('company_currency');;

        // add records of transaction and movement in
        $stock_label = "Salary Transaction Accounting For " . $detailed_info->Employee->u_fullname . " On " . $creation_date;
        $transactions = new Transactions();
        $transactions->at_transaction_date  = $creation_date;
        $transactions->at_creation_date     = $creation_date;
        $transactions->at_accounting_doc    = $stock_label;
        $transactions->fk_acc_journal_id    = 3;
        $transactions->at_currency_id       =$currency_id;
        $transactions->save();
        $at_id = $transactions->at_id;

        $transaction_movements = new TransactionMovements();
        $transaction_movements->fk_tran_id              = $at_id;
        $transaction_movements->tm_ledger_account       = 6311;
        $transaction_movements->tm_sub_ledger_account   = 6311;
        $transaction_movements->tm_ledger_label         = "Salary For " . $detailed_info->Employee->u_fullname . " on date " . date("m-Y",strtotime($creation_date));
        $transaction_movements->tm_debit                = ($basic_salary + $total_benefits  - $total_deductions);
        $transaction_movements->tm_credit               = 0;
        $transaction_movements->tm_creation_date        = $creation_date;
        $transaction_movements->tm_currency_id          = $currency_id;
        $transaction_movements->save();


        $transaction_movements = new TransactionMovements();
        $transaction_movements->fk_tran_id              = $at_id;
        $transaction_movements->tm_ledger_account       = 6318;
        $transaction_movements->tm_sub_ledger_account   = 6318;
        $transaction_movements->tm_ledger_label         = "Comission on salary For " . $detailed_info->Employee->u_fullname . " on date " . date("m-Y",strtotime($creation_date));
        $transaction_movements->tm_debit                = $total_comissions;
        $transaction_movements->tm_credit               = 0;
        $transaction_movements->tm_creation_date        = $creation_date;
        $transaction_movements->tm_currency_id          = $currency_id;
        $transaction_movements->save();


        $transaction_movements = new TransactionMovements();
        $transaction_movements->fk_tran_id              = $at_id;
        $transaction_movements->tm_ledger_account       = $account_id;
        $transaction_movements->tm_sub_ledger_account   = $account_id;
        $transaction_movements->tm_ledger_label         = "Sallary details " . $detailed_info->Employee->u_fullname . " on date " . date("m-Y",strtotime($creation_date));
        $transaction_movements->tm_debit                = 0;
        $transaction_movements->tm_credit               = ($basic_salary + $total_benefits  - $total_deductions + $total_comissions);
        $transaction_movements->tm_creation_date        = $creation_date;
        $transaction_movements->tm_currency_id          = $currency_id;
        $transaction_movements->save();


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
