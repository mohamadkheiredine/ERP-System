<?php
/***********************************************************
DayTypesController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\Timesheet;

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
use App\models\Timesheet\DayTypes;



class DayTypesController extends Controller
{

    /**
     * Page to control Day Types Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('timesheet.daytypes',$data);
    }
    
    
   /**
    * Display list of day types saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {        
        $lst_day_types = DayTypes::whereDtIsDeleted(0)->orderBy("dt_day_type","ASC")->get();
        
        $data = array(
            "lst_day_types" => $lst_day_types
        );
        
        $result_array = array();
        
        $result_array['display'] = view("timesheet.lstdaytypes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
 
    /**
     * Add new Form Day type
     * 
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $data = array();
        return view('timesheet.adddaytype',$data);
    }
    
    
    /**
     * Save day type Info to saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveDayTypeInfo(Request $request)
    {
        $dt_id                  = $request->input('dt_id');
        $dt_day_type            = $request->input('dt_day_type');
        $dt_working_hours       = $request->input('dt_working_hours');
        $dt_day_color           = $request->input('dt_day_color');
        $dt_show_for_employee   = $request->input('dt_show_for_employee');
        $dt_show_for_employee   = ($dt_show_for_employee == null) ? 0 : 1;
        
        $result_array = array();
 

        $dayType     = new DayTypes();
        if($dt_id != null)
        {
            $dayType= DayTypes::find($dt_id);
        }
         
        $dayType->dt_day_type           = $dt_day_type; 
        $dayType->dt_day_color          = $dt_day_color;
        $dayType->dt_working_hours      = $dt_working_hours;
        $dayType->dt_show_for_employee  = $dt_show_for_employee;
        
        
        
        $dayType->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Day Type Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page for day types
     * @param unknown $dt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $dt_id )
    {
        $daytypes_info = DayTypes::find($dt_id); 
        
        $data = array(
            "daytypes_info" => $daytypes_info
        );
        return view('timesheet.editdaytype',$data);
    }
    
    
    /**
     * Delete Day Tyoes from the database by change flag is_deleted of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteDayTypeInfo(Request $request)
    {
        
        $dt_id= $request->input('dt_id');
         
        $daytypes_info = DayTypes::find( $dt_id);
        $daytypes_info->dt_is_deleted          = 1;
        $daytypes_info->dt_deleted_by          = Session('user_id');
        $daytypes_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}