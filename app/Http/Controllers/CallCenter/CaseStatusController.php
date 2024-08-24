<?php
/***********************************************************
CaseStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\CallCenter;

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
use App\models\CallCenter\CaseStatus;


class CaseStatusController extends Controller
{

    /**
     * Page to control Case Statuses Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->get();
        
        $data = array(
            "lst_statuses" => $lst_statuses
        );
        return Response()->view('callcenter.casestatus',$data);
    }
    
    
   /**
    * Display list of Case Statuses saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {   
        $general_search = $request->input('general_search');
        
        $statuses_cond = CaseStatus::whereCcIsDeleted(0);
        
        if(strlen($general_search) > 0)
        {
            $statuses_cond = $statuses_cond->where("cc_status_title","LIKE","%" . $general_search . "%");
        }
        
        $lst_cases_status     = $statuses_cond->orderBy("cc_id","asc")->get();
        
        $data = array(
            "lst_cases_status" => $lst_cases_status
        );
        
        $result_array = array();
        
        $result_array['display'] = view("callcenter.listcasestatus",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->get();
        $data = array(
            "lst_statuses" => $lst_statuses
        );
        return view('callcenter.addcasestatus',$data);
    }
    
    
    /**
     * Save Case Status Info to saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveCaseStatusInfo(Request $request)
    {
        $cc_id                     = $request->input('cc_id');
        $fk_parent_status           = $request->input('fk_parent_status');
        $cc_status_title           = $request->input('cc_status_title');
        $cc_status_description            = $request->input('cc_status_description');
        $cc_status_color            = $request->input('cc_status_color');
        
        $result_array = array();
 

        $casestatus_info     = new CaseStatus();
        if($cc_id != null)
        {
            $casestatus_info= CaseStatus::find($cc_id);
        }
         
        $casestatus_info->fk_parent_status        = $fk_parent_status; 
        $casestatus_info->cc_status_title        = $cc_status_title; 
        $casestatus_info->cc_status_description        = $cc_status_description; 
        $casestatus_info->cc_status_color        = $cc_status_color; 
        
        
        
        $casestatus_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Case Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page for Case status
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $cc_id )
    {
        $status_info = CaseStatus::find($cc_id); 
        $lst_statuses = CaseStatus::whereCcIsDeleted(0)->whereNotIn('cc_id',array( $cc_id ))->get();
        
        
        $data = array(
            "status_info" => $status_info,
            "lst_statuses" => $lst_statuses
        );
        return view('callcenter.editcasestatus',$data);
    }
    
    
    /**
     * Delete Supplier status from the database by change flag is_deleted of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteCaseStatusInfo(Request $request)
    {
        
        $cc_id = $request->input('cc_id');
         
        $case_status = CaseStatus::find( $cc_id);
        $case_status->cc_is_deleted          = 1;
        $case_status->cc_deleted_by          = Session('user_id');
        $case_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}