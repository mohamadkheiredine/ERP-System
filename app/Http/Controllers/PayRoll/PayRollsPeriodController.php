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
use App\models\PayRolls\PayrollsPeriods;



class PayRollsPeriodController extends Controller
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
       $pp_frequency =  Array('weekly', 'bi-weekly', 'monthly', 'quarterly');
        $pp_status = Array('open', 'closed');
        
        $data = array(
            "pp_frequency" => $pp_frequency,
            "pp_status" => $pp_status,
        );
        return Response()->view('payrolls.periods',$data);
    }
    
    
    /**
     * Display list PayRoll saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
   
        $pp_frequency               = $request->input('pp_frequency');
        $pp_status                  = $request->input('pp_status');
        $page_number                = $request->input('page_number');
        $general_search             = $request->input('general_search');
        $nbr_rows_per_pages         = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
        
        
        $payrolls_periods_cond = PayrollsPeriods::wherePpIsDeleted(0); 

        if($pp_frequency != "")
            $payrolls_periods_cond = $payrolls_periods_cond->wherePpFrequency($pp_frequency);
        if($pp_status != "")
            $payrolls_periods_cond = $payrolls_periods_cond->wherePpStatus($pp_status);
        
         if( strlen($general_search)  > 0)
         {
             $payrolls_periods_cond = $payrolls_periods_cond->where('pp_period_name','LIKE','%' . $general_search . '%'); 
         }
             
        
         $periods_count = $payrolls_periods_cond->count();
       
        
         $total_pages = ceil( $periods_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);
             
         $list_periods = $payrolls_periods_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "list_periods" => $list_periods, 
        );
        
        $result_array = array(); 
        $result_array['display'] = view("payrolls.listperiods",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Period
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $pp_frequency =  Array('weekly', 'bi-weekly', 'monthly', 'quarterly');
        $pp_status = Array('open', 'closed');
        
        $data = array(
            "pp_frequency" => $pp_frequency,
            "pp_status" => $pp_status,
        );
        return view('payrolls.addperiod',$data);
    }
    
    
    /**
     * Save Payroll Period to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SavePayRollPeriodInfo(Request $request)
    {
        $pp_id                      = $request->input('pp_id');
        $pp_period_name             = $request->input('pp_period_name');
        $pp_start_date              = $request->input('pp_start_date');
        $pp_end_date                = $request->input('pp_end_date');
        $pp_frequency               = $request->input('pp_frequency');
        $pp_status                  = $request->input('pp_status');
        
        $result_array = array();
 
        
        $payroll_periods = new PayrollsPeriods();
        if( $pp_id != null )
        {
            $payroll_periods = PayrollsPeriods::find($pp_id);
            $payroll_periods->pp_updated_by              = session('user_id');
            $payroll_periods->pp_updated_at           = date("Y-m-d H:i:s");
        }
        else
        {
             $payroll_periods->pp_created_by              = session('user_id');
            $payroll_periods->pp_created_at           = date("Y-m-d H:i:s");
        }
         
        $payroll_periods->pp_period_name              = $pp_period_name;
        $payroll_periods->pp_start_date           = $pp_start_date;
        $payroll_periods->pp_end_date           = $pp_end_date;
        $payroll_periods->pp_frequency           = $pp_frequency;
        $payroll_periods->pp_status           = $pp_status;
        
        $payroll_periods->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'PayRoll Periods Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit PayRoll Period Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $pp_id )
    {
        $payroll_periods        = PayrollsPeriods::find($pp_id);
         $pp_frequency =  Array('weekly', 'bi-weekly', 'monthly', 'quarterly');
        $pp_status = Array('open', 'closed');
        $data = array(
            "pp_frequency" => $pp_frequency,
            "pp_status" => $pp_status,
            "payroll_periods" => $payroll_periods
        );
        return view('payrolls.editperiod',$data);
    }
    
    
    /**
     * Delete PayRoll Period information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeletePayRollPeriodInfo(Request $request)
    {
        
        $pp_id= $request->input('pp_id');
         
        $payroll_periods = PayrollsPeriods::find($pp_id);
        $payroll_periods->pp_is_deleted          = 1;
        $payroll_periods->pp_deleted_by          = Session('user_id');
        $payroll_periods->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}