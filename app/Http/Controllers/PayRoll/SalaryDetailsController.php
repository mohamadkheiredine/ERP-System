<?php
/***********************************************************
SalaryDetailsController
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/




namespace App\Http\Controllers\PayRoll;

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
use App\models\Shipment\OrderStatus;
use App\models\System\Companies;
use App\models\Users;



class SalaryDetailsController extends Controller
{

    /**
     * Page to control Salary Details Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_employees = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        
        $data = array(
            "lst_companies" => $lst_companies,
            "lst_employees" => $lst_employees
        );
        return Response()->view('payrolls.salarydetails',$data);
    }
    
    
    /**
     * Display list of Order Status saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $lst_order_status = OrderStatus::whereSsIsDeleted(0)->get();
        
        $order_status_array   = CreateDatabaseArrayByIndex($lst_order_status, "os_id");
 
        $data = array(
            "lst_order_status" => $lst_order_status,
            "order_status_array" => $order_status_array
        );
        
        $result_array = array(); 
        $result_array['display'] = view("shipment.orders.liststatus",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Order Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_order_status = OrderStatus::whereSsIsDeleted(0)->get();
        
        $data = array(
            "lst_order_status" => $lst_order_status,
        );
        return view('shipment.orders.addstatus',$data);
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
    public function SaveOrderStatusInfo(Request $request)
    {
        $ss_id                      = $request->input('ss_id');
        $ss_parent_id               = $request->input('ss_parent_id');
        $ss_status_title             = $request->input('ss_status_title');
        $ss_status_color             = $request->input('ss_status_color');
        $ss_status_order             = $request->input('ss_status_order');
        
        $result_array = array();
 
        
        $OrderStatus = new OrderStatus();
        if( $ss_id != null )
        {
            $OrderStatus= OrderStatus::find($ss_id);
        }
         
        $OrderStatus->ss_parent_id           = $ss_parent_id;
        $OrderStatus->ss_status_title           = $ss_status_title;
        $OrderStatus->ss_status_color           = $ss_status_color;
        $OrderStatus->ss_status_order           = $ss_status_order;
        
        $OrderStatus->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Order Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Order Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $ss_id )
    {
        $lst_order_status   = OrderStatus::whereSsIsDeleted(0)->whereNotIn('ss_id',array($ss_id))->get();
        $status_info        = OrderStatus::find($ss_id);
        
        $data = array(
            "lst_order_status" => $lst_order_status,
            "status_info" => $status_info
        );
        return view('shipment.orders.editstatus',$data);
    }
    
    
    /**
     * Delete Order Status information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteOrderStatusInfo(Request $request)
    {
        
        $ss_id= $request->input('ss_id');
         
        $order_status = OrderStatus::find( $ss_id);
        $order_status->ss_is_deleted          = 1;
        $order_status->ss_deleted_by          = Session('user_id');
        $order_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}