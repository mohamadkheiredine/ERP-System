<?php
/***********************************************************
 * FarmCyclesController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/29/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\library\AccountingManager;
use App\library\FarmCycleManager;
use App\models\Accounting\ChartAccounts;
use App\models\Billing\PaymentVouchers;
use App\models\Expenses\ExpensesCategories;
use App\models\Inventory\Products;
use App\models\Inventory\WareHouses;
use App\models\PMP\ProjectRoles;
use App\models\Production\FarmCycleDays;
use App\models\Production\FarmCycleExpenses;
use App\models\Production\FarmCycles;
use App\models\System\Companies;
use App\models\System\Currency;
use App\models\System\Units;
use App\models\Users\Users;
use App\models\Users\UserTypes;
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
use App\models\Sales\OrderStatus;
use App\models\Production\PlanStatus;



class FarmCyclesController extends Controller
{

    /**
     * Page to control Farm Cycle
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_companies = Companies::whereCdIsDeleted(0)->get();

        $data = array(
            'lst_companies' => $lst_companies,
        );
        return Response()->view('farms.farmscycles',$data);
    }


    /**
     * Display list of Farm Cycles saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {

        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        $result_array           = array();

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $farm_cycles_cond = FarmCycles::whereFcIsDeleted(0);

        if(strlen($search_query) > 0)
        {
            $farm_cycles_cond = $farm_cycles_cond->where('fc_farm_name' , 'LIKE' , '%' . $search_query . '%');
            $farm_cycles_cond = $farm_cycles_cond->orWhere('fc_bird_type' , 'LIKE' , '%' . $search_query . '%');
            $farm_cycles_cond = $farm_cycles_cond->orWhere('fc_notes' , 'LIKE' , '%' . $search_query . '%');
        }

        $farm_cycles_count = $farm_cycles_cond->count();


        $total_pages = ceil( $farm_cycles_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_farm_cycles = $farm_cycles_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('fc_farm_name', 'ASC')->get();



        $data = array(
            "lst_farm_cycles" => $lst_farm_cycles,
        );
        $result_array['display'] = view("farms.listcycles",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Farm Cycle
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $cycle_manager = new FarmCycleManager();
        $cycle_code = $cycle_manager->GenerateCycleCode();
        $cycleDays =[];
        $default_company_id = session('default_company_id');
        $lst_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->get();
        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();
        $lst_bird_types   = Products::wherePProductIsDeleted(0)->wherePProductType(1)->get();
        $lst_final_products   = Products::wherePProductIsDeleted(0)->wherePProductType(22)->get();
        $lst_sys_units      = Units::whereSuIsDeleted(0)->get();
        $cycle = new FarmCycles();
        $data = array(
            "lst_users" => $lst_users,
            "cycle" => $cycle,
            "cycle_code" => $cycle_code,
            "lst_warehouses" => $lst_warehouses,
            "lst_sys_units" => $lst_sys_units,
            "lst_bird_types" => $lst_bird_types,
            "lst_final_products" => $lst_final_products,
            "cycleDays" => $cycleDays,
        );
        return view('farms.addcycle',$data);
    }


    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $default_company_id = session('default_company_id');
        $fc_id                          = $request->input('fc_id');
        $fc_assign_to                          = $request->input('fc_assign_to');
        $fc_warehouse_id                          = $request->input('fc_warehouse_id');
        $fc_product_id                          = $request->input('fc_product_id');
        $fc_code                          = $request->input('fc_code');
        $fc_farm_name                          = $request->input('fc_farm_name');
        $fc_bird_type                          = $request->input('fc_bird_type');
        $fc_birds_start                          = $request->input('fc_birds_start');
        $fc_start_date                          = $request->input('fc_start_date');
        $fc_end_date                            = $request->input('fc_end_date');
        $fc_notes                               = $request->input('fc_notes');
        $days                                   = $request->input('days');
        $fc_is_closed                          = $request->has('fc_is_closed') ? 1 : 0;

        $result_array = array();


        $farm_cycle = new FarmCycles();
        if( $fc_id != null )
        {
            $farm_cycle = FarmCycles::find($fc_id);
        }

        $farm_cycle->fc_company_id            = $default_company_id;
        $farm_cycle->fc_assign_to            = $fc_assign_to;
        $farm_cycle->fc_warehouse_id            = $fc_warehouse_id;
        $farm_cycle->fc_product_id            = $fc_product_id;
        $farm_cycle->fc_code            = $fc_code;
        $farm_cycle->fc_farm_name            = $fc_farm_name;
        $farm_cycle->fc_bird_type            = $fc_bird_type;
        $farm_cycle->fc_birds_start            = $fc_birds_start;
        $farm_cycle->fc_start_date            = $fc_start_date;
        $farm_cycle->fc_end_date            = $fc_end_date;
        $farm_cycle->fc_notes            = $fc_notes;
        $farm_cycle->fc_is_closed            = $fc_is_closed;
        $farm_cycle->save();


        foreach($days as $index => $day)
        {
            $id = $day['id'];
            $cycle_day = new FarmCycleDays();
            if( $id != null )
                $cycle_day = FarmCycleDays::find($id);

            $cycle_day->fcd_cycle_id = $fc_id;
            $cycle_day->fcd_date = date("Y-m-d",strtotime($day['date']));
            $cycle_day->fcd_age_days = $day['age_days'];
            $cycle_day->fcd_feed_type = $day['feed_type'];
            $cycle_day->fcd_feed_received_kg = $day['feed_received_kg'];
            $cycle_day->fcd_feed_intake_day_kg = $day['feed_intake_day_kg'];
            $cycle_day->fcd_feed_in_stock_kg = $day['feed_in_stock_kg'];
            $cycle_day->fcd_mortality = $day['mortality'];
            $cycle_day->fcd_closing_birds = $day['closing_birds'];
            $cycle_day->fcd_body_weight_g = $day['body_weight_g'];
            $cycle_day->fcd_daily_intake_g_per_bird = $day['daily_intake_g_per_bird'];
            $cycle_day->fcd_cumulative_intake_g_per_bird = $day['cumulative_intake_g_per_bird'];
            $cycle_day->fcd_fcr = $day['fcr'];
            $cycle_day->fcd_medicine = $day['medicine'];
            $cycle_day->fcd_water_liters = $day['water_liters'];
            $cycle_day->fcd_diesel_liters = $day['diesel_liters'];
            $cycle_day->save();

        }


        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Farm Cycle Information Has been saved';

        return Response()->json($result_array);
    }


    /**
     * Get List of Expenses paied for this cycle
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return void
     */
    public function GetListOfExpenses(Request $request)
    {
        $fc_id = $request->input('fc_id');

        $lst_cycle_expenses = FarmCycleExpenses::whereCeCycleId($fc_id)->get();
        $result_array = array();
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = '';
        $data = array(
            'lst_cycle_expenses' => $lst_cycle_expenses
        );
        $result_array['display'] = view('farms.listexpenses',$data)->render();

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $fc_id )
    {

        $default_company_id = session('default_company_id');
        $lst_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->get();
        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();
        $lst_bird_types   = Products::wherePProductIsDeleted(0)->wherePProductType(1)->get();
        $lst_final_products   = Products::wherePProductIsDeleted(0)->wherePProductType(22)->get();
        $lst_sys_units      = Units::whereSuIsDeleted(0)->get();
        $lst_chart_accounts      = ChartAccounts::whereAaIsDeleted(0)->get();
        $lst_currencies = Currency::all();


        $lst_expenses_categories = ExpensesCategories::whereEcIsDeleted(0)->get();

        $cycle_info = FarmCycles::find($fc_id);
        $cycleDays = FarmCycleDays::where('fcd_cycle_id', $fc_id)
            ->orderBy('fcd_id','ASC')
            ->get();
        $data = array(
            "lst_users" => $lst_users,
            "lst_warehouses" => $lst_warehouses,
            "lst_bird_types" => $lst_bird_types,
            "lst_final_products" => $lst_final_products,
            "lst_sys_units" => $lst_sys_units,
            "cycle" => $cycle_info,
            "lst_expenses_categories" => $lst_expenses_categories,
            "lst_chart_accounts" => $lst_chart_accounts,
            "lst_currencies" => $lst_currencies,
            "cycleDays" => $cycleDays,
        );
        return view('farms.editcycle',$data);
    }


    /**
     *
     * Save Cycle Expenses
     * @param Request $request
     * @return void
     */
    public function SaveCycleExpenses(Request $request)
    {
        $fc_id = $request->input('fc_id');
        $ce_category_id = $request->input('ce_category_id');
        $ce_expense_price = $request->input('ce_expense_price');
        $ce_currency_id = $request->input('ce_currency_id');
        $ce_source_account = $request->input('ce_source_account');
        $default_company_id = session('default_company_id');
        $user_id = session('user_id');

        $cycle_info = FarmCycles::find($fc_id);
        $account_management = new AccountingManager();
        $voucher_code       = $account_management->GetVoucherCode();

        $result_array = array();

        $expense_category = ExpensesCategories::find($ce_category_id);


        $payment_voucher = new PaymentVouchers();
        $payment_voucher->pv_company_id = $default_company_id;
        $payment_voucher->pv_user_id = $user_id;
        $payment_voucher->pv_code = $voucher_code;
        $payment_voucher->pv_account_payable = $ce_source_account;
        $payment_voucher->pv_creation_date = date("Y-m-d");
        $payment_voucher->pv_payment_amount = $ce_expense_price;
        $payment_voucher->pv_currency_id = $ce_currency_id;
        $payment_voucher->pv_account_receivable = $expense_category->ec_gl_account_id;
        $payment_voucher->pv_voucher_label = " New Payment Voucher " . $voucher_code . " Created by " . session('user_fullname');
        $payment_voucher->pv_voucher_description = " New Payment Voucher " . $voucher_code . " Created by " . session('user_fullname') . " on " . date('d-m-Y H:i:s') . " For Cycle " .$cycle_info->fc_farm_name;
        $payment_voucher->save();

        $cycle_expense = new FarmCycleExpenses();
        $cycle_expense->ce_company_id = $default_company_id;
        $cycle_expense->ce_cycle_id = $fc_id;
        $cycle_expense->ce_expense_category_id = $ce_category_id;
        $cycle_expense->ce_voucher_amount = $ce_expense_price;
        $cycle_expense->ce_voucher_currency = $ce_currency_id;
        $cycle_expense->ce_created_by = $user_id;
        $cycle_expense->ce_creation_date = date('Y-m-d');
        $cycle_expense->ce_voucher_id = $payment_voucher->pv_id;
        $cycle_expense->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg']  = 'Operation Completed Successfully';

        return Response()->json($result_array);
    }


    /**
     * Delete Product Roles from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteFarmCycle(Request $request)
    {

        $fc_id= $request->input('fc_id');

        $farm_cycles = FarmCycles::find( $fc_id );
        $farm_cycles->fc_is_deleted   = 1;
        $farm_cycles->fc_deleted_by   = Session('user_id');
        $farm_cycles->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
