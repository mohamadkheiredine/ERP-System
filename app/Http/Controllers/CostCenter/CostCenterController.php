<?php
/***********************************************************
CostCenterController.php
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
use App\models\CostCenter\CostCenters;
use App\models\CostCenter\CostCenterTypes;
use App\models\CostCenter\CostCenterStatus;
use App\models\Users\Users;


class CostCenterController extends Controller
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
        
        $lst_categories = Categories::whereCcaIsDeleted(0)->get();
        $lst_types = CostCenterTypes::whereAtIsDeleted(0)->get();
        
        $data = array(
            "lst_categories" => $lst_categories,
            "lst_types" => $lst_types,
        );
        return Response()->view('costcenters.index',$data);
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
   
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $ac_category_id         = $request->input('ac_category_id');
        $ac_type_id             = $request->input('ac_type_id');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
        $costcenter_cond = CostCenters::whereAcIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $costcenter_cond = $costcenter_cond->where('ac_cost_center_label','LIKE','%' . $general_search . '%');
            $costcenter_cond = $costcenter_cond->orWhere('ac_cost_center_description','LIKE','%' . $general_search . '%');
        }


        $costcenter_count = $costcenter_cond->count();


        $total_pages = ceil( $costcenter_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_costcenters_info = $costcenter_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('ac_id', 'asc')->get();

        $data = array(
            "lst_costcenters_info" => $lst_costcenters_info
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("costcenters.lstcostcenters",$data)->render();

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
        $lst_categories = Categories::whereCcaIsDeleted(0)->get();
        $lst_types = CostCenterTypes::whereAtIsDeleted(0)->get();
        $lst_statuses = CostCenterStatus::whereCsIsDeleted(0)->get();
        $lst_costcenters = CostCenters::whereAcIsDeleted(0)->get();
        $lst_managers = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        
        
        $data = array(
            "lst_categories" => $lst_categories,
            "lst_types" => $lst_types,
            "lst_statuses" => $lst_statuses,
            "lst_costcenters" => $lst_costcenters,
            "lst_managers" => $lst_managers,
        );
        return view('costcenters.addform',$data);
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
    public function SaveCostCenterInfo(Request $request)
    {
        $ac_id                          = $request->input('ac_id');
        $ac_cost_center_code            = "";
        $ac_cost_center_label           = $request->input('ac_cost_center_label');
        $ac_cost_center_description     = $request->input('ac_cost_center_description');
        $ac_category_id                 = $request->input('ac_category_id');
        $ac_type_id                     = $request->input('ac_type_id');
        $ac_parent_cost_center_id       = $request->input('ac_parent_cost_center_id');
        $ac_manager_id                  = $request->input('ac_manager_id');
        $ac_status_id                   = $request->input('ac_status_id');
   
        
        $result_array = array();
 
        
        $costcenter_info = new CostCenters();
        if( $ac_id != null )
        {
            $costcenter_info = CostCenters::find($ac_id);
            $costcenter_info->ac_last_update_date = date('Y-m-d'); 
        }
        else
        {
            $costcenter_info->ac_creation_date = date('Y-m-d');
            $costcenter_info->ac_created_by = session('user_id');
        }
         
        $costcenter_info->ac_cost_center_code               = $ac_cost_center_code;
        $costcenter_info->ac_cost_center_label              = $ac_cost_center_label;
        $costcenter_info->ac_cost_center_description        = $ac_cost_center_description;
        $costcenter_info->ac_category_id                    = $ac_category_id;
        $costcenter_info->ac_type_id                        = $ac_type_id;
        $costcenter_info->ac_parent_cost_center_id          = $ac_parent_cost_center_id;
        $costcenter_info->ac_manager_id                     = $ac_manager_id;
        $costcenter_info->ac_status_id                      = $ac_status_id;
        
        $costcenter_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Cost Center Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Cost Center category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $tc_id
     */
    public function EditForm( $ac_id )
    {
        $costcenter_info          = CostCenters::find($ac_id);
        $lst_categories = Categories::whereCcaIsDeleted(0)->get();
        $lst_types = CostCenterTypes::whereAtIsDeleted(0)->get();
        $lst_statuses = CostCenterStatus::whereCsIsDeleted(0)->get();
        $lst_managers = Users::where('u_is_active',1)->where('u_is_deleted',0)->get();
        $lst_costcenters = CostCenters::whereAcIsDeleted(0)->whereNotIn('ac_id',array($ac_id))->get();
        
        
        $data = array(
            "costcenter_info" => $costcenter_info,
            "lst_categories" => $lst_categories,
            "lst_costcenters" => $lst_costcenters,
            "lst_types" => $lst_types,
            "lst_statuses" => $lst_statuses,
            "lst_managers" => $lst_managers,
        );
        return view('costcenters.editform',$data);
    }
    
    
    /**
     * Delete Cost Center information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteCostCenterInformation(Request $request)
    {
        $result_array = array();
        $ac_id= $request->input('ac_id');
         
        $costcenter_info = CostCenters::find( $ac_id );
        $costcenter_info->ac_is_deleted          = 1;
        $costcenter_info->ac_deleted_by          = Session('user_id');
        $costcenter_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}