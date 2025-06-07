<?php
/***********************************************************
 * WarehouseFloorsController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/11/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\WareHouses;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouseFloors;
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseZones;
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



class WarehouseFloorsController extends Controller
{

    /**
     * Page to control Warehouse Floors Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_zones = WareHouseZones::whereWzIsDeleted(0)->get();


        $data = array(
            'lst_warehouses' => $lst_warehouses,
            'lst_zones' => $lst_zones
        );
        return Response()->view('warehouses.floors.index',$data);
    }


    /**
     * Display list of Floors saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {

        $fk_warehouse_id        = $request->input('fk_warehouse_id');
        $fk_zone_id             = $request->input('fk_zone_id');
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $floors_cond = WareHouseFloors::whereWfIsDeleted(0);

        if($fk_warehouse_id > 0)
            $floors_cond = $floors_cond->whereFkWarehouseId($fk_warehouse_id);
        if($fk_zone_id > 0)
            $floors_cond = $floors_cond->whereFkZoneId($fk_zone_id);
        if( strlen($general_search)  > 0)
        {
            $floors_cond = $floors_cond->where('wf_floor_title','LIKE','%' . $general_search . '%');
        }


        $floors_count = $floors_cond->count();


        $total_pages = ceil( $floors_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $list_floors = $floors_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "list_floors" => $list_floors,
        );

        $result_array = array();
        $result_array['display'] = view("warehouses.floors.displaylist",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new warehouse Floor
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_zones = WareHouseZones::whereWzIsDeleted(0)->get();

        $data = array(
            "lst_warehouses" => $lst_warehouses,
            "lst_zones" => $lst_zones,
        );
        return view('warehouses.floors.addform',$data);
    }


    /**
     * Save Warehouse Floors to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveWarehouseFloorInfo(Request $request)
    {
        $wf_id   = $request->input('wf_id');
        $fk_warehouse_id   = $request->input('fk_warehouse_id');
        $fk_zone_id   = $request->input('fk_zone_id');
        $wf_floor_title   = $request->input('wf_floor_title');

        $result_array = array();


        $floor_info = new WareHouseFloors();
        if( $wf_id != null )
        {
            $floor_info= WareHouseFloors::find($wf_id);
        }

        $floor_info->fk_warehouse_id           = $fk_warehouse_id;
        $floor_info->fk_zone_id           = $fk_zone_id;
        $floor_info->wf_floor_title           = $wf_floor_title;

        $floor_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Warehouse Floor Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Warehouse Floor Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $wf_id
     */
    public function EditForm( $wf_id )
    {

        $floor_info        = WareHouseFloors::find($wf_id);
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_zones = WareHouseZones::whereWzIsDeleted(0)->get();

        $data = array(
            "floor_info" => $floor_info,
            "lst_warehouses" => $lst_warehouses,
            "lst_zones" => $lst_zones,
        );
        return view('warehouses.floors.editform',$data);
    }


    /**
     * Delete warehouse Floor information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteWarehouseFloorInfo(Request $request)
    {

        $wf_id= $request->input('wf_id');

        $floor_info = WareHouseFloors::find( $wf_id);
        $floor_info->wf_is_deleted          = 1;
        $floor_info->wf_deleted_by          = Session('user_id');
        $floor_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
