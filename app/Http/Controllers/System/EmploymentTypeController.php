<?php
/***********************************************************
EmploymentTypeController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 3, 2019
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
use App\models\Timesheet\EmploymentType;



class EmploymentTypeController extends Controller
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
        return Response()->view('system.employment-type',$data);
    }
    
    
    /**
     * Display list of ERmployment Type saved in the system
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
            
        $employment_type_count = EmploymentType::whereEtIsDeleted(0)->count();
        
        
        $total_pages = ceil( $employment_type_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $employment_type = EmploymentType::whereEtIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
        
        $data = array(
            "employment_type" => $employment_type
        );
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("system.listemptype",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Employment Type 
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
         
        $data = array();
        return view('system.addemptype',$data);
    }
    
    
    /**
     * Save Employment Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveEmpTypeInfo(Request $request)
    {
        $et_id                      = $request->input('et_id');
        $et_type                    = $request->input('et_type');
        $et_min_working_hours       = $request->input('et_min_working_hours');
        $et_order                   = $request->input('et_order');
        
        
        $result_array = array();
 
        
        $EmpType = new EmploymentType();
        if($et_id != null)
        {
            $EmpType= EmploymentType::find($et_id);
        }
         
        $EmpType->et_type= $et_type;
        $EmpType->et_min_working_hours = $et_min_working_hours; 
        $EmpType->et_order= $et_order; 
        
        $EmpType->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Employment Type Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Employment Type Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $et_id
     */
    public function EditForm( $et_id )
    {
        $employment_type_info        = EmploymentType::find($et_id); 
        $data = array(
            "employment_type_info" => $employment_type_info
        );
        return view('system.editemptype',$data);
    }
    
    
    /**
     * Delete Employment Type information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteEmpTypeInfo(Request $request)
    {
        
        $et_id= $request->input('et_id');
         
        $emp_type = EmploymentType::find( $et_id );
        $emp_type->et_is_deleted          = 1;
        $emp_type->et_deleted_by          = Session('user_id');
        $emp_type->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}