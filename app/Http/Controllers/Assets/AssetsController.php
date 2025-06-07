<?php
/***********************************************************
 * AssetsController.php
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
use App\models\Accounting\ChartAccounts;
use App\models\Assets\AssetCategories;
use App\models\Assets\AssetLocations;
use App\models\Assets\Assets;
use App\models\System\Currency;
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



class AssetsController extends Controller
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

        $lst_categories = AssetCategories::whereAcIsDeleted(0)->get();
        $lst_locations = AssetLocations::whereIlIsDeleted(0)->get();

        $data = array(
            'lst_categories' => $lst_categories,
            'lst_locations' => $lst_locations
        );
        return Response()->view('assets.assets',$data);
    }


    /**
     * Display list of Assets
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $aa_category_id        = $request->input('aa_category_id');
        $aa_location           = $request->input('aa_location');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $assets_cond = Assets::whereAaIsDeleted(0);
        if(strlen($aa_category_id) >  0)
            $assets_cond = $assets_cond->whereAaCategoryId($aa_category_id);

        if(strlen($aa_location) >  0)
            $assets_cond = $assets_cond->whereAaLocation($aa_location);

        $assets_count = $assets_cond->count();


        $total_pages = ceil( $assets_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_assets = $assets_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_assets" => $lst_assets
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("assets.displaylist",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Asset
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_categories = AssetCategories::whereAcIsDeleted(0)->get();
        $lst_locations = AssetLocations::whereIlIsDeleted(0)->get();
        $lst_accounts = ChartAccounts::all();
        $lst_currencies = Currency::all();

        $data = array(
            "lst_categories" => $lst_categories,
            "lst_locations" => $lst_locations,
            "lst_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        return view('assets.addform',$data);
    }


    /**
     * Save Asset Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAssetInfo(Request $request)
    {
        $aa_id                                          = $request->input('aa_id');
        $aa_asset_name                                  = $request->input('aa_asset_name');
        $aa_asset_description                           = $request->input('aa_asset_description');
        $aa_category_id                                 = $request->input('aa_category_id');
        $aa_purchase_date                               = $request->input('aa_purchase_date');
        $aa_purchase_price                              = $request->input('aa_purchase_price');
        $aa_currency_id                                 = $request->input('aa_currency_id');
        $aa_depreciation_rate                           = $request->input('aa_depreciation_rate');
        $aa_current_value                               = $request->input('aa_current_value');
        $aa_location                                    = $request->input('aa_location');
        $aa_status                                      = $request->input('aa_status');
        $aa_account_id                                  = $request->input('aa_account_id');

        $result_array = array();


        $asset_info = new Assets();
        if($aa_id != null)
        {
            $asset_info = AssetCategories::find($aa_id);
        }

        $asset_info->aa_asset_name                        = $aa_asset_name;
        $asset_info->aa_asset_description                 = $aa_asset_description;
        $asset_info->aa_category_id                 = $aa_category_id;
        $asset_info->aa_purchase_date                 = $aa_purchase_date;
        $asset_info->aa_purchase_price                 = $aa_purchase_price;
        $asset_info->aa_currency_id                 = $aa_currency_id;
        $asset_info->aa_depreciation_rate                 = $aa_depreciation_rate;
        $asset_info->aa_current_value                 = $aa_current_value;
        $asset_info->aa_location                 = $aa_location;
        $asset_info->aa_status                 = $aa_status;
        $asset_info->aa_account_id                 = $aa_account_id;

        $asset_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Asset Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Asset Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $aa_id
     */
    public function EditForm( $aa_id )
    {
        $asset_info = Assets::find($aa_id);
        $lst_categories = AssetCategories::whereAcIsDeleted(0)->get();
        $lst_locations = AssetLocations::whereIlIsDeleted(0)->get();
        $lst_accounts = ChartAccounts::all();
        $lst_currencies = Currency::all();

        $data = array(
            "lst_categories" => $lst_categories,
            "lst_locations" => $lst_locations,
            "lst_accounts" => $lst_accounts,
            "asset_info" => $asset_info,
            "lst_currencies" => $lst_currencies
        );
        return view('assets.editform',$data);
    }


    /**
     * Delete Asset information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAssetInfo(Request $request)
    {

        $aa_id = $request->input('aa_id');

        $asset_info = Assets::find($aa_id);
        $asset_info->aa_is_deleted          = 1;
        $asset_info->aa_deleted_by          = Session('user_id');
        $asset_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
