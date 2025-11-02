<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouseZones;
use App\models\Sales\StoreEmployees;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
use App\models\System\Units;
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
use App\models\Sales\Orders;
use App\models\Users\Users;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use App\library\OrdersManager;
use App\models\Sales\OrderProducts;
use App\models\Inventory\Products;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\CurrencyExchangeRates;
use App\models\Inventory\Stocks;
use App\models\Billing\Invoices;
use App\library\AccountingManager;
use App\models\Billing\InvoiceProducts;
use App\models\Billing\PaymentTypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Inventory\Vendors;
use Milon\Barcode\DNS1D;
use App\models\Inventory\StockIds;
use App\models\System\Companies;

class StoresController extends Controller
{
    /**
     * Page to control SRM Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {


        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies
        );
        return Response()->view('stores.stores',$data);
    }


    /**
     * Display list of Stores saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $stores_cond = Stores::wherePsIsDeleted(0);

        if( strlen($general_search)  > 0)
        {
            $stores_cond = $stores_cond->where('ps_store_name','LIKE','%' . $general_search . '%');
            $stores_cond = $stores_cond->orWhere('ps_location','LIKE','%' . $general_search . '%');
        }


        $stores_count = $stores_cond->count();


        $total_pages = ceil( $stores_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $list_stores = $stores_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "lst_stores" => $list_stores,
        );

        $result_array = array();
        $result_array['display'] = view("stores.liststores",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Store
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {


        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_managers = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            "lst_managers" => $lst_managers,
            "lst_warehouses" => $lst_warehouses,
            "lst_companies" => $lst_companies,
        );
        return view('stores.addstore',$data);
    }


    /**
     * Save Store Info
     * @author Moe mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveStoreInfo(Request $request)
    {
        $ps_id                          = $request->input('ps_id');
        $ps_company_id                  = $request->input('ps_company_id');
        $ps_store_name                  = $request->input('ps_store_name');
        $ps_location                    = $request->input('ps_location');
        $ps_manager_id                  = $request->input('ps_manager_id');
        $ps_employees_id                  = $request->input('ps_employees_id');
        $ps_warehouses_id                  = $request->input('ps_warehouses_id');
        $ps_online_store                   = $request->has('ps_online_store') ? 1 : 0;
        $ps_is_active                   = $request->has('ps_is_active') ? 1 : 0;

        $result_array = array();


        $store_info = new Stores();
        if($ps_id != null)
        {
            $store_info= Stores::find($ps_id);
        }


        $store_info->ps_company_id          = $ps_company_id;
        $store_info->ps_store_name          = $ps_store_name;
        $store_info->ps_location            = $ps_location;
        $store_info->ps_manager_id          = $ps_manager_id;
        $store_info->ps_is_active           = $ps_is_active;
        $store_info->ps_online_store        = $ps_online_store;

        $store_info->save();

        $ps_id = $store_info->ps_id;

        $delete = StoreWarehouses::whereSwIsDeleted(0)->whereSwStoreId($ps_id)->delete();

        foreach ($ps_warehouses_id as $index => $w_id)
        {
            $store_warehouses = new StoreWarehouses();
            $store_warehouses->sw_company_id = $ps_company_id;
            $store_warehouses->sw_store_id = $ps_id;
            $store_warehouses->sw_warehouse_id = $w_id;
            $store_warehouses->save();
        }


        $delete = StoreEmployees::whereSeStoreId($ps_id)->delete();

        foreach ($ps_employees_id as $index => $u_id)
        {
            $store_employees = new StoreEmployees();
            $store_employees->se_store_id = $ps_id;
            $store_employees->se_company_id = $ps_company_id;
            $store_employees->se_employee_id = $u_id;
            $store_employees->save();
        }


        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Store information Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $ps_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ps_id )
    {
        $store_info = Stores::find($ps_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_managers = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $store_warehouses = StoreWarehouses::whereSwIsDeleted(0)->whereSwStoreId($ps_id)->get();
        $warehouse_ids = array();
        foreach ($store_warehouses as $index => $warehouse) {
            $warehouse_ids[] = $warehouse->sw_warehouse_id;
        }

        $store_employees = StoreEmployees::whereSeStoreId($ps_id)->get();
        $employees_ids = array();
        foreach ($store_employees as $index => $employee) {
            $employees_ids[] = $employee->se_employee_id;
        }

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_managers" => $lst_managers,
            "lst_warehouses" => $lst_warehouses,
            "warehouse_ids" => $warehouse_ids,
            "employees_ids" => $employees_ids,
            "store_employees" => $store_employees,
            "store_info" => $store_info,
        );
        return view('stores.editstore',$data);
    }


    /**
     * get list of allowed and denied Store Warehouses
     *
     * @author Moe Mantach
     * @acess public
     * @param Request $request
     * @return void
     */
    public function GetListWarehouses(Request $request)
    {

    }


    /**
     * Delete Store from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteStoreInfo(Request $request)
    {

        $ps_id = $request->input('ps_id');

        $store_info = Stores::find($ps_id);
        $store_info->ps_is_deleted          = 1;
        $store_info->ps_deleted_by          = Session('user_id');
        $store_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
