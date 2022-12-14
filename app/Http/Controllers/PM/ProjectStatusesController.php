<?php
/***********************************************************
ProjectStatusesController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 26, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\Http\Controllers\PM;

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
use App\models\SRM\SupplierCategories;
use App\models\PMP\ProjectTypes;
use App\models\PMP\ProjectStatus;



class ProjectStatusesController extends Controller
{
    /**
     * Page to control Project Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('pm.projects.status',$data);
    }
    
    
    /**
     * Display list of Project status saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $lst_project_status     = ProjectStatus::wherePsIsDeleted(0)->get();
        
        $data = array(
            "lst_project_status" => $lst_project_status,
        );
        
        $result_array = array();
        
        $result_array['display'] = view("pm.projects.liststatus",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Project Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_project_status     = ProjectStatus::wherePsIsDeleted(0)->get();
        
        
        $data = array(
            "lst_project_status" => $lst_project_status
        );
        return view('pm.projects.addstatus',$data);
    }
    
    
    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $ps_id                      = $request->input('ps_id');
        $ps_status_title            = $request->input('ps_status_title');
        $ps_status_color            = $request->input('ps_status_color');
        $ps_depend_on               = $request->input('ps_depend_on');
        
        $result_array = array();
        
        
        $project_status = new ProjectStatus();
        if( $ps_id != null )
        {
            $project_status = ProjectStatus::find($ps_id);
        }
        
        $project_status->ps_status_title         = $ps_status_title;
        $project_status->ps_status_color         = $ps_status_color;
        $project_status->ps_depend_on            = $ps_depend_on;
        
        
        
        $project_status->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ps_id )
    {
        $status_info  = ProjectStatus::find($ps_id);
         
        $lst_project_status     = ProjectStatus::wherePsIsDeleted(0)->whereNotIn('ps_id',array($ps_id))->get();
        
        
        $data = array(
            "status_info" => $status_info,
            "lst_project_status" => $lst_project_status,
        );
        return view('pm.projects.editstatus',$data);
    }
    
    
    /**
     * Delete Product Types from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteData(Request $request)
    {
        
        $ps_id= $request->input('ps_id');
        
        $project_status = ProjectStatus::find( $ps_id );
        $project_status->pt_is_deleted   = 1;
        $project_status->pt_deleted_by   = Session('user_id');
        $project_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}