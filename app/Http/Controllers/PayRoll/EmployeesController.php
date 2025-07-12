<?php

/* * *********************************************************
  UsersController.php
  Product :
  Version : 1.0
  Release : 2
  Date Created :Sep 5, 2018
  Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
  All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2018

  Page Description :
  {Enter page description Here}
 * ********************************************************* */

namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\Array;
use App\Http\Controllers\Users\unknown;
use App\models\PayRolls\PayrollsPaymentMethods;
use App\models\Accounting\ChartAccounts;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Config;
use File;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\System\JobTitles;
use App\models\System\JobRoles;
use App\models\System\Departments;
use App\models\System\Roles;
use App\models\Timesheet\EmploymentType;
use App\library\UsersManager;
use App\models\System\Companies;
use App\models\System\Languages;
use App\models\Inventory\WareHouses;
use App\models\Users\UserTeam;
use App\models\Users\UserTypes;

class EmployeesController extends Controller {


    public function index() {

        $lst_companies = Companies::where('cd_is_deleted', 0)->get();

        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view("payrolls.employees", $data);
    }

    /**
     * Display List of Employees
     * @param Request $request
     */
    public function DisplayList(Request $request) {

        $page_number = $request->input("page_number");
        $company_id = $request->input('company_id');
        $general_search = $request->input("general_search");
        $nbr_rows_per_pages = Config::get('appconfig.max_rows_per_page');

        if ($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages;
        else
            $skip = 0;



        $users_cond = Users::whereUIsDeleted(0)->whereUIsActive(1);

        if (strlen($general_search) > 0) {
            $users_cond = $users_cond->orWhere('u_fullname', 'LIKE', '%' . $general_search . '%');
            $users_cond = $users_cond->orWhere('u_email', 'LIKE', '%' . $general_search . '%');
            $users_cond = $users_cond->orWhere('u_mobile', 'LIKE', '%' . $general_search . '%');
        }


        if ($company_id > 0) {
            $users_cond = $users_cond->whereFkCompanyId($company_id);
        }

        $user_count = $users_cond->count();
        $total_pages = ceil($user_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        $lst_users = $users_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $response_array = array();

        $data = array(
            "lst_users" => $lst_users
        );
        $response_array['is_error'] = 0;
        $result_array['total_pages'] = $total_pages;
        $response_array['display'] = view('payrolls.listemployees', $data)->render();

        return Response()->json($response_array);
    }

    /**
     * Add User Form
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function AddForm(Request $request) {

        $lst_job_titles = JobTitles::whereJtIsDeleted(0)->get();
        $lst_job_roles = JobRoles::whereJrIsDeleted(0)->get();
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
        $lst_employment_type = EmploymentType::whereEtIsDeleted(0)->get();
        $lst_companies = Companies::whereCdIsDeleted(0)->whereCdPrimaryCompany(1)->get();
        $lst_warhouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_user_types = UserTypes::all();

        $rand = rand(9, 99999);
        $data = array(
            "rand" => $rand,
            "lst_job_titles" => $lst_job_titles,
            "lst_job_roles" => $lst_job_roles,
            "lst_departments" => $lst_departments,
            "lst_warhouses" => $lst_warhouses,
            "lst_employment_type" => $lst_employment_type,
            "lst_user_types" => $lst_user_types,
            "lst_companies" => $lst_companies
        );
        return Response()->view('payrolls.addemployee', $data);
    }

    /**
     * Edit User Form
     * @param unknown $user_id
     */
    public function EditForm($user_id) {
        $user_info = Users::find($user_id);
        $rand = rand(9, 99999);
        $lst_job_titles = JobTitles::whereJtIsDeleted(0)->get();
        $lst_job_roles = JobRoles::whereJrIsDeleted(0)->get();
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
        $lst_employment_type = EmploymentType::whereEtIsDeleted(0)->get();
        $lst_companies = Companies::whereCdIsDeleted(0)->whereCdPrimaryCompany(1)->get();
        $lst_warhouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_user_types = UserTypes::all();

        $payroll_paymentmethod = PayrollsPaymentMethods::where('pm_company_id', $user_info->fk_company_id)->get();

        if($payroll_paymentmethod->count() > 0) {
            $payroll_paymentmethod = $payroll_paymentmethod[0];
        }

        $data = array(
            "rand" => $rand,
            "user_info" => $user_info,
            "lst_job_titles" => $lst_job_titles,
            "lst_job_roles" => $lst_job_roles,
            "lst_departments" => $lst_departments,
            "lst_warhouses" => $lst_warhouses,
            "lst_employment_type" => $lst_employment_type,
            "lst_user_types" => $lst_user_types,
            "payroll_paymentmethod" => $payroll_paymentmethod,
            "lst_companies" => $lst_companies
        );
        return Response()->view('payrolls.editemployee', $data);
    }

    /**
     * Save User information based on data received from Add/Edit User Form
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array $result_array
     */
    public function SaveInfo(Request $request) {
        $user_id = $request->input('user_id');
        $u_fullname = $request->input('u_fullname');
        $u_username = str_replace(' ', '.', $u_fullname);
        $u_password = '123123123';
        $u_user_type = $request->input('u_user_type');
        $u_gender = $request->input('u_gender');
        $u_residential_area = $request->input('u_residential_area');
        $u_email = $request->input('u_email');
        $u_date_birth = $request->input('u_date_birth');
        $u_mobile = $request->input('u_mobile');
        $u_phone = $request->input('u_phone');
        $u_fax = $request->input('u_fax');
        $u_website = $request->input('u_website');
        $u_job_role_id = $request->input('u_job_role_id');
        $u_job_title_id = $request->input('u_job_title_id');
        $u_department_id = $request->input('u_department_id');
        $u_employee_type = $request->input('u_employee_type');
        $u_employment_date = $request->input('u_employment_date');
        $u_daily_working_hours = $request->input('u_daily_working_hours');
        $u_address = $request->input('u_address');
        $fk_company_id = $request->input('fk_company_id');
        $u_user_sallary = $request->input('u_user_sallary');
        $u_sales_commission = $request->input('u_sales_commission');
        $u_number_holidays = $request->input('u_number_holidays');
        $u_attendance_code = $request->input('u_attendance_code');
        $fk_warehouse_id = $request->input('fk_warehouse_id');
        $u_cnss_number = $request->input('u_cnss_number');
        $u_has_insurance = $request->input('u_has_insurance');
        $u_marital_status = $request->input('u_marital_status');
        $u_number_of_dependencies = $request->input('u_number_of_dependencies');
        $u_hourly_rate = $request->input('u_hourly_rate');
        $u_is_active = $request->input('u_is_active');
        $pm_id              = $request->input('pm_id');
        $pm_account_number  = $request->input('pm_account_number');
        $pm_payment_method  = $request->input('pm_payment_method');

        $result_array = array();

        // check if username exist and return warning
        if($user_id == null)
        {
            $count_users = Users::whereUIsDeleted(0)->where('u_username','LIKE','%' . $u_username . '%')->count();
            if($count_users > 0 )
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "User Already Exist !!";
                return Response()->json($result_array);
            }
        }

        $Users = new Users();
        if ($user_id !== null) {
            $Users = Users::find($user_id);
        }
        else
        {
            $account_info   = ChartAccounts::where("aa_account_ref","=","6311")->get();
            $account_info = $account_info[0];

            $count   = ChartAccounts::where("aa_account_ref","LIKE","6311%")->count();

            $new_count      = $count + 1;
            $aa_account_ref = $account_info->aa_account . (String)$new_count;


             $AccAccounting = new ChartAccounts();
             $AccAccounting->aa_parent_account   = $account_info->aa_id;
             $AccAccounting->aa_account_ref      = $aa_account_ref;
             $AccAccounting->aa_account          = $aa_account_ref;
             $AccAccounting->aa_sub_account      = $account_info->aa_id;
             $AccAccounting->aa_account_label    = $u_fullname;
             $AccAccounting->fk_country_id       = 0;
             $AccAccounting->save();
             $aa_id = $AccAccounting->aa_id;

            $Users->u_account_id = $aa_id;

        }


        $Users->u_fullname = $u_fullname;
        $Users->u_username = $u_username;
        if (strlen($u_password) > 0 ) {
            $Users->password = Hash::make($u_password);
        }

        $UsersManager = new UsersManager();
        $u_avatar_base_src = "";
        $u_avatar_filename = "";
        $u_avatar_extentions = "";

        // upload user profile pic
        if (count($_FILES) > 0) {

            $image_data = $UsersManager->UploadAvatarUsers(null);

            $u_avatar_base_src = $image_data['data']['u_avatar_base_src'];
            $u_avatar_filename = $image_data['data']['u_avatar_filename'];
            $u_avatar_extentions = $image_data['data']['u_avatar_extentions'];
        }


        $Users->u_user_type = $u_user_type;
        $Users->u_gender = $u_gender;
        $Users->u_residential_area = $u_residential_area;
        $Users->u_email = $u_email;
        $Users->u_date_birth = date("Y-m-d", strtotime($u_date_birth));
        $Users->u_mobile = $u_mobile;
        $Users->u_phone = $u_phone;
        $Users->u_fax = $u_fax;
        $Users->u_website = $u_website;
        $Users->u_job_role_id = $u_job_role_id;
        $Users->u_job_title_id = $u_job_title_id;
        $Users->u_department_id = $u_department_id;
        $Users->u_employee_type = $u_employee_type;
        $Users->u_employment_date = date("Y-m-d", strtotime($u_employment_date));
        $Users->u_daily_working_hours = $u_daily_working_hours;
        $Users->u_address = $u_address;
        $Users->u_avatar_base_src = $u_avatar_base_src;
        $Users->u_avatar_filename = $u_avatar_filename;
        $Users->u_avatar_extentions = $u_avatar_extentions;
        $Users->fk_company_id = $fk_company_id;
        $Users->u_user_sallary = $u_user_sallary;
        $Users->u_sales_commission = $u_sales_commission;
        $Users->u_number_holidays = $u_number_holidays;
        $Users->u_attendance_code = $u_attendance_code;
        $Users->fk_warehouse_id = $fk_warehouse_id;
        $Users->u_cnss_number = $u_cnss_number;
        $Users->u_marital_status = $u_marital_status;
        $Users->u_number_of_dependencies = $u_number_of_dependencies;
        $Users->u_has_insurance = $u_has_insurance;
        $Users->u_hourly_rate = $u_hourly_rate;
        $Users->u_is_active = $u_is_active;
        $Users->save();

        // if we add new warehouse
         if ($user_id == null && ( $u_user_type == UserTypes::USER_TYPE_TECHNICIAN || $u_user_type == UserTypes::USER_TYPE_SALES )  ) {
          $warehouse_info = new WareHouses();
          $warehouse_info->w_warehouse_ref = $u_username;
          $warehouse_info->w_warehouse_name = $u_fullname;
          $warehouse_info->w_warehouse_adddress = $u_address;
          $warehouse_info->w_owner_id = session('user_id');
          $warehouse_info->w_linked_to = $Users->id;
          $warehouse_info->w_warehouse_status = 1;
          $warehouse_info->save();
        }

         $payroll_paymentmethod = new PayrollsPaymentMethods();
         if($pm_id != null && $pm_id > 0)
         {
             $payroll_paymentmethod = PayrollsPaymentMethods::find($pm_id);
         }

        $payroll_paymentmethod->pm_company_id = $fk_company_id;
        $payroll_paymentmethod->pm_employee_id = $Users->id;
        $payroll_paymentmethod->save();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }

    /**
     * Set the is_deleted flag to 1 in the user table
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteRecord(Request $request) {
        $user_id = $request->input("user_id");

        $Users = Users::find($user_id);
        $Users->u_is_deleted = 1;
        $Users->u_deleted_by = session('user_id');
        $Users->save();

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }



}
