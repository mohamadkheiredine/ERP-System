<?php
/***********************************************************
 * WarehouseZonesController.php
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
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseZones;
use App\models\System\Units;
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
use App\models\SRM\SupplierCategories;



class WarehouseZonesController extends Controller
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

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            'lst_warehouses' => $lst_warehouses,
        );
        return Response()->view('warehouses.zones.index',$data);
    }


    /**
     * Display list of Warehouse Zones saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $fk_warehouse_id        = $request->input('fk_warehouse_id');
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $zones_cond = WareHouseZones::whereWzIsDeleted(0);

        if($fk_warehouse_id > 0)
            $zones_cond = $zones_cond->whereFkWarehouseId($fk_warehouse_id);
        if( strlen($general_search)  > 0)
        {
            $zones_cond = $zones_cond->where('wz_zone_label','LIKE','%' . $general_search . '%');
            $zones_cond = $zones_cond->orWhere('wz_zone_description','LIKE','%' . $general_search . '%');
        }


        $zones_count = $zones_cond->count();


        $total_pages = ceil( $zones_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $list_zones = $zones_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "list_zones" => $list_zones,
        );

        $result_array = array();
        $result_array['display'] = view("warehouses.zones.displaylist",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Zone
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_units_weight = Units::whereSuUnityType("weight")->get();
        $lst_units_size   = Units::whereSuUnityType("size")->get();
        $lst_units_surface= Units::whereSuUnityType("surface")->get();

        $data = array(
            "lst_warehouses" => $lst_warehouses,
            "lst_units_weight" => $lst_units_weight,
            "lst_units_size" => $lst_units_size,
            "lst_units_surface" => $lst_units_surface,
        );
        return view('warehouses.zones.addform',$data);
    }


    /**
     * Save Warehouse Zone Info
     * @author Moe mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveWarehouseZoneInfo(Request $request)
    {
        $wz_id                  = $request->input('wz_id');
        $fk_warehouse_id        = $request->input('fk_warehouse_id');
        $wz_zone_label          = $request->input('wz_zone_label');
        $wz_zone_color          = $request->input('wz_zone_color');
        $wz_zone_description    = $request->input('wz_zone_description');
        $wz_zone_length         = $request->input('wz_zone_length');
        $wz_zone_length_unit    = $request->input('wz_zone_length_unit');
        $wz_zone_width          = $request->input('wz_zone_width');
        $wz_zone_width_unit     = $request->input('wz_zone_width_unit');
        $wz_zone_height         = $request->input('wz_zone_height');
        $wz_zone_height_unit    = $request->input('wz_zone_height_unit');
        $wz_zone_volume         = $request->input('wz_zone_volume');
        $wz_zone_volume_unit    = $request->input('wz_zone_volume_unit');

        $result_array = array();


        $warehouse_zone = new WareHouseZones();
        if($wz_id != null)
        {
            $warehouse_zone= WareHouseZones::find($wz_id);
        }


        $warehouse_zone->fk_warehouse_id          = $fk_warehouse_id;
        $warehouse_zone->wz_zone_label          = $wz_zone_label;
        $warehouse_zone->wz_zone_color        = $wz_zone_color;
        $warehouse_zone->wz_zone_description        = $wz_zone_description;
        $warehouse_zone->wz_zone_length        = $wz_zone_length;
        $warehouse_zone->wz_zone_length_unit        = $wz_zone_length_unit;
        $warehouse_zone->wz_zone_width        = $wz_zone_width;
        $warehouse_zone->wz_zone_width_unit        = $wz_zone_width_unit;
        $warehouse_zone->wz_zone_height        = $wz_zone_height;
        $warehouse_zone->wz_zone_height_unit        = $wz_zone_height_unit;
        $warehouse_zone->wz_zone_volume        = $wz_zone_volume;
        $warehouse_zone->wz_zone_volume_unit        = $wz_zone_volume_unit;



        $warehouse_zone->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Warehouse Zone Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $sc_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $wz_id )
    {
        $zone_info = WareHouseZones::find($wz_id);
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_units_weight = Units::whereSuUnityType("weight")->get();
        $lst_units_size   = Units::whereSuUnityType("size")->get();
        $lst_units_surface= Units::whereSuUnityType("surface")->get();

        $data = array(
            "lst_warehouses" => $lst_warehouses,
            "zone_info" => $zone_info,
            "lst_units_weight" => $lst_units_weight,
            "lst_units_size" => $lst_units_size,
            "lst_units_surface" => $lst_units_surface
        );
        return view('warehouses.zones.editform',$data);
    }


    /**
     * Delete Warehouse Zone from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteWarehouseZoneInfo(Request $request)
    {

        $wz_id = $request->input('wz_id');

        $zone_info = WareHouseZones::find( $wz_id );
        $zone_info->wz_is_deleted          = 1;
        $zone_info->wz_deleted_by          = Session('user_id');
        $zone_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
