<?php
/***********************************************************
 * AssetLocationsController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/1/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\models\Assets\AssetLocations;
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



class AssetLocationsController extends Controller
{

    /**
     * Page to control Asset locations Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('assets.locations',$data);
    }


    /**
     * Display list of Asset Locations
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $asset_locations_count = AssetLocations::whereIlIsDeleted(0)->count();


        $total_pages = ceil( $asset_locations_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_asset_locations = AssetLocations::whereIlIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_asset_locations" => $lst_asset_locations
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("assets.listlocations",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new location
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $data = array(
        );
        return view('assets.addlocation',$data);
    }


    /**
     * Save Asset Location Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAssetLocationInfo(Request $request)
    {
        $il_id                          = $request->input('il_id');
        $il_location_name               = $request->input('il_location_name');
        $il_description                 = $request->input('il_description');
        $il_address                     = $request->input('il_address');

        $result_array = array();


        $asset_locations = new AssetLocations();
        if($il_id != null)
        {
            $asset_locations = AssetLocations::find($il_id);
        }

        $asset_locations->il_location_name                 = $il_location_name;
        $asset_locations->il_description                 = $il_description;
        $asset_locations->il_address                 = $il_address;

        $asset_locations->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Asset Locations Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Asset Locations Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $il_id
     */
    public function EditForm( $il_id )
    {
        $asset_locations = AssetLocations::find($il_id);

        $data = array(
            "asset_locations" => $asset_locations,
        );
        return view('assets.editlocation',$data);
    }


    /**
     * Delete Asset Location information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAssetLocationInfo(Request $request)
    {

        $il_id = $request->input('il_id');

        $asset_locations = AssetLocations::find( $il_id);
        $asset_locations->il_is_deleted          = 1;
        $asset_locations->il_deleted_by          = Session('user_id');
        $asset_locations->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
