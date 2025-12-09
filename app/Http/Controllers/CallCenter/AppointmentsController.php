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
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadResults;
use App\models\CRM\CRMLeadStatus;
use App\models\System\Areas;
use App\models\System\Regions;
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
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;


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

        $default_company_id     = Session('default_company_id');
        $lst_lead_types         = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_countries          = Countries::all();
        $lst_appt_results       = ApptResults::whereArIsDeleted(0)->whereArAppShowApt(1)->get();
        $lst_telemarketing      = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales              = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_leads              = CRMLeads::whereClIsDeleted(0)->whereClCompanyId($default_company_id)->get();

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


    public function DownloadAppointment($apt_id)
    {
        $apt_info = Appointments::find($apt_id);


        $appointment = view('templates.aptsummary',array())->render();
        $appointment = str_replace("%date_apt%",$apt_info->ca_apt_date, $appointment);
        $appointment = str_replace("%apt_time%",$apt_info->ca_apt_time, $appointment);
        $appointment = str_replace("%client_with%",$apt_info->ca_apt_with, $appointment);
        $appointment = str_replace("%client_job%",$apt_info->ca_apt_job, $appointment);
        $appointment = str_replace("%client_area%",$apt_info->ca_lead_address, $appointment);
        $appointment = str_replace("%client_phone%",$apt_info->Lead->cl_mobile, $appointment);
        $appointment = str_replace("%app_details%",$apt_info->ca_apt_details, $appointment);
        $appointment = str_replace("%app_notes%",$apt_info->ca_apt_notes, $appointment);
        $appointment = str_replace("%number_of_leads%",$apt_info->ca_nbr_leads, $appointment);
        $appointment = str_replace("%client_referredby%",$apt_info->ca_lead_referred_by, $appointment);
        $appointment = str_replace("%sales_name%",$apt_info->Salesman->u_fullname, $appointment);
        $appointment = str_replace("%telemarketing_name%",$apt_info->Telemarketing->u_fullname, $appointment);
        $appointment = str_replace("%client_address%",$apt_info->ca_lead_address, $appointment);
        $appointment = str_replace("%lead_type%",$apt_info->Lead->LeadType->lt_deal_type, $appointment);
        if($apt_info->AppResult)
            $appointment = str_replace("%app_result%",$apt_info->AppResult->ar_app_result, $appointment);
        else
            $appointment = str_replace("%app_result%","", $appointment);
        $appointment = str_replace("%is_confirmed%","Confirmed", $appointment);
        if(strlen($apt_info->ca_lead_fullname) > 0)
            $appointment = str_replace("%lead_name%",(strtolower($apt_info->ca_lead_fullname)), $appointment);
        else
            $appointment = str_replace("%lead_name%",(strtolower($apt_info->Lead->cl_first_name) . " " . strtolower($apt_info->Lead->cl_last_name)), $appointment);

        return PDF::loadHTML($appointment)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->download((strtolower($apt_info->Lead->cl_first_name) . " " . strtolower($apt_info->Lead->cl_last_name)) . '.pdf');
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
        $default_company_id     = Session('default_company_id');

        $lst_sales = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();

        $data = array(
            "lst_sales" => $lst_sales
        );
        return Response()->view('callcenter.closureapp',$data);
    }



    public function DisplayClosureSalesmanApp(Request $request)
    {
        $default_company_id     = Session('default_company_id');

        $lst_sales              = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_apt_results              = ApptResults::whereArAppShowApt(1)->get();
        $total_results_app = array();
        $sales_array = array();
        $aptres_array = array();
        $percentage_colsure_array = array();

        $cl_sales_id = $request->input('cl_sales_id', 0);
        $ca_apt_from_date = $request->input('ca_apt_from_date', '');
        $ca_apt_last_date = $request->input('ca_apt_last_date', '');

        // 🧱 Build dynamic WHERE clause
        $where = "WHERE 1=1";

        if ($cl_sales_id > 0) {
            $where .= " AND lapp.ca_salesman_id = " . intval($cl_sales_id);
        }

        if (strlen($ca_apt_from_date) > 0 && strlen($ca_apt_last_date) > 0) {
            $where .= " AND lapp.ca_apt_date BETWEEN '" . $ca_apt_from_date . "' AND '" . $ca_apt_last_date . "'";
        }

        // 🧮 Dynamic columns from crm_lead_app_results
        $cols = DB::table('crm_lead_app_results')
            ->where('ar_app_show_apt', 1)
            ->selectRaw("GROUP_CONCAT(DISTINCT
            CONCAT('SUM(CASE WHEN lapp.ca_apt_result = ', ar_id,
                   ' THEN 1 ELSE 0 END) AS `', ar_app_result, '`') SEPARATOR ', ') AS cols")
            ->value('cols');

        // 🧾 Final dynamic SQL with number of leads
        $sql = "
        SELECT
            u.id AS salesman_id,
            u.u_fullname AS salesman_name,
            COUNT(lapp.ca_id) AS total_app,
            $cols,
            ROUND(SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) / COUNT(lapp.ca_id), 2) * 100 AS closing_average,
            SUM(lapp.ca_nbr_leads) AS number_of_leads
        FROM callcenter_lead_appointments AS lapp
        LEFT JOIN users AS u ON lapp.ca_salesman_id = u.id
        LEFT JOIN crm_lead_app_results AS res ON lapp.ca_apt_result = res.ar_id
        $where
        GROUP BY u.id, u.u_fullname
        ORDER BY closing_average DESC
    ";

        // Execute and return
        $lst_closing_res = DB::select($sql);

        $data = array(
            'lst_closing_res' => $lst_closing_res
        );

       $result_array['display'] = view('callcenter.displayclosuresalesapp',$data)->render();
        return Response()->json($result_array);

    }


    public function DownloadClosureSalesApp(Request $request)
    {
 $cl_sales_id = $request->input('cl_sales_id');

        $default_company_id     = Session('default_company_id');

        $lst_sales              = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_apt_results              = ApptResults::whereArAppShowApt(1)->get();
        $total_results_app = array();
        $sales_array = array();
        $aptres_array = array();
        $percentage_colsure_array = array();

        $cl_sales_id = $request->input('cl_sales_id', 0);
        $ca_apt_from_date = $request->input('ca_apt_from_date', '');
        $ca_apt_last_date = $request->input('ca_apt_last_date', '');

        // 🧱 Build dynamic WHERE clause
        $where = "WHERE ca_lead_confirm = 1";

        if ($cl_sales_id > 0) {
            $where .= " AND lapp.ca_salesman_id = " . intval($cl_sales_id);
        }

        if (strlen($ca_apt_from_date) > 0 && strlen($ca_apt_last_date) > 0) {
            $where .= " AND lapp.ca_apt_date BETWEEN '" . $ca_apt_from_date . "' AND '" . $ca_apt_last_date . "'";
        }

        // 🧮 Dynamic columns from crm_lead_app_results
        $cols = DB::table('crm_lead_app_results')
            ->where('ar_app_show_apt', 1)
            ->selectRaw("GROUP_CONCAT(DISTINCT
            CONCAT('SUM(CASE WHEN lapp.ca_apt_result = ', ar_id,
                   ' THEN 1 ELSE 0 END) AS `', ar_app_result, '`') SEPARATOR ', ') AS cols")
            ->value('cols');

        // 🧾 Final dynamic SQL with number of leads
        $sql = "
        SELECT
            u.id AS salesman_id,
            u.u_fullname AS salesman_name,
            COUNT(lapp.ca_id) AS total_app,
            $cols,
            ROUND(SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) / COUNT(lapp.ca_id), 2)  * 100 AS closing_average,
            SUM(lapp.ca_nbr_leads) AS number_of_leads
        FROM callcenter_lead_appointments AS lapp
        LEFT JOIN users AS u ON lapp.ca_salesman_id = u.id
        LEFT JOIN crm_lead_app_results AS res ON lapp.ca_apt_result = res.ar_id
        $where
        GROUP BY u.id, u.u_fullname
        ORDER BY closing_average DESC
    ";

        // ⚙️ Execute and return
        $lst_closing_res = DB::select($sql);

        $data = array(
            'lst_closing_res' => $lst_closing_res
        );
       $display = view('callcenter.downloadclosuresalesapp',$data)->render();


         $pdf = App::make('snappy.pdf.wrapper');
        $pdf->setPaper('a4')->setOption('encoding', 'UTF-8')->loadHTML($display);
        return $pdf->inline();

    }


    /**
     * Telemarketing Report
     * @param Request $request
     * @return void
     */
    public function telemarketerAppointmentsReport(Request $request)
    {
        // Date range (default current month)
        $from = $request->input('from', date('Y-m-01'));
        $to = $request->input('to', date('Y-m-t'));

        $sql = "SELECT
                u.u_fullname AS telemarketer,
                ROUND(SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) / NULLIF(COUNT(lapp.ca_id), 0), 2) AS average,
                COUNT(lapp.ca_id) AS app,
                SUM(CASE WHEN lapp.ca_lead_confirm = 1 THEN 1 ELSE 0 END) AS confirmed_app,
                SUM(CASE WHEN lapp.ca_lead_confirm = 0 THEN 1 ELSE 0 END) AS pending_app,
                SUM(CASE WHEN res.ar_app_result = 'DEMO' THEN 1 ELSE 0 END) AS demo,
                SUM(CASE WHEN res.ar_app_result = 'CANCEL' THEN 1 ELSE 0 END) AS cancel,
                SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) AS sold,
                SUM(CASE WHEN res.ar_app_result = 'RESET' THEN 1 ELSE 0 END) AS reset,
                SUM(CASE WHEN res.ar_app_result = 'RESET DA' THEN 1 ELSE 0 END) AS reset_da
            FROM callcenter_lead_appointments AS lapp
            LEFT JOIN users AS u
                ON lapp.ca_telemarketing_id = u.id
            LEFT JOIN crm_lead_app_results AS res
                ON lapp.ca_apt_result = res.ar_id
            WHERE lapp.ca_apt_date BETWEEN ? AND ?
            GROUP BY u.id, u.u_fullname

            UNION ALL

            SELECT
                'Total Marketing' AS telemarketer,
                ROUND(SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) / NULLIF(COUNT(lapp.ca_id), 0), 2) AS average,
                COUNT(lapp.ca_id) AS app,
                SUM(CASE WHEN lapp.ca_lead_confirm = 1 THEN 1 ELSE 0 END) AS confirmed_app,
                SUM(CASE WHEN lapp.ca_lead_confirm = 0 THEN 1 ELSE 0 END) AS pending_app,
                SUM(CASE WHEN res.ar_app_result = 'DEMO' THEN 1 ELSE 0 END) AS demo,
                SUM(CASE WHEN res.ar_app_result = 'CANCEL' THEN 1 ELSE 0 END) AS cancel,
                SUM(CASE WHEN res.ar_app_result = 'SOLD' THEN 1 ELSE 0 END) AS sold,
                SUM(CASE WHEN res.ar_app_result = 'RESET' THEN 1 ELSE 0 END) AS reset,
                SUM(CASE WHEN res.ar_app_result = 'RESET DA' THEN 1 ELSE 0 END) AS reset_da
            FROM callcenter_lead_appointments AS lapp
            LEFT JOIN crm_lead_app_results AS res
                ON lapp.ca_apt_result = res.ar_id
            WHERE lapp.ca_apt_date BETWEEN ? AND ?
        ";


        $results = DB::select($sql, [$from, $to, $from, $to]);
        return view('reports.telemarketer-appointments', compact('results', 'from', 'to'));
    }


    /**
     * Report Forcasting Lead Numbers for current month
     * @param Request $request
     * @return void
     */
    public function ForcastingLeadsNumber(Request $request)
    {
        $sql = <<<SQL
WITH
-- 1️⃣ Average daily leads over the last 3 months
historical_avg AS (
    SELECT
        ROUND(SUM(1) / COUNT(DISTINCT DATE(cl_date_creation)), 2) AS avg_daily_leads
    FROM crm_leads
    WHERE cl_date_creation BETWEEN DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 3 MONTH)
                              AND LAST_DAY(DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH))
),

-- 2️⃣ Generate a calendar of all days for current month
calendar AS (
    SELECT DATE_FORMAT(CURDATE(), '%Y-%m-01') + INTERVAL n.n DAY AS report_date
    FROM (
        SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
        UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11
        UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17
        UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23
        UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30
    ) AS n
    WHERE DATE_FORMAT(CURDATE(), '%Y-%m-01') + INTERVAL n.n DAY <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-%m-01'))
),

-- 3️⃣ Actual daily leads for current month
daily_actuals AS (
    SELECT
        DATE(cl_date_creation) AS report_date,
        COUNT(*) AS total_leads
    FROM crm_leads
    WHERE cl_date_creation BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())
    GROUP BY DATE(cl_date_creation)
)

-- 4️⃣ Final Forecast Output
SELECT
    c.report_date,
    COALESCE(a.total_leads, 0) AS actual_leads,
    ROUND(h.avg_daily_leads, 2) AS expected_daily_leads,
    ROUND(h.avg_daily_leads * DAY(c.report_date), 0) AS cumulative_expected_leads
FROM calendar AS c
LEFT JOIN daily_actuals AS a ON c.report_date = a.report_date
CROSS JOIN historical_avg AS h
ORDER BY c.report_date;
SQL;

        $results = DB::select($sql);

        // If you want to return JSON for API
        // return response()->json($results);

        // Or render a Blade view
        return view('reports.forecast-daily-leads', compact('results'));
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
        $default_company_id     = Session('default_company_id');
         $result_array = array();
        if($current_date == null)
        {
            $current_date = date('Y-m-d');
        }

        $lst_apppointments = Appointments::whereCaIsDeleted(0)->whereCaCompanyId($default_company_id)->whereCaAptDate($current_date)->whereCaLeadConfirm(1)->get();

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
        $default_company_id     = Session('default_company_id');
         $result_array = array();
        if($current_date == null)
        {
            $current_date = date('Y-m-d');
        }

        $lst_apppointments = Appointments::whereCaIsDeleted(0)->whereCaCompanyId($default_company_id)->whereCaAptDate($current_date)->get();

       $result_array['is_error'] = 0;
       $data = array(
           'lst_apppointments' => $lst_apppointments
       );
       $display = view('callcenter.documenttodaysapt',$data)->render();


          $pdf = App::make('snappy.pdf.wrapper');
        $pdf->setPaper('a4')->setOption('encoding', 'UTF-8')->loadHTML($display);
        return $pdf->inline();
    }


    public function CallBackReports(Request $request)
    {
        $default_company_id     = Session('default_company_id');
        $lead_categories = CRMClientCategories::whereCcIsDeleted(0)->get();
        $lead_statuses  = CRMLeadStatus::whereLsIsDeleted(0)->get();
        $lst_users  = Users::whereUIsActive(1)->whereFkCompanyId($default_company_id)->whereUIsDeleted(0)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->whereFkCompanyId($default_company_id)->get();
        $lst_appt_results      = ApptResults::whereArIsDeleted(0)->get();
        $lst_lead_types      = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_leads = CRMLeads::whereClIsDeleted(0)->whereClLeadResults(2)->whereClCompanyId($default_company_id)->where('cl_next_call_date','<=',date('Y-m-d'))->get();


        $lst_areas              = Areas::all();
        $lst_regions             = Regions::all();

        $data = array(
            "lead_categories" => $lead_categories,
            "lst_appt_results" => $lst_appt_results,
            "lst_lead_types" => $lst_lead_types,
            "lst_areas" => $lst_areas,
            "lst_regions" => $lst_regions,
            "lst_users" => $lst_users,
            "lst_sales" => $lst_sales,
            "lst_leads" => $lst_leads,
            "lead_statuses" => $lead_statuses
        );
        return Response()->view("callcenter.reportcallbackleads",$data);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function DisplayListCallbackReport(Request $request)
    {
        $default_company_id     = Session('default_company_id');
        $lead_name = $request->get('lead_name');
        $referred_by = $request->get('referred_by');
        $lead_mobile = $request->get('lead_mobile');
        $sheet_number = $request->get('sheet_number');
        $cl_area = $request->get('cl_area');
        $cl_region = $request->get('cl_region');
        $cl_sales_id = $request->get('cl_sales_id');
        $cl_lead_types = $request->get('cl_lead_types');
        $cl_date = $request->get('cl_date');
        $leads_cond = CRMLeads::whereClIsDeleted(0)->whereClLeadResults(2)->whereClCompanyId($default_company_id);


        if(strlen($cl_date) > 0)
        {
            $leads_cond =   $leads_cond->where('cl_next_call_date','<=',$cl_date);
        }

        if( $cl_lead_types > 0 )
        {
            $leads_cond = $leads_cond->whereClLeadTypeId($cl_lead_types);
        }


        if( $cl_sales_id > 0 )
        {
            $leads_cond = $leads_cond->whereClSalesId($cl_sales_id);
        }


        if( strlen($sheet_number)  > 0) {
            $leads_cond = $leads_cond->where('cl_sheet_number', '=', $sheet_number);
        }



        if( strlen($lead_name)  > 0) {
            $leads_cond = $leads_cond->where('cl_first_name','LIKE','%' . $lead_name . '%');
            $leads_cond = $leads_cond->orWhere('cl_last_name','LIKE','%' . $lead_name . '%');
        }

        if( strlen($lead_mobile)  > 0) {
            $leads_cond = $leads_cond->where('cl_mobile','LIKE','%' . $lead_mobile . '%');
        }

        if( $cl_area  > 0) {
            $leads_cond = $leads_cond->where('cl_area','LIKE','%' . $cl_area . '%');
        }

        if( ($cl_region)  > 0) {
            $leads_cond = $leads_cond->where('cl_region','LIKE','%' . $cl_region . '%');
        }

        if( strlen($referred_by)  > 0) {
            $leads_cond = $leads_cond->where('cl_referred_by','LIKE','%' . $referred_by . '%');
        }




         $lst_leads =   $leads_cond->get();

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Operation Completed Successfully';
        $data = array(
            'lst_leads' => $lst_leads
        );
        $display = view('callcenter.lstreportcbleads',$data)->render();
        $result_array['display'] = $display;

        return Response()->json($result_array);
    }


    public function DownloadListCallbackLeads(Request $request)
    {
        $default_company_id     = Session('default_company_id');
        $lead_name = $request->get('lead_name');
        $referred_by = $request->get('referred_by');
        $lead_mobile = $request->get('lead_mobile');
        $sheet_number = $request->get('sheet_number');
        $cl_area = $request->get('cl_area');
        $cl_region = $request->get('cl_region');
        $cl_sales_id = $request->get('cl_sales_id');
        $cl_lead_types = $request->get('cl_lead_types');
        $cl_date = $request->get('cl_date');
        $leads_cond = CRMLeads::whereClIsDeleted(0)->whereClLeadResults(2)->whereClCompanyId($default_company_id)->where('cl_next_call_date','<=',$cl_date);


        if( $cl_lead_types > 0 )
        {
            $leads_cond = $leads_cond->whereClLeadTypeId($cl_lead_types);
        }


        if( $cl_sales_id > 0 )
        {
            $leads_cond = $leads_cond->whereClSalesId($cl_sales_id);
        }


        if( strlen($sheet_number)  > 0) {
            $leads_cond = $leads_cond->where('cl_sheet_number', '=', $sheet_number);
        }



        if( strlen($lead_name)  > 0) {
            $leads_cond = $leads_cond->where('cl_first_name','LIKE','%' . $lead_name . '%');
            $leads_cond = $leads_cond->orWhere('cl_last_name','LIKE','%' . $lead_name . '%');
        }

        if( strlen($lead_mobile)  > 0) {
            $leads_cond = $leads_cond->where('cl_mobile','LIKE','%' . $lead_mobile . '%');
        }

        if( $cl_area  > 0) {
            $leads_cond = $leads_cond->where('cl_area','LIKE','%' . $cl_area . '%');
        }

        if( strlen($cl_region)  > 0) {
            $leads_cond = $leads_cond->where('cl_region','LIKE','%' . $cl_region . '%');
        }

        if( strlen($referred_by)  > 0) {
            $leads_cond = $leads_cond->where('cl_referred_by','LIKE','%' . $referred_by . '%');
        }



        $lst_leads =   $leads_cond->get();

        $data = array(
            'lst_leads' => $lst_leads
        );
        $display = view('callcenter.downloadcallbackreport',$data)->render();


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
        $ap_apt_result = $request->input('ap_apt_result');
        $phone_number = $request->input('phone_number');
        $default_company_id     = Session('default_company_id');

        $apt_cond = Appointments::whereCaIsDeleted(0)->whereCaCompanyId($default_company_id)->leftJoin('crm_leads', 'callcenter_lead_appointments.ca_lead_id', '=', 'crm_leads.cl_id');

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
        if($phone_number != '')
        {
            $apt_cond = $apt_cond->where('cl_mobile','LIKE','%'.$phone_number.'%');
        }

        if($ca_salesman_id != '0')
        {
            $apt_cond = $apt_cond->whereCaSalesmanId($ca_salesman_id);
        }

        if($ap_apt_result != '')
        {
            $apt_cond = $apt_cond->whereCaAptResult($ap_apt_result);
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
            'cl_full_name' => $app_info->ca_lead_fullname,
            'cl_lead_type_id' => $app_info->Lead->cl_lead_type_id,
            'ca_apt_time' => $app_info->ca_apt_time,
            'ca_apt_with' => $app_info->ca_apt_with,
            'ca_apt_job' => $app_info->ca_apt_job,
            'ca_apt_result' => $app_info->ca_apt_result,
            'ca_lead_confirm' => $app_info->ca_lead_confirm,
            'ca_lead_address' => $app_info->ca_lead_address,
            'ca_apt_notes' => $app_info->ca_apt_notes,
            'ca_apt_details' => $app_info->ca_apt_details,
            'cl_mobile' => $app_info->Lead->cl_mobile,
            'cl_region' => $app_info->Lead->cl_region,
            'cl_area' => $app_info->Lead->cl_area,
            'cl_referred_by' => $app_info->Lead->cl_referred_by,
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
        $default_company_id     = Session('default_company_id');
        $leads_info = CRMLeads::find($lead_id);
        $lst_lead_types = CRMLeadTypes::whereLtIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lst_appt_results      = ApptResults::whereArIsDeleted(0)->get();
        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereFkCompanyId($default_company_id)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();

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
        $cl_referred_by                               = $request->input('cl_referred_by');
        $cl_phone                               = $request->input('cl_phone');
        $cl_full_name                               = $request->input('cl_full_name');
        $default_company_id     = Session('default_company_id');
        $lead_info = CRMLeads::find($ca_lead_id);
        $lead_info->cl_lead_results = $ca_apt_result;
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
        $app_info->ca_lead_fullname                  = $cl_full_name;
        $app_info->ca_lead_phone                    = $cl_phone;
        $app_info->ca_lead_referred_by              = $cl_referred_by;
        $app_info->ca_company_id                    = $default_company_id;
        $app_info->save();

        // change result of lead based of appointment change
        $lead_info = CRMLeads::find($ca_lead_id);
        $lead_info->cl_lead_results = $ca_apt_result;
        $lead_info->save();

        // add callcenter result
        $lead_results                             = new CRMLeadResults();
        $lead_results->lr_lead_id                 = $ca_lead_id;
        $lead_results->lr_text_result             = $ca_apt_result;
        $lead_results->lr_text_notes             = $ca_apt_notes;
        $lead_results->lr_result_date               = $ca_apt_date;
        $lead_results->lr_telemarketing_id        = Session('user_id');
        $lead_results->lr_sales_id                = $ca_salesman_id;
        $lead_results->save();


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
