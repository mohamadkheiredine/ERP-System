<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouseZones;
use App\models\Sales\StoreEmployees;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
use App\models\Sales\Terminals;
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

class TerminalsController extends Controller
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


        $lst_stores = Stores::wherePsIsDeleted(0)->get();
        $data = array(
            "lst_stores" => $lst_stores
        );
        return Response()->view('stores.terminals',$data);
    }


    /**
     * Display list of Terminals saved in the database
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
        $pt_store_id            = $request->input('pt_store_id');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $terminals_cond = Terminals::wherePtIsDeleted(0);

        if( strlen($general_search)  > 0)
        {
            $terminals_cond = $terminals_cond->where('pt_terminal_name','LIKE','%' . $general_search . '%');
            $terminals_cond = $terminals_cond->orWhere('pt_description','LIKE','%' . $general_search . '%');
        }


        $terminals_count = $terminals_cond->count();


        $total_pages = ceil( $terminals_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $list_terminals = $terminals_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "list_terminals" => $list_terminals,
        );

        $result_array = array();
        $result_array['display'] = view("stores.listterminals",$data)->render();
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
        $lst_stores = Stores::wherePsIsDeleted(0)->get();

        $data = array(
            "lst_managers" => $lst_managers,
            "lst_warehouses" => $lst_warehouses,
            "lst_stores" => $lst_stores,
            "lst_companies" => $lst_companies,
        );
        return view('stores.addterminal',$data);
    }


    /**
     * Save Store terminal Info
     * @author Moe mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveTerminalInfo(Request $request)
    {
        $pt_id                          = $request->input('pt_id');
        $pt_store_id                          = $request->input('pt_store_id');
        $pt_terminal_name                          = $request->input('pt_terminal_name');
        $pt_description                          = $request->input('pt_description');
        $pt_manager_id                          = $request->input('pt_manager_id');
        $pt_warehouse_id                          = $request->input('pt_warehouse_id');
        $pt_is_active                          = $request->has('pt_is_active') ? 1 : 0;

        $result_array = array();


        $terminal_info = new Terminals();
        if($pt_id != null)
        {
            $terminal_info= Terminals::find($pt_id);
        }


        $terminal_info->pt_store_id          = $pt_store_id;
        $terminal_info->pt_terminal_name          = $pt_terminal_name;
        $terminal_info->pt_description          = $pt_description;
        $terminal_info->pt_manager_id          = $pt_manager_id;
        $terminal_info->pt_warehouse_id          = $pt_warehouse_id;
        $terminal_info->pt_is_active          = $pt_is_active;

        $terminal_info->save();



        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Store Terminal Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pt_id )
    {
        $termianl_info = Terminals::find($pt_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_managers = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_stores = Stores::wherePsIsDeleted(0)->get();


        $data = array(
            "lst_companies" => $lst_companies,
            "lst_managers" => $lst_managers,
            "lst_stores" => $lst_stores,
            "lst_warehouses" => $lst_warehouses,
            "termianl_info" => $termianl_info
        );
        return view('stores.editterminal',$data);
    }




    /**
     * Delete Store Terminal from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteTerminalInfo(Request $request)
    {

        $pt_id = $request->input('pt_id');

        $terminal_info = Terminals::find($pt_id);
        $terminal_info->pt_is_deleted          = 1;
        $terminal_info->pt_deleted_by          = Session('user_id');
        $terminal_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
