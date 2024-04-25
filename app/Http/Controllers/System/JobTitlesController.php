<?php
/***********************************************************
JobTitlesController.php
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
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\System\JobTitles;



class JobTitlesController extends Controller
{

    /**
     * Page to control job titles Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('system.jobtitles',$data);
    }
    
    
    /**
     * Display list of jolb title saved in the system
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
            
        $jobtitles_count = JobTitles::whereJtIsDeleted(0)->count();
        
        
        $total_pages = ceil( $jobtitles_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $job_titles = JobTitles::whereJtIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
        
        $data = array(
            "job_titles" => $job_titles
        );
        
        $result_array = array();
        
        $result_array['total_pages']    = $total_pages;
        $result_array['display']        = view("system.listjobtitles",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new job title
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
         
        $data = array();
        return view('system.addjobtitle',$data);
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
    public function SaveJobTitleInfo(Request $request)
    {
        
        $jt_id              = $request->input('jt_id');
        $jt_job_title       = $request->input('jt_job_title');
        
        $result_array = array();
 
        
        $JobTitles = new JobTitles();
        if($jt_id != null)
        {
            $JobTitles = JobTitles::find($jt_id);
        }
         
        $JobTitles->jt_job_title = $jt_job_title;
        
        $JobTitles->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Job Title Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit JobTitle Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $jt_id
     */
    public function EditForm( $jt_id )
    {
        $jobtitles_info        = JobTitles::find($jt_id); 
        $data = array(
            "jobtitles_info" => $jobtitles_info
        );
        return view('system.editjobtitle',$data);
    }
    
    
    /**
     * Delete jobtitle information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteJobTitleInfo(Request $request)
    {
        
        $jt_id= $request->input('jt_id');
         
        $JobTitles = JobTitles::find( $d_id);
        $JobTitles->jt_is_deleted          = 1;
        $JobTitles->jt_deleted_by          = Session('user_id');
        $JobTitles->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}