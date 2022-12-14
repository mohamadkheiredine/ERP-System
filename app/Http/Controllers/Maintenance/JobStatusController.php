<?php
/***********************************************************
JobStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : May 31, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Controller to manage job status table
***********************************************************/



namespace App\Http\Controllers\Maintenance;

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
use App\models\Maintenance\JobStatus;



class JobStatusController extends Controller
{
    
    /**
     * Page to control Order Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('maintenance.jobstatus',$data);
    }
    
    
    /**
     * Display list of Maintenance Job Status saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        
        $lst_job_status = JobStatus::whereJsIsDeleted(0)->get();
        
        $data = array(
            "lst_job_status" => $lst_job_status
        );
        
        $result_array = array();
        $result_array['display'] = view("maintenance.listjobstatus",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new job Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
         
        $data = array();
        return view('maintenance.addjobstatus',$data);
    }
    
    
    /**
     * Save Order Order Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveJobStatusInfo(Request $request)
    {
        $js_id                      = $request->input('js_id');
        $js_status_title            = $request->input('js_status_title');
        $js_status_color            = $request->input('js_status_color'); 
        $js_status_order            = $request->input('js_status_order');
        $js_status_description      = $request->input('js_status_description');
        
        $result_array = array();
        
        
        $job_status  = new JobStatus();
        if($js_id!= null)
        {
            $job_status = JobStatus::find($js_id);
        }
        
        $job_status->js_status_title           = $js_status_title;
        $job_status->js_status_color           = $js_status_color; 
        $job_status->js_status_order           = $js_status_order;
        $job_status->js_status_description     = $js_status_description;
        
        $job_status->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Job Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Job Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $js_id
     */
    public function EditForm( $js_id )
    { 
        $status_info        = JobStatus::find($js_id);
        
        $data = array( 
            "status_info" => $status_info
        );
        return view('maintenance.editjobstatus',$data);
    }
    
    
    /**
     * Delete job Status information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteJobStatusInfo(Request $request)
    {
        
        $js_id= $request->input('js_id');
        
        $job_status = JobStatus::find( $js_id);
        $job_status->js_is_deleted          = 1;
        $job_status->js_deleted_by          = Session('user_id');
        $job_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}