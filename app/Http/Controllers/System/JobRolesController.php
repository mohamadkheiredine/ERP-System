<?php
/***********************************************************
JobRolesController.php
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
use App\models\System\JobRoles;



class JobRolesController extends Controller
{

    /**
     * Page to control job roles Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('system.jobroles',$data);
    }
    
    
    /**
     * Display list of job roles saved in the system
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
            
        $jobroles_count = JobRoles::whereJrIsDeleted(0)->count();
        
        
        $total_pages = ceil( $jobroles_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $job_roles = JobRoles::whereJrIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
        
        $data = array(
            "job_roles" => $job_roles
        );
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("system.listjobroles",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Job Roles
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
         
        $data = array();
        return view('system.addjobrole',$data);
    }
    
    
    /**
     * Save Job Role Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveJobRoleInfo(Request $request)
    {
        
        $jr_id                  = $request->input('jr_id');
        $jr_job_role            = $request->input('jr_job_role');
        
        $result_array = array();
 
        
        $JobRoles = new JobRoles();
        if($jr_id != null)
        {
            $JobRoles = JobRoles::find($jr_id);
        }
         
        $JobRoles->jr_job_role = $jr_job_role; 
        
        $JobRoles->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Job Roles Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Job Role Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $jr_id
     */
    public function EditForm( $jr_id )
    {
        $jobroles_info        = JobRoles::find($jr_id); 
        $data = array(
            "jobroles_info" => $jobroles_info
        );
        return view('system.editjobrole',$data);
    }
    
    
    /**
     * Delete Job Role information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteJobRoleInfo(Request $request)
    {
        
        $jr_id= $request->input('jr_id');
         
        $jobroles = JobRoles::find( $jr_id);
        $jobroles->jr_is_deleted          = 1;
        $jobroles->jr_deleted_by          = Session('user_id');
        $jobroles->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}