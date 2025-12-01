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
use App\models\Inventory\Products;
use App\models\Inventory\WareHouses;
use App\models\PMP\ProjectRoles;
use App\models\Production\FarmCycles;
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
        $data = array();
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
        $result_array['display'] = view("farms.listfarms",$data)->render();

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

        $default_company_id = session('default_company_id');
        $lst_users = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->get();
        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();
        $lst_products   = Products::wherePProductIsDeleted(0)->get();

        $data = array(
            "lst_users" => $lst_users,
            "lst_warehouses" => $lst_warehouses,
            "lst_products" => $lst_products,
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
        $fc_id                          = $request->input('fc_id');
        $fc_assign_to                          = $request->input('fc_assign_to');
        $fc_warehouse_id                          = $request->input('fc_warehouse_id');
        $fc_product_id                          = $request->input('fc_product_id');
        $fc_code                          = $request->input('fc_code');
        $fc_farm_name                          = $request->input('fc_farm_name');
        $fc_bird_type                          = $request->input('fc_bird_type');
        $fc_birds_start                          = $request->input('fc_birds_start');
        $fc_start_date                          = $request->input('fc_start_date');
        $fc_end_date                          = $request->input('fc_end_date');
        $fc_notes                          = $request->input('fc_notes');
        $fc_is_closed                          = $request->has('fc_is_closed') ? 1 : 0;

        $result_array = array();


        $farm_cycle = new FarmCycles();
        if( $fc_id != null )
        {
            $farm_cycle = FarmCycles::find($fc_id);
        }

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

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Farm Cycle Information Has been saved';

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
        $lst_products   = Products::wherePProductIsDeleted(0)->get();
        $cycle_info = FarmCycles::find($fc_id);

        $data = array(
            "lst_users" => $lst_users,
            "lst_warehouses" => $lst_warehouses,
            "lst_products" => $lst_products,
            "cycle_info" => $cycle_info,
        );
        return view('farms.editcycle',$data);
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
