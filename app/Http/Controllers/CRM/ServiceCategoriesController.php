<?php
/***********************************************************
ServiceCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
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
use App\models\CRM\CRMServiceCategories;
use App\Library\ServiceCategoriesManager;



class ServiceCategoriesController extends Controller
{
    
    
    /**
     * Page to control Service Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('services.categories',$data);
    }
    
    
    /**
     * Display list of Service categories
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
                
                $service_categories_count = CRMServiceCategories::whereScIsDeleted(0)->count();
                
                
                $total_pages = ceil( $service_categories_count /$nbr_rows_per_pages );
                $total_pages = intval($total_pages);
                
                $service_categories = CRMServiceCategories::whereScIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
                
                $service_categories_array   = array();
                $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
                foreach ( $lst_service_categories as $key => $sc_info )
                {
                    $service_categories_array[ $sc_info->sc_id ] =  $sc_info->sc_category_name;
                }
                $data = array(
                    "service_categories" => $service_categories,
                    "service_categories_array" => $service_categories_array
                );
                
                $result_array = array();
                
                $result_array['total_pages'] = $total_pages;
                $result_array['display'] = view("services.listcategories",$data)->render();
                
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
        
        $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
        
        $data = array(
            "lst_service_categories" => $lst_service_categories,
        );
        return view('services.addcategory',$data);
    }
    
    
    /**
     * Save Services Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveServiceCategoryInfo(Request $request)
    {
        $sc_id                      = $request->input('sc_id');
        $fk_category_id             = $request->input('fk_category_id');
        $sc_category_ref            = $request->input('sc_category_ref');
        $sc_category_name           = $request->input('sc_category_name');
        $sc_category_description    = $request->input('sc_category_description');
        
        $result_array = array();
        
        $ServiceCategoriesManager  = new ServiceCategoriesManager();
        $sc_avatar_base_src     = "";
        $sc_avatar_file_name    = "";
        $sc_avatar_extension    = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $ServiceCategoriesManager->UploadAvatarCategory($sc_id);
            
            $sc_avatar_base_src     = $image_data['data']['sc_avatar_base_src'];
            $sc_avatar_file_name    = $image_data['data']['sc_avatar_file_name'];
            $sc_avatar_extension    = $image_data['data']['sc_avatar_extentions'];
            
        }
        
        $ServiceCategories = new CRMServiceCategories();
        if($sc_id != null)
        {
            $ServiceCategories  = CRMServiceCategories::find($sc_id);
        }
        
        $ServiceCategories->fk_category_id           = $fk_category_id;
        $ServiceCategories->sc_category_ref          = $sc_category_ref;
        $ServiceCategories->sc_category_name         = $sc_category_name;
        $ServiceCategories->sc_category_description  = $sc_category_description;
        
        if(strlen($sc_avatar_base_src) > 0)
        {
            $ServiceCategories->sc_profile_base_src      = $sc_avatar_base_src;
            $ServiceCategories->sc_profile_file_name     = $sc_avatar_file_name;
            $ServiceCategories->sc_profile_extension     = $sc_avatar_extension;
        }
        
        $ServiceCategories->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Service Category Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Service Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $sc_id
     */
    public function EditForm( $sc_id )
    {
        $service_categories        = CRMServiceCategories::find($sc_id);
        
        $lst_service_categories = CRMServiceCategories::whereScIsDeleted(0)->whereNotIn("sc_id",array($sc_id))->get();
        
        $data = array(
            "service_categories" => $service_categories,
            "lst_service_categories" => $lst_service_categories
        );
        return view('services.editcategory',$data);
    }
    
    
    /**
     * Delete Service category information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteServiceCategoryInfo(Request $request)
    {
        
        $sc_id= $request->input('sc_id');
        
        $services_category = CRMServiceCategories::find( $sc_id);
        $services_category->sc_is_deleted          = 1;
        $services_category->sc_deleted_by          = Session('user_id');
        $services_category->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}