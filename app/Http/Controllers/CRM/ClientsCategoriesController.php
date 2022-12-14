<?php
/***********************************************************
ProductCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
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
use App\models\CRM\CRMClientCategories;
use App\Library\ClientsCategoriesManager;



class ClientsCategoriesController extends Controller
{

    /**
     * Page to control Client Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('accounts.categories',$data);
    }
    
    
    /**
     * Display list of Client categories
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
            
        $client_categories_count = CRMClientCategories::whereCcIsDeleted(0)->count();
        
        
        $total_pages = ceil( $client_categories_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $client_categories = CRMClientCategories::whereCcIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
        
        $client_categories_array   = array();
        $lst_client_categories     = CRMClientCategories::whereCcIsDeleted(0)->get();
        foreach ( $lst_client_categories as $key => $cc_info ) 
        {
            $client_categories_array[ $cc_info->cc_id ] =  $cc_info->cc_category_name;
        } 
        $data = array(
            "client_categories" => $client_categories,
            "client_categories_array" => $client_categories_array
        );
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("accounts.listcategories",$data)->render();
        
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
        
        $lst_client_categories     = CRMClientCategories::whereCcIsDeleted(0)->get();
        
        $data = array(
            "lst_client_categories" => $lst_client_categories,
        );
        return view('accounts.addcategory',$data);
    }
    
    
    /**
     * Save Client Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveClientCategoryInfo(Request $request)
    {
        $cc_id                      = $request->input('cc_id');
        $fk_cc_id                   = $request->input('fk_cc_id');
        $cc_category_ref            = $request->input('cc_category_ref');
        $cc_category_name           = $request->input('cc_category_name');
        $cc_category_description    = $request->input('cc_category_description');
        
        $result_array = array();
 
        $ClientCategoriesManager  = new ClientsCategoriesManager();
        $cc_avatar_base_src     = "";
        $cc_avatar_file_name    = "";
        $cc_avatar_extension    = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $ClientCategoriesManager->UploadAvatarCategory(null);
            
            $cc_avatar_base_src      = $image_data['data']['cc_avatar_base_src'];
            $cc_avatar_file_name     = $image_data['data']['cc_avatar_file_name'];
            $cc_avatar_extension     = $image_data['data']['cc_avatar_extentions'];
            
        }
        
        $ClientCategories = new CRMClientCategories();
        if($cc_id != null)
        {
            $ClientCategories = CRMClientCategories::find($cc_id);
        }
         
        $ClientCategories->fk_cc_id                 = $fk_cc_id;
        $ClientCategories->cc_category_ref          = $cc_category_ref;
        $ClientCategories->cc_category_name         = $cc_category_name;
        $ClientCategories->cc_category_description  = $cc_category_description;
        
        if(strlen($cc_avatar_base_src) > 0)
        {
            $ClientCategories->cc_profile_base_src      = $cc_avatar_base_src;
            $ClientCategories->cc_profile_file_name     = $cc_avatar_file_name;
            $ClientCategories->cc_profile_extension     = $cc_avatar_extension;
        }
        
        $ClientCategories->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Client Category Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Client Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $cc_id
     */
    public function EditForm( $cc_id )
    {
        $client_categories        = CRMClientCategories::find($cc_id); 
        
        $lst_client_categories = CRMClientCategories::whereCcIsDeleted(0)->whereNotIn("cc_id",array($cc_id))->get();
        
        $data = array(
            "client_categories" => $client_categories,
            "lst_client_categories" => $lst_client_categories
        );
        return view('accounts.editcategory',$data);
    }
    
    
    /**
     * Delete Client category information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteClientCategoryInfo(Request $request)
    {
        
        $cc_id= $request->input('cc_id');
         
        $clients_category = CRMClientCategories::find( $cc_id);
        $clients_category->cc_is_deleted          = 1;
        $clients_category->cc_deleted_by          = Session('user_id');
        $clients_category->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}