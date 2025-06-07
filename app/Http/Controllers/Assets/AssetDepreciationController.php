<?php
/***********************************************************
 * AssetDepreciationController.php
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



class AssetDepreciationController extends Controller
{

    /**
     * Page to control Asset Depreciation Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_assets = Assets::whereAaIsDeleted(0)->get();

        $data = array(
            'lst_assets' => $lst_assets
        );
        return Response()->view('assets.depreciation',$data);
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
        $ad_asset_id           = $request->input('ad_asset_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $depreciation_cond = AssetDepreciation::whereAdIsDeleted(0);

        if($ad_asset_id > 0)
        {
            $depreciation_cond = $depreciation_cond->where('ad_asset_id', $ad_asset_id);
        }

        $asset_dep_count = $depreciation_cond->count();


        $total_pages = ceil( $asset_dep_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_asset_depreciations = $depreciation_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_asset_depreciations" => $lst_asset_depreciations
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("assets.listdepreciation",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new depreciation
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_assets = Assets::whereAaIsDeleted(0)->get();
        $data = array(
            'lst_assets' => $lst_assets
        );
        return view('assets.adddepreciation',$data);
    }


    /**
     * Save Asset Depreciation Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAssetDepreciationInfo(Request $request)
    {
        $ad_id                                  = $request->input('ad_id');
        $ad_asset_id                            = $request->input('ad_asset_id');
        $ad_year                                = $request->input('ad_year');
        $ad_depreciation_amount                 = $request->input('ad_depreciation_amount');
        $ad_new_value                           = $request->input('ad_new_value');

        $result_array = array();


        $asset_depreciation = new AssetDepreciation();
        if($ad_id != null)
        {
            $asset_depreciation = AssetDepreciation::find($ad_id);
        }

        $asset_depreciation->ad_asset_id                  = $ad_asset_id;
        $asset_depreciation->ad_year                      = $ad_year;
        $asset_depreciation->ad_depreciation_amount       = $ad_depreciation_amount;
        $asset_depreciation->ad_new_value                 = $ad_new_value;

        $asset_depreciation->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Asset Depreciation Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Asset Depreciation Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $ad_id
     */
    public function EditForm( $ad_id )
    {
        $asset_depreciation = AssetDepreciation::find($ad_id);
        $lst_assets = Assets::whereAaIsDeleted(0)->get();

        $data = array(
            "asset_depreciation" => $asset_depreciation,
            "lst_assets" => $lst_assets
        );
        return view('assets.editdepreciation',$data);
    }


    /**
     * Delete Asset depreciation information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAssetDepreciationInfo(Request $request)
    {

        $ad_id = $request->input('ad_id');

        $asset_depreciation = AssetDepreciation::find( $ad_id);
        $asset_depreciation->ad_is_deleted          = 1;
        $asset_depreciation->ad_deleted_by          = Session('user_id');
        $asset_depreciation->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
