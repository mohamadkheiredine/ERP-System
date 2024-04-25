<?php
/***********************************************************
ShipCompaniesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\Logistics;

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
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\System\Companies;
use App\models\System\Countries;
use App\library\CompaniesManager;
use App\models\System\Currency;
use App\models\Logistics\Vehicules;
use App\library\VehiculesManager;
use App\models\Logistics\VehiculeTypes;
use App\models\Logistics\ShipCompanies;
use App\library\ShipCompaniesManager;



class ShipCompaniesController extends Controller
{
    
    /**
     * Page to control Shipment Companies Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('logistics.shipcompanies',$data);
    }
    
    
    /**
     * Display list of vehicules saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;
                
                $shipcompanies_count = ShipCompanies::whereScIsDeleted(0)->count();
                
                
                $total_pages = ceil( $shipcompanies_count /$nbr_rows_per_pages );
                $total_pages = intval($total_pages);
                
                $lst_shipcompanies = ShipCompanies::whereScIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
                
                $data = array(
                    "lst_shipcompanies" => $lst_shipcompanies
                );
                
                $result_array = array();
                
                $result_array['total_pages'] = $total_pages;
                $result_array['display'] = view("logistics.listshipcompanies",$data)->render();
                
                return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Company
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_currencies = Currency::all()->sortBy('cc_currency_name');
        $lst_countries = Countries::all()->sortBy('name');
        $data = array(
            "lst_currencies" => $lst_currencies,
            "lst_countries" => $lst_countries
        );
        return view('logistics.addshipcompanies',$data);
    }
    
    
    /**
     * Save Shipment Company Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveCompanyInfo(Request $request)
    {
        $sc_id                      = $request->input('sc_id');
        $sc_company_name            = $request->input('sc_company_name');
        $sc_company_owner           = $request->input('sc_company_owner');
        $sc_about_company           = $request->input('sc_about_company');
        $sc_company_country         = $request->input('sc_company_country');
        $sc_company_address         = $request->input("sc_company_address");
        $sc_company_website         = $request->input("sc_company_website");
        $sc_company_email           = $request->input("sc_company_email");
        $sc_company_phone           = $request->input("sc_company_phone");
        $sc_company_mobile          = $request->input("sc_company_mobile");
        $sc_company_fax             = $request->input("sc_company_fax");
        $sc_company_currency        = $request->input("sc_company_currency");
        $sc_company_rate            = $request->input("sc_company_rate");
        $sc_rate_type               = $request->input("sc_rate_type");
        
        $result_array = array();
        
        $shipCompanies  = new ShipCompaniesManager();
        $sc_logo_base_src       = "";
        $sc_logo_file_name      = "";
        $sc_logo_file_extension = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $shipCompanies->UploadShipCompanyLogo(null);
            
            $sc_logo_base_src           = $image_data['data']['sc_logo_base_src'];
            $sc_logo_file_name          = $image_data['data']['sc_logo_file_name'];
            $sc_logo_file_extension     = $image_data['data']['sc_logo_file_extension'];
            
        }
        
        $ShipCompanies = new ShipCompanies();
        if( $sc_id != null )
        {
            $ShipCompanies = ShipCompanies::find( $sc_id );
        }
        
        $ShipCompanies->sc_company_name     = $sc_company_name;
        $ShipCompanies->sc_company_owner    = $sc_company_owner;
        $ShipCompanies->sc_about_company    = $sc_about_company;
        $ShipCompanies->sc_company_country  = $sc_company_country;
        $ShipCompanies->sc_company_address  = $sc_company_address;
        $ShipCompanies->sc_company_website  = $sc_company_website;
        $ShipCompanies->sc_company_email    = $sc_company_email;
        $ShipCompanies->sc_company_phone    = $sc_company_phone;
        $ShipCompanies->sc_company_mobile   = $sc_company_mobile;
        $ShipCompanies->sc_company_fax      = $sc_company_fax;
        $ShipCompanies->sc_company_currency = $sc_company_currency;
        $ShipCompanies->sc_company_rate     = $sc_company_rate;
        $ShipCompanies->sc_rate_type        = $sc_rate_type;
        
        if(strlen($sc_logo_base_src) > 0)
        {
            $ShipCompanies->sc_logo_base_src        = $sc_logo_base_src;
            $ShipCompanies->sc_logo_file_name       = $sc_logo_file_name;
            $ShipCompanies->sc_logo_file_extension  = $sc_logo_file_extension;
        }
        
        $ShipCompanies->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Shipment Company Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Shipment Company Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param Integer $sc_id
     */
    public function EditForm( $sc_id )
    {
        $shipcompany_info   = ShipCompanies::find($sc_id);
        $lst_currencies = Currency::all()->sortBy('cc_currency_name');
        $lst_countries = Countries::all()->sortBy('name');
        
        $data = array(
            "lst_currencies" => $lst_currencies,
            "shipcompany_info" => $shipcompany_info,
            "lst_countries" => $lst_countries
        );
        return view('logistics.editshipcompany',$data);
    }
    
    
    /**
     * Delete shipment company information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteShipmentCompanyInfo(Request $request)
    {
        
        $sc_id= $request->input('sc_id');
        
        $shipment_companies = ShipCompanies::find( $sc_id );
        $shipment_companies->sc_is_deleted      = 1;
        $shipment_companies->sc_deleted_by      = Session('user_id');
        $shipment_companies->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}