<?php
/***********************************************************
DepartmentsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\System;

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
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\Library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Users\Users;
use App\Library\DepartmentsManager;



class DepartmentsController extends Controller
{

    /**
     * Page to control Departments Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('system.departments',$data);
    }
    
    
    /**
     * Display list of Departments saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $general_search        = $request->input('general_search');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;
            
        $departments_count = Departments::whereSdIsDeleted(0);
        
        if(strlen($general_search) > 0)
        {
            $departments_count = $departments_count->where('sd_department_title','LIKE','%' . $general_search . '%');
        }
        
        $departments_count = $departments_count->count();
        
        
        $total_pages = ceil( $departments_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $departments = Departments::whereSdIsDeleted(0);
        
        if(strlen($general_search) > 0)
        {
            $departments= $departments->where('sd_department_title','LIKE','%' . $general_search . '%');
        }
        
        $departments = $departments->skip($skip)->take($nbr_rows_per_pages)->orderBy('sd_department_order', 'asc')->get();
        
        $data = array(
            "departments" => $departments 
        );
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("system.listdepartments",$data)->render();
        
        return Response()->json($result_array);
    }
    
    /**
     * Page to Draw Hierarchy based on Selected Parent Department
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DrawHierarchy(Request $request)
    {
        $lst_departments = Departments::whereSdIsDeleted(0)->get();
         
        $department_manager = new DepartmentsManager();
        $dep_array = array();
        foreach( $lst_departments as $key => $department_info ) {
            
            $parent_department = $department_info->sd_parent_department;
            $sd_id             = $department_info->sd_id;
            
            $dep_array[ $sd_id ]['department_title']   = $department_info->sd_department_title;
            $dep_array[ $sd_id ]['department_manager'] = $department_info->sd_department_manager;
            $dep_array[ $sd_id ]['parent_department']  = $parent_department;
            
        }
        
        
        
        $departments_array = $department_manager->GenerateDepartmentsArray($dep_array);
        

        
         
        $data = array(
            "departments_array" => $departments_array
        );
        return Response()->view('system.hierarchydepartments',$data);
    }
    
    /**
     * Function of Adding a new Department
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        $lst_managers       = Users::whereUIsActive(1)->whereUUserType(3)->get(); 
        
        $data = array(
            "lst_departments" => $lst_departments,
            "lst_managers" => $lst_managers,
        );
        return view('system.adddepartment',$data);
    }
    
    
    /**
     * Save Department Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveDepartmentInfo(Request $request)
    {
        $sd_department_title        = $request->input('sd_department_title');
        $sd_department_order        = $request->input('sd_department_order');
        $sd_department_color        = $request->input('sd_department_color');
        $sd_department_code         = $request->input('sd_department_code');
        $sd_parent_department       = $request->input('sd_parent_department');
        $sd_department_manager      = $request->input('sd_department_manager');
        $sd_id                      = $request->input('d_id');
        
        $result_array = array();
 
        
        $Departments = new Departments();
        if($sd_id != null)
        {
            $Departments= Departments::find($sd_id);
        }
         
         
        $Departments->sd_department_code        = $sd_department_code;
        $Departments->sd_department_color       = $sd_department_color;
        $Departments->sd_department_title       = $sd_department_title;
        $Departments->sd_department_order       = $sd_department_order; 
        $Departments->sd_parent_department      = $sd_parent_department; 
        $Departments->sd_department_manager     = $sd_department_manager; 
        
        $Departments->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Department Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Department Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pc_id
     */
    public function EditForm( $d_id )
    {
        $department_info        = Departments::find($d_id);
        $lst_departments        = Departments::whereSdIsDeleted(0)->where("sd_id","!=",$d_id)->get();
        $lst_managers           = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(3)->get();
        
        $data = array(
            "department_info" => $department_info,
            "lst_departments" => $lst_departments,
            "lst_managers" => $lst_managers
        );
        return view('system.editdepartment',$data);
    }
    
    
    /**
     * Delete Department information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteDepartmentInfo(Request $request)
    {
        
        $d_id= $request->input('d_id');
         
        $department = Departments::find( $d_id);
        $department->sd_is_deleted          = 1;
        $department->sd_deleted_by          = Session('user_id');
        $department->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}