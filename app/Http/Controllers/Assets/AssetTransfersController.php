<?php
/***********************************************************
 * AssetTransfersController.php
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
use App\models\Assets\AssetDepreciation;
use App\models\Assets\AssetLocations;
use App\models\Assets\Assets;
use App\models\Assets\AssetsTransfers;
use App\models\System\Departments;
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



class AssetTransfersController extends Controller
{

    /**
     * Page to control Asset Transfers Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_asset_locations = AssetLocations::whereIlIsDeleted(0)->get();
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
        $lst_assets = Assets::whereAaIsDeleted(0)->get();

        $data = array(
            'lst_asset_locations' => $lst_asset_locations,
            'lst_assets' => $lst_assets,
            'lst_departments' => $lst_departments
        );
        return Response()->view('assets.transfers',$data);
    }


    /**
     * Display list of Asset
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $at_asset_id           = $request->input('at_asset_id');
        $at_department_id           = $request->input('at_department_id');
        $at_location_id           = $request->input('at_location_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $transfers_cond = AssetsTransfers::whereAtIsDeleted(0);

        if($at_asset_id > 0)
        {
            $transfers_cond = $transfers_cond->where('at_asset_id', $at_asset_id);
        }

        if($at_department_id > 0)
        {
            $transfers_cond = $transfers_cond->where('at_from_department_id', $at_department_id);
            $transfers_cond = $transfers_cond->orWhere('at_to_department_id', $at_department_id);
        }


        if($at_location_id > 0)
        {
            $transfers_cond = $transfers_cond->where('at_from_location_id', $at_location_id);
            $transfers_cond = $transfers_cond->orWhere('at_to_location_id', $at_location_id);
        }

        $asset_transfers_count = $transfers_cond->count();


        $total_pages = ceil( $asset_transfers_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_asset_transfers = $transfers_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_asset_transfers" => $lst_asset_transfers
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("assets.listtransfers",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Transfer
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_asset_locations = AssetLocations::whereIlIsDeleted(0)->get();
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
        $lst_assets = Assets::whereAaIsDeleted(0)->get();

        $data = array(
            'lst_assets' => $lst_assets,
            "lst_asset_locations" => $lst_asset_locations,
            "lst_departments" => $lst_departments
        );
        return view('assets.addtransfer',$data);
    }


    /**
     * Save Asset Transfer Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAssetTransferInfo(Request $request)
    {
        $at_id                          = $request->input('at_id');
        $at_asset_id                    = $request->input('at_asset_id');
        $at_from_location_id            = $request->input('at_from_location_id');
        $at_to_location_id              = $request->input('at_to_location_id');
        $at_from_department_id          = $request->input('at_from_department_id');
        $at_to_department_id            = $request->input('at_to_department_id');
        $at_transfer_date               = $request->input('at_transfer_date');
        $at_transfer_reason             = $request->input('at_transfer_reason');
        $at_approved_by                 = $request->input('at_approved_by');
        $at_remarks                     = $request->input('at_remarks');

        $result_array = array();


        $asset_transfer = new AssetsTransfers();
        if($at_id != null)
        {
            $asset_transfer = AssetsTransfers::find($at_id);
        }

        $asset_transfer->at_asset_id                  = $at_asset_id;
        $asset_transfer->at_from_location_id                  = $at_from_location_id;
        $asset_transfer->at_to_location_id                  = $at_to_location_id;
        $asset_transfer->at_from_department_id                  = $at_from_department_id;
        $asset_transfer->at_to_department_id                  = $at_to_department_id;
        $asset_transfer->at_transfer_date                  = $at_transfer_date;
        $asset_transfer->at_transfer_reason                  = $at_transfer_reason;
        $asset_transfer->at_approved_by                  = $at_approved_by;
        $asset_transfer->at_remarks                  = $at_remarks;
        $asset_transfer->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Asset Transfer Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Asset Transfer Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $ad_id
     */
    public function EditForm( $at_id )
    {
        $asset_transfer = AssetsTransfers::find($at_id);
        $lst_asset_locations = AssetLocations::whereIlIsDeleted(0)->get();
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
        $lst_assets = Assets::whereAaIsDeleted(0)->get();

        $data = array(
            "asset_transfer" => $asset_transfer,
            'lst_assets' => $lst_assets,
            "lst_asset_locations" => $lst_asset_locations,
            "lst_departments" => $lst_departments
        );
        return view('assets.edittransfer',$data);
    }


    /**
     * Delete Asset Transfer information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAssetTransferInfo(Request $request)
    {

        $at_id = $request->input('at_id');

        $asset_transformation = AssetsTransfers::find( $at_id );
        $asset_transformation->at_is_deleted          = 1;
        $asset_transformation->at_deleted_by          = Session('user_id');
        $asset_transformation->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
