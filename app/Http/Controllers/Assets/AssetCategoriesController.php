<?php
/***********************************************************
 * AssetCategoriesController.php
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
use App\models\Assets\AssetCategories;
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



class AssetCategoriesController extends Controller
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
        return Response()->view('assets.categories',$data);
    }


    /**
     * Display list of Asset Categories
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

        $assets_categories_cond = AssetCategories::whereAcIsDeleted(0);


        $asset_categories_count = $assets_categories_cond->count();


        $total_pages = ceil( $asset_categories_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_asset_categories = $assets_categories_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = array(
            "lst_asset_categories" => $lst_asset_categories
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("assets.listcategories",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $data = array(
        );
        return view('assets.addcategory',$data);
    }


    /**
     * Save Asset Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAssetCategoryInfo(Request $request)
    {
        $ac_id                          = $request->input('ac_id');
        $ac_category_name              = $request->input('ac_category_name');
        $ac_description                 = $request->input('ac_description');

        $result_array = array();


        $asset_categories = new AssetCategories();
        if($ac_id != null)
        {
            $asset_categories = AssetCategories::find($ac_id);
        }

        $asset_categories->ac_category_name                 = $ac_category_name;
        $asset_categories->ac_description                 = $ac_description;

        $asset_categories->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Asset Categories Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Asset Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $il_id
     */
    public function EditForm( $ac_id )
    {
        $asset_categories = AssetCategories::find($ac_id);

        $data = array(
            "asset_categories" => $asset_categories,
        );
        return view('assets.editcategory',$data);
    }


    /**
     * Delete Asset Category information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAssetCategoryInfo(Request $request)
    {

        $ac_id = $request->input('ac_id');

        $asset_categories = AssetCategories::find( $ac_id);
        $asset_categories->ac_is_deleted          = 1;
        $asset_categories->ac_deleted_by          = Session('user_id');
        $asset_categories->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
