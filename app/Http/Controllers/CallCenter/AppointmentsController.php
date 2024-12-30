<?php
/***********************************************************
AppointmentsController.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 1, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/



namespace App\Http\Controllers\CallCenter;

use App;
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
use App\models\Users\Users;
use App\models\CallCenter\InboundCall;
use App\models\Inventory\Customers;
use App\models\Inventory\Products;
use App\models\CallCenter\Appointments;
use App\models\CRM\CRMLeads;
use App\models\System\Countries;
use App\models\Users\UserTypes;
use App\models\CRM\CRMLeadTypes;
use App\models\CallCenter\ApptResults;


class AppointmentsController extends Controller
{
    
  
    /**
     * Page to control Lead Appointment Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_lead_types         = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_countries          = Countries::all(); 
        $lst_appt_results       = ApptResults::whereArIsDeleted(0)->get(); 
        $lst_telemarketing      = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_leads              = CRMLeads::whereClIsDeleted(0)->get();
        
        $data = array(
            "lst_sales" => $lst_sales,
            "lst_telemarketing" => $lst_telemarketing,
            "lst_appt_results" => $lst_appt_results,
            "lst_countries" => $lst_countries,
            "lst_lead_types" => $lst_lead_types,
            "lst_leads" => $lst_leads,
        );
        return Response()->view('callcenter.appointments',$data);
    }
    
    /**
     * page of list of todays appointments
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function TodaysAppointments(Request $request)
    {
        
        
        $data = array();
        return Response()->view('callcenter.todaysapt',$data);
    }
    
    /**
     * Closure sales appointment
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return type
     */
    public function ClosureAppointmentReport(Request $request)
    {
        
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        
        $data = array(
            "lst_sales" => $lst_sales
        );
        return Response()->view('callcenter.closureapp',$data);
    }
    
    
    
    public function DisplayClosureSalesmanApp(Request $request)
    {
        $cl_sales_id = $request->input('cl_sales_id');
        $ca_apt_from_date = $request->input('ca_apt_from_date');
        $ca_apt_last_date = $request->input('ca_apt_last_date');
        
        $lst_sales              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_apt_results              = ApptResults::all();
        $total_results_app = array();
        $sales_array = array();
        $aptres_array = array();
        $percentage_colsure_array = array();
        
        $query="SELECT ca_salesman_id, res.ar_app_result as appointment_result,res.ar_id as res_id, users.u_fullname as salesman_name,users.id as salesman_id, COUNT(ca_id) as total_app, ROUND(COUNT(ca_id) * 100.0 / (SELECT COUNT(ca_id) FROM callcenter_lead_appointments), 2) as ca_percentage FROM callcenter_lead_appointments as lapp left join users on lapp.ca_salesman_id = users.id left join crm_lead_app_results as res on lapp.ca_apt_result = res.ar_id WHERE 1 ";
        
        if($cl_sales_id > 0)
            $query .= " AND ca_salesman_id = " . $cl_sales_id;
        
        if(strlen($ca_apt_from_date) > 0 && strlen($ca_apt_last_date) > 0)
             $query .= " AND ca_apt_date BETWEEN '".$ca_apt_from_date."' AND '".$ca_apt_last_date."' ";
        
        $query .= " GROUP BY ca_salesman_id,ca_apt_result ORDER BY ca_percentage DESC;";
        
        $lst_closure_sales_app = DB::select($query);
        
        
        $closure_app_array = array();
        
        foreach ($lst_closure_sales_app as $key => $info ) {
            if(isset($closure_app_array[ $info->salesman_id ][ $info->res_id ]))
            {
                $closure_app_array[ $info->salesman_id ][ $info->res_id ] = $closure_app_array[ $info->salesman_id ][ $info->res_id ] + $info->ca_percentage;   
                $total_results_app[ $info->salesman_id ][ $info->res_id ] = $total_results_app[ $info->salesman_id ][ $info->res_id ] + $info->total_app;   
            }
            else
            {
                $closure_app_array[ $info->salesman_id ][ $info->res_id ] = $info->ca_percentage;
                $total_results_app[ $info->salesman_id ][ $info->res_id ] = $info->total_app;
            }
        }
        
        
        
        foreach ($lst_sales as $key => $salesman_info) {
            $sales_array[$salesman_info->id] = $salesman_info->u_fullname;
        }
        
        foreach ($lst_apt_results as $key => $res_info) {
            $aptres_array[$res_info->ar_id] = $res_info->ar_app_result;
        }
        
        
        foreach ($sales_array as $sales_id => $salesname) {
            $app = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][1]) ? $total_results_app[ $sales_id ][ 1 ] : 1;
            $approved = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][7]) ? $total_results_app[ $sales_id ][ 7 ] : 0;
            $demo = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][8]) ? $total_results_app[ $sales_id ][ 8 ] : 0;
            $percentage_colsure_array[$sales_id] = ($approved * 100) / $app;
         }
        
         
        
        $result_array['is_error'] = 0;
       $data = array(
           'closure_app_array' => $closure_app_array,
           'total_results_app' => $total_results_app,
           'sales_array' => $sales_array,
           'aptres_array' => $aptres_array,
           'lst_closure_sales_app' => $lst_closure_sales_app
       );
       $result_array['display'] = view('callcenter.displayclosuresalesapp',$data)->render();
        return Response()->json($result_array);
        
    }
    
    
    public function DownloadClosureSalesApp(Request $request)
    {
 $cl_sales_id = $request->input('cl_sales_id');
        $ca_apt_from_date = $request->input('ca_apt_from_date');
        $ca_apt_last_date = $request->input('ca_apt_last_date');
        
        $lst_sales              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_apt_results              = ApptResults::all();
        $total_results_app = array();
        $sales_array = array();
        $aptres_array = array();
        $percentage_colsure_array = array();
        
        $query="SELECT ca_salesman_id, res.ar_app_result as appointment_result,res.ar_id as res_id, users.u_fullname as salesman_name,users.id as salesman_id, COUNT(ca_id) as total_app, ROUND(COUNT(ca_id) * 100.0 / (SELECT COUNT(ca_id) FROM callcenter_lead_appointments), 2) as ca_percentage FROM callcenter_lead_appointments as lapp left join users on lapp.ca_salesman_id = users.id left join crm_lead_app_results as res on lapp.ca_apt_result = res.ar_id WHERE 1 ";
        
        if($cl_sales_id > 0)
            $query .= " AND ca_salesman_id = " . $cl_sales_id;
        
        if(strlen($ca_apt_from_date) > 0 && strlen($ca_apt_last_date) > 0)
             $query .= " AND ca_apt_date BETWEEN '".$ca_apt_from_date."' AND '".$ca_apt_last_date."' ";
        
        $query .= " GROUP BY ca_salesman_id,ca_apt_result ORDER BY ca_percentage DESC;";
        
        $lst_closure_sales_app = DB::select($query);
        
        
        $closure_app_array = array();
        
        foreach ($lst_closure_sales_app as $key => $info ) {
            if(isset($closure_app_array[ $info->salesman_id ][ $info->res_id ]))
            {
                $closure_app_array[ $info->salesman_id ][ $info->res_id ] = $closure_app_array[ $info->salesman_id ][ $info->res_id ] + $info->ca_percentage;   
                $total_results_app[ $info->salesman_id ][ $info->res_id ] = $total_results_app[ $info->salesman_id ][ $info->res_id ] + $info->total_app;   
            }
            else
            {
                $closure_app_array[ $info->salesman_id ][ $info->res_id ] = $info->ca_percentage;
                $total_results_app[ $info->salesman_id ][ $info->res_id ] = $info->total_app;
            }
        }
        
        
        
        foreach ($lst_sales as $key => $salesman_info) {
            $sales_array[$salesman_info->id] = $salesman_info->u_fullname;
        }
        
        foreach ($lst_apt_results as $key => $res_info) {
            $aptres_array[$res_info->ar_id] = $res_info->ar_app_result;
        }
        
        
        foreach ($sales_array as $sales_id => $salesname) {
            $app = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][1]) ? $total_results_app[ $sales_id ][ 1 ] : 1;
            $approved = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][7]) ? $total_results_app[ $sales_id ][ 7 ] : 0;
            $demo = isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][8]) ? $total_results_app[ $sales_id ][ 8 ] : 0;
            $percentage_colsure_array[$sales_id] = ($approved * 100) / $app;
         }
        
         
        
        $result_array['is_error'] = 0;
       $data = array(
           'closure_app_array' => $closure_app_array,
           'total_results_app' => $total_results_app,
           'sales_array' => $sales_array,
           'aptres_array' => $aptres_array,
           'lst_closure_sales_app' => $lst_closure_sales_app
       );
       $display = view('callcenter.downloadclosuresalesapp',$data)->render();
       
       
         $pdf = App::make('snappy.pdf.wrapper');
        $pdf->setPaper('a4')->setOption('encoding', 'UTF-8')->loadHTML($display);
        return $pdf->inline();
     
    }
    
    
    /**
     * display list of appointments by date
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ListAppointmentsByDate(Request $request)
    {
        $current_date = $request->input('current_date');
        $display_type = $request->input('display_type');
         $result_array = array();
        if($current_date == null)
        {
            $current_date = date('Y-m-d');
        }
        
        $lst_apppointments = Appointments::whereCaIsDeleted(0)->whereCaAptDate($current_date)->get();
        
       $result_array['is_error'] = 0;
       $data = array(
           'lst_apppointments' => $lst_apppointments
       );
       $result_array['display'] = view('callcenter.displaylisttodaysapt',$data)->render();
        return Response()->json($result_array);
    }
    
    
    /**
     * Generate pdf and download report for list appointments
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateAppointmentsReport(Request $request)
    {
        $current_date = $request->input('ca_appointment_date');
        $display_type = $request->input('display_type');
         $result_array = array();
        if($current_date == null)
        {
            $current_date = date('Y-m-d');
        }
        
        $lst_apppointments = Appointments::whereCaIsDeleted(0)->whereCaAptDate($current_date)->get();
        
       $result_array['is_error'] = 0;
       $data = array(
           'lst_apppointments' => $lst_apppointments
       );
       $display = view('callcenter.documenttodaysapt',$data)->render();
       
       
          $pdf = App::make('snappy.pdf.wrapper');
        $pdf->setPaper('a4')->setOption('encoding', 'UTF-8')->loadHTML($display);
        return $pdf->inline();
    }
    
    
    
    /**
     * Display list of Appointments saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayListApp(Request $request)
    {
        $lead_id = $request->input('lead_id');
        $ld_apt_date = $request->input('ld_apt_date');
        $ld_from_apt_date = $request->input('ld_from_apt_date');
        $ld_to_apt_date = $request->input('ld_to_apt_date');
        $ca_salesman_id = $request->input('ca_salesman_id');
        
        $apt_cond = Appointments::whereCaIsDeleted(0);
        
        if($lead_id > 0 || $lead_id != '')
        {
            $apt_cond = $apt_cond->whereCaLeadId($lead_id);
        }
        
        if($ld_from_apt_date != '' && $ld_to_apt_date != '' )
        {
            $apt_cond = $apt_cond->whereBetween('ca_apt_date', [$ld_from_apt_date, $ld_to_apt_date]);
        }
        
        if($ld_apt_date != '')
        {
            $apt_cond = $apt_cond->whereCaAptDate($ld_apt_date);
        }
        
        if($ca_salesman_id != '0')
        {
            $apt_cond = $apt_cond->whereCaSalesmanId($ca_salesman_id);
        }
        
        
        $lst_lead_apps = $apt_cond->get();
        
        $result_array = array();
        $data = array(
            "lst_lead_apps" => $lst_lead_apps
        );
        $result_array['display'] = view("callcenter.listleadapps",$data)->render();

        return Response()->json($result_array);
    }
    
    
    /**
     * Get Appointment information for selected ID
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetAppointmentInformation(Request $request)
    {
        $result_array = array();
        $ca_id = $request->input('ca_id');
        $app_info = Appointments::find($ca_id);
        
        
        $appointment_array = array(
            'ca_id' => $app_info->ca_id,
            'ca_lead_id' => $app_info->ca_lead_id,
            'ca_salesman_id' => $app_info->ca_salesman_id,
            'ca_telemarketing_id' => $app_info->ca_telemarketing_id,
            'ca_apt_date' => $app_info->ca_apt_date,
            'ca_apt_time' => $app_info->ca_apt_time,
            'ca_apt_with' => $app_info->ca_apt_with,
            'ca_apt_job' => $app_info->ca_apt_job,
            'ca_apt_result' => $app_info->ca_apt_result,
            'ca_apt_notes' => $app_info->ca_apt_notes,
            'ca_lead_confirm' => $app_info->ca_lead_confirm,
            'ca_lead_address' => $app_info->ca_lead_address,
            'ca_apt_details' => $app_info->ca_apt_details,
            'ca_apt_notes' => $app_info->ca_apt_notes,
            'ca_apt_details' => $app_info->ca_apt_details,
            'ca_nbr_leads' => $app_info->ca_nbr_leads
        );
        
        $result_array['is_error'] = 0;
        $result_array['appointment_array'] = $appointment_array;
        return Response()->json($result_array);
    }
    
    
    
    public function CreateLeadAppointment( $lead_id )
    {
        //$lst_appt = Appointments::whereCaIsDeleted(0)->whereCaLeadId($lead_id)->get();
        $result_array =array();
        $leads_info = CRMLeads::find($lead_id);
        $lst_lead_types = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_countries      = Countries::all(); 
        $lst_appt_results      = ApptResults::whereArIsDeleted(0)->get(); 
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
         
        $data = array(
           "lead_id" => $lead_id,
           "lst_countries" => $lst_countries,
           "lst_telemarketing" => $lst_telemarketing,
           "lst_appt_results" => $lst_appt_results,
           "lst_sales" => $lst_sales,
           "lst_lead_types" => $lst_lead_types,
           "leads_info" => $leads_info
        );
        
        return Response()->view('callcenter.createappointment',$data);
    }
    
    

    
    /**
     * Save Cost Center Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAppointmentInfo(Request $request)
    {
        $ca_id                                      = $request->input('ca_id');
        $ca_lead_id                                 = $request->input('lead_id');
        $ca_salesman_id                             = $request->input('cl_sales_id');
        $ca_telemarketing_id                        = $request->input('cl_telemarketing_id');
        $ca_apt_date                                = $request->input('ca_apt_date');
        $ca_apt_time                                = $request->input('ca_apt_time');
        $ca_apt_with                                = $request->input('ca_apt_with');
        $ca_apt_job                                 = $request->input('ca_apt_job');
        $ca_apt_result                              = $request->input('ca_apt_result');
        $ca_apt_notes                               = $request->input('ca_apt_notes');
        $ca_lead_confirm                            = $request->input('ca_lead_confirm');
        $ca_lead_address                            = $request->input('ca_lead_address');
        $ca_apt_details                             = $request->input('ca_apt_details');
        $ca_nbr_leads                               = $request->input('ca_nbr_leads');
        $lead_info = CRMLeads::find($ca_lead_id);
        $lead_info->cl_lead_notes = "APP";
        $lead_info->cl_lead_notes = "APP";
        $lead_info->save();
        $result_array = array();
 
        
        $app_info = new Appointments();
        if( $ca_id != 0 && $ca_id != null)
        {
            $app_info = Appointments::find($ca_id);
        }
         
        $app_info->ca_lead_id                       = $ca_lead_id;
        $app_info->ca_salesman_id                   = $ca_salesman_id;
        $app_info->ca_telemarketing_id              = $ca_telemarketing_id;
        $app_info->ca_apt_date                      = $ca_apt_date;
        $app_info->ca_apt_time                      = $ca_apt_time;
        $app_info->ca_apt_with                      = $ca_apt_with;
        $app_info->ca_apt_job                       = $ca_apt_job;
        $app_info->ca_apt_result                    = $ca_apt_result;
        $app_info->ca_apt_notes                     = $ca_apt_notes;
        $app_info->ca_lead_confirm                  = $ca_lead_confirm;
        $app_info->ca_lead_address                  = $ca_lead_address;
        $app_info->ca_apt_details                   = $ca_apt_details;
        $app_info->ca_nbr_leads                     = $ca_nbr_leads;
        $app_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Appointment Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    public function DeleteAppointmentInfo(Request $request)
    {
        $ca_id = $request->input('ca_id');
        
        $apt_info = Appointments::find( $ca_id );
        $apt_info->ca_is_deleted          = 1;
        $apt_info->ca_deleted_by          = Session('user_id');
        $apt_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}