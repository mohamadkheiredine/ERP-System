<?php
/***********************************************************
OperationStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\Shipment;

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
use App\models\Logistics\ShipOperationStatus;



class OperationStatusController extends Controller
{
    
    /**
     * Display Page of Operation Status Management
     * 
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('shipment.operationstatus',$data);
    }
    
    
    /**
     * Display list of operation statuses
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;
                
                $operation_status_count = ShipOperationStatus::whereOsIsDeleted(0)->count();
                
                
                $total_pages = ceil( $operation_status_count/$nbr_rows_per_pages );
                $total_pages = intval($total_pages);
                
                $operation_status = ShipOperationStatus::whereOsIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
                
                $data = array(
                    "operation_status" => $operation_status
                );
                
                $result_array = array();
                
                $result_array['total_pages'] = $total_pages;
                $result_array['display'] = view("shipment.listostatuses",$data)->render();
                
                return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Operation shipment Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $data = array();
        return view('shipment.addstatus',$data);
    }
    
    
    /**
     * Save Status Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveStatusInfo(Request $request)
    {
        $os_id                      = $request->input('os_id');
        $os_status_title            = $request->input('os_status_title');
        $os_status_color            = $request->input('os_status_color');
        $os_status_order            = $request->input('os_status_order');
        
        $result_array = array();
        
        
        $OperationStatus = new ShipOperationStatus();
        if($os_id != null)
        {
            $OperationStatus = ShipOperationStatus::find($os_id);
        }
        
        $OperationStatus->os_status_title       = $os_status_title;
        $OperationStatus->os_status_color       = $os_status_color;
        $OperationStatus->os_status_order       = $os_status_order;
        
        $OperationStatus->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Operation Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Operation Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $et_id
     */
    public function EditForm( $os_id )
    {
        $operation_status_info        = ShipOperationStatus::find( $os_id );
        $data = array(
            "operation_status_info" => $operation_status_info
        );
        return view('shipment.editstatus',$data);
    }
    
    
    /**
     * Delete Operation Status information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteOperationStatusInfo(Request $request)
    {
        
        $os_id      = $request->input('os_id');
        
        $operation_status   = ShipOperationStatus::find( $os_id );
        $operation_status->os_is_deleted          = 1;
        $operation_status->os_deleted_by          = Session('user_id');
        $operation_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}