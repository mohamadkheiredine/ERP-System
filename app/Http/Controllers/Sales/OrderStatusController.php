<?php
/***********************************************************
OrderStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Sales;

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



class OrderStatusController extends Controller
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
        return Response()->view('orders.status',$data);
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
   
        $lst_order_status = OrderStatus::whereOsIsDeleted(0)->get();
        
        $order_status_array   = CreateDatabaseArrayByIndex($lst_order_status, "os_id");
 
        $data = array(
            "lst_order_status" => $lst_order_status,
            "order_status_array" => $order_status_array
        );
        
        $result_array = array(); 
        $result_array['display'] = view("orders.liststatus",$data)->render();
        
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
        
        $lst_order_status = OrderStatus::whereOsIsDeleted(0)->get();
        
        $data = array(
            "lst_order_status" => $lst_order_status,
        );
        return view('orders.addstatus',$data);
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
        $os_id                      = $request->input('os_id');
        $os_status_title            = $request->input('os_status_title');
        $os_status_color            = $request->input('os_status_color');
        $os_status_parent_id        = $request->input('os_status_parent_id');
        $os_status_order            = $request->input('os_status_order');
        
        $result_array = array();
 
        
        $OrderStatus = new OrderStatus();
        if($os_id!= null)
        {
            $OrderStatus= OrderStatus::find($os_id);
        }
         
        $OrderStatus->os_status_title           = $os_status_title;
        $OrderStatus->os_status_color           = $os_status_color;
        $OrderStatus->os_status_parent_id       = $os_status_parent_id;
        $OrderStatus->os_status_order           = $os_status_order;
        
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
    public function EditForm( $os_id )
    {
        $lst_order_status   = OrderStatus::whereOsIsDeleted(0)->whereNotIn('os_id',array($os_id))->get();
        $status_info        = OrderStatus::find($os_id);
        
        $data = array(
            "lst_order_status" => $lst_order_status,
            "status_info" => $status_info
        );
        return view('orders.editstatus',$data);
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
        
        $os_id= $request->input('os_id');
         
        $order_status = OrderStatus::find( $os_id);
        $order_status->os_is_deleted          = 1;
        $order_status->os_deleted_by          = Session('user_id');
        $order_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}