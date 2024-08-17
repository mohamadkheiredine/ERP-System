<?php
/***********************************************************
CostCenterCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\CostCenter;

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
use App\models\CostCenter\Categories; 



class CostCenterCategoriesController extends Controller
{
    
  
    /**
     * Page to control Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('costcenters.categories',$data);
    }
    
    
    /**
     * Display list of Cost Center categories saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $page_number           = $request->input('page_number');
        $general_search        = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
        $categories_cond = Categories::whereCcaIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $categories_cond = $categories_cond->where('cca_category_name','LIKE','%' . $general_search . '%');
            $categories_cond = $categories_cond->orWhere('cca_description','LIKE','%' . $general_search . '%');
        }


        $categories_count = $categories_cond->count();


        $total_pages = ceil( $categories_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_costcemter_categories = $categories_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('cca_id', 'asc')->get();

        $data = array(
            "lst_costcemter_categories" => $lst_costcemter_categories
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("costcenters.lstcategories",$data)->render();

        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Cost Center category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    { 
        $lst_costcenter_categories = Categories::whereCcaIsDeleted(0)->get();
        $data = array(
            "lst_costcenter_categories" => $lst_costcenter_categories
        );
        return view('costcenters.addcategory',$data);
    }
    
    
    /**
     * Save Cost Center Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveCostCenterCategoryInfo(Request $request)
    {
        $cca_id                             = $request->input('cca_id');
        $fk_cca_id                          = $request->input('fk_cca_id');
        $cca_category_name                  = $request->input('cca_category_name');
        $cca_description                    = $request->input('cca_description');
   
        
        $result_array = array();
 
        
        $category_info = new Categories();
        if( $cca_id != null )
        {
            $category_info = Categories::find($cca_id); 
        } 
         
        $category_info->fk_cca_id      = $fk_cca_id;
        $category_info->cca_category_name      = $cca_category_name;
        $category_info->cca_description      = $cca_description;
        
        $category_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Cost Center Category Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Cost Center category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $tc_id
     */
    public function EditForm( $tc_id )
    {
        $category_info          = Categories::find($tc_id);
        $lst_costcenter_categories    = Categories::whereCcaIsDeleted(0)->get();
        
        $data = array(
            "category_info" => $category_info,
            "lst_costcenter_categories" => $lst_costcenter_categories
        );
        return view('costcenters.editcategory',$data);
    }
    
    
    /**
     * Delete Cost Center Category information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteCostCenterCategoryInformation(Request $request)
    {
        $result_array = array();
        $cca_id= $request->input('cca_id');
         
        $category_info = Categories::find( $cca_id );
        $category_info->cca_is_deleted          = 1;
        $category_info->cca_deleted_by          = Session('user_id');
        $category_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}