<?php
/***********************************************************
HolidaysController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 5, 2019
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
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Timesheet\EmploymentType;
use App\models\Timesheet\Holidays;
use App\models\Timesheet\PersonalHolidays;



class HolidaysController extends Controller
{

    /**
     * Page to control company Holidays Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('timesheet.companyholidays',$data);
    }
    
    
    /**
     * Display list of Company Holidays saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $year                   = $request->input('holiday_year');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;
            
          $holidays_count = Holidays::whereThIsDeleted(0)->whereThYear($year)->count();
        
        
          $total_pages = ceil( $holidays_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $company_holidays = Holidays::whereThIsDeleted(0)->whereThYear($year)->skip($skip)->take($nbr_rows_per_pages)->get();
        
        $data = array(
            "company_holidays" => $company_holidays
        );
         
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("timesheet.listholidays",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function to display add new holiday form
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
         
        $data = array();
        return view('timesheet.addholiday',$data);
    }
    
    
    /**
     * Save yearly holiday to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveHolidayInfo(Request $request)
    {
        $th_id                      = $request->input('th_id');
        $th_year                    = $request->input('th_year');
        $th_country_id              = $request->input('th_country_id');
        $th_holiday_name            = $request->input('th_holiday_name');
        $th_holiday_date            = $request->input('th_holiday_date');
        $th_holiday_date            = date("Y-m-d",strtotime($th_holiday_date));
        
        $result_array = array();
 
        
        $holidays = new Holidays();
        if($th_id!= null)
        {
            $holidays = Holidays::find($th_id);
        }
         
        $holidays->th_year          = $th_year;
        $holidays->th_country_id    = session("company_country"); 
        $holidays->th_holiday_name  = $th_holiday_name; 
        $holidays->th_holiday_date  = $th_holiday_date; 
        
        $holidays->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Yearly Holidays Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit holiday Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $th_id
     */
    public function EditForm( $th_id )
    {
        $yearly_holiday        = Holidays::find($th_id); 
        $data = array(
            "yearly_holiday" => $yearly_holiday
        );
        return view('timesheet.editholiday',$data);
    }
    
    
    /**
     * Delete Holiday information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteHolidayInfo(Request $request)
    {
        
        $th_id= $request->input('th_id');
         
        $yearly_holidays = Holidays::find( $th_id);
        $yearly_holidays->th_is_deleted          = 1;
        $yearly_holidays->th_deleted_by          = Session('user_id');
        $yearly_holidays->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Page to Show Personal Holidays for current loggedIn User
     * 
     * @author Moe Mantach
     * @access public
     */
    public function PersonalHolidays()
    {
        $user_id    = session("user_id");
        $year       = date('Y');
        $personal_holidays = PersonalHolidays::wherePhUserId($user_id)->whereYear('ph_holiday_date',$year)->get();
        
        $data = array(
            'personal_holidays' => $personal_holidays
        );
        return Response()->view("timesheet.personalholidays",$data);
    }

}