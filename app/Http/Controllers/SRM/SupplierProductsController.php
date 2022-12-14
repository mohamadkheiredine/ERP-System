<?php
/***********************************************************
SupplierStatusesController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\SRM;

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
use App\models\SRM\SupplierStatus;



class SupplierStatusesController extends Controller
{

    /**
     * Page to control SRM Statuses Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('srm.statuses',$data);
    }
    
    
   /**
    * Display list of Supplier Statuses saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {        
        $supplier_statuses = SupplierStatus::whereSsIsDeleted(0)->get();
        
        $supplier_categories_array   = array();
        $lst_supplier_status     = SupplierStatus::whereSsIsDeleted(0)->orderBy("ss_status_order","asc")->get();
        
        $data = array(
            "lst_supplier_status" => $lst_supplier_status
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.lststatuses",$data)->render();
        
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

        $data = array();
        return view('srm.addstatus',$data);
    }
    
    
    /**
     * Save Supplier Status Info to saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierStatusInfo(Request $request)
    {
        $ss_id                      = $request->input('ss_id');
        $ss_status_title            = $request->input('ss_status_title');
        $ss_status_order            = $request->input('ss_status_order');
        $ss_status_color            = $request->input('ss_status_color');
        
        $result_array = array();
 

        $SupplierStatus     = new SupplierStatus();
        if($ss_id != null)
        {
            $SupplierStatus= SupplierStatus::find($ss_id);
        }
         
        $SupplierStatus->ss_status_title        = $ss_status_title;
        $SupplierStatus->ss_status_order        = $ss_status_order; 
        $SupplierStatus->ss_status_color        = $ss_status_color;
        
        
        
        $SupplierStatus->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Supplier Status Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page for supplier status
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ss_id )
    {
        $status_info = SupplierStatus::find($ss_id); 
        
        $data = array(
            "status_info" => $status_info
        );
        return view('srm.editstatus',$data);
    }
    
    
    /**
     * Delete Supplier status from the database by change flag is_deleted of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierStatusInfo(Request $request)
    {
        
        $ss_id= $request->input('ss_id');
         
        $supplier_status = SupplierStatus::find( $ss_id);
        $supplier_status->ss_is_deleted          = 1;
        $supplier_status->ss_deleted_by          = Session('user_id');
        $supplier_status->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}