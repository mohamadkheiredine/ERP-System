<?php
/***********************************************************
PlansStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Production;

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
use App\models\Sales\OrderStatus;
use App\models\Production\PlanStatus;



class PlansStatusController extends Controller
{

    /**
     * Page to control Plam Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('planstatus.status',$data);
    }
    
    
    /**
     * Display list of Plan Status saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $lst_plan_status = PlanStatus::wherePsIsDeleted(0)->get();
        
        $plan_status_array   = CreateDatabaseArrayByIndex($lst_plan_status, "ps_id");
 
        $data = array(
            "lst_plan_status" => $lst_plan_status,
            "plan_status_array" => $plan_status_array
        );
        
        $result_array = array(); 
        $result_array['display'] = view("planstatus.displaylist",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Plan Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_plan_status = PlanStatus::wherePsIsDeleted(0)->get();
        
        $data = array(
            "lst_plan_status" => $lst_plan_status,
        );
        return view('planstatus.addstatus',$data);
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
    public function SaveStatusInfo(Request $request)
    {
        $ps_id                      = $request->input('ps_id');
        $ps_parent_status           = $request->input('ps_parent_status');
        $ps_status_title            = $request->input('ps_status_title');
        $ps_status_description      = $request->input('ps_status_description');
        $ps_status_color            = $request->input('ps_status_color');
        
        $result_array = array();
 
        
        $PlanStatus = new PlanStatus();
        if( $ps_id != null )
        {
            $PlanStatus= PlanStatus::find($ps_id);
        }
         
        $PlanStatus->ps_parent_status           = $ps_parent_status;
        $PlanStatus->ps_status_title            = $ps_status_title;
        $PlanStatus->ps_status_description      = $ps_status_description;
        $PlanStatus->ps_status_color            = $ps_status_color;
        
        $PlanStatus->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Plan Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Order Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $ps_id )
    {
        $lst_plan_status   = PlanStatus::wherePsIsDeleted(0)->whereNotIn('ps_id',array($ps_id))->get();
        $status_info        = PlanStatus::find($ps_id);
        
        $data = array(
            "lst_plan_status" => $lst_plan_status,
            "status_info" => $status_info
        );
        return view('planstatus.editstatus',$data);
    }
    
    
    /**
     * Delete Plan Status information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteStatusInfo(Request $request)
    {
        
        $ps_id= $request->input('ps_id');
         
        $plan_status = PlanStatus::find( $ps_id);
        $plan_status->ps_is_deleted          = 1;
        $plan_status->ps_deleted_by          = Session('user_id');
        $plan_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}