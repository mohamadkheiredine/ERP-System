<?php
/***********************************************************
CompaniesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\System;

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
use App\Library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\System\Companies;
use App\models\System\Countries;
use App\Library\CompaniesManager;
use App\models\System\Currency;
use App\models\Accounting\VatAccounts;



class CompaniesController extends Controller
{

    /**
     * Page to control Companies Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('system.companies',$data);
    }
    
    
    /**
     * Display list of Companies saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        
        $data = array(
            "lst_companies" => $lst_companies
        );
        
        $result_array = array();
        $result_array['display'] = view("system.listcompanies",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Company Info
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_countries = Countries::all();
        $lst_currencies = Currency::all();
        $lst_taxes      = VatAccounts::whereAvIsDeleted(0)->get();
        
        $data = array(
            "lst_countries" => $lst_countries,
            "lst_taxes" => $lst_taxes,
            "lst_currencies" => $lst_currencies,
        );
        return view('system.addcompany',$data);
    }
    
    
    /**
     * Save Company Details Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveCompanyInfo(Request $request)
    {
        $cd_id                      = $request->input('cd_id');
        $cd_company_name            = $request->input('cd_company_name');
        $cd_about_company           = $request->input('cd_about_company');
        $cd_company_owner           = $request->input('cd_company_owner');
        $cd_company_phone           = $request->input("cd_company_phone");
        $cd_company_mobile          = $request->input("cd_company_mobile");
        $cd_company_email           = $request->input("cd_company_email");
        $cd_company_website         = $request->input("cd_company_website");
        $cd_company_currency        = $request->input("cd_company_currency");
        $cd_company_country         = $request->input("cd_company_country");
        $cd_primary_company         = $request->input("cd_primary_company");
        $cd_company_address         = $request->input("cd_company_address");
        $cd_contact_name            = $request->input("cd_contact_name");
        $cd_contact_mobile          = $request->input("cd_contact_mobile");
        $cd_contact_email           = $request->input("cd_contact_email");
        $cd_transportation_fees     = $request->input("cd_transportation_fees");
        $cd_company_tax             = $request->input('cd_company_tax'); 
        $cd_default_item            = $request->input('cd_default_item');
        $cd_secondary_currency      = $request->input('cd_secondary_currency');
        $cd_company_homepage        = $request->input('cd_company_homepage');
       
        $result_array = array(); 
        $CompanyManager  = new CompaniesManager();
        $cd_logo_base_src       = "";
        $cd_logo_file_name      = "";
        $cd_logo_file_extension = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $CompanyManager->UploadCompanyLogo(null);
            
            $cd_logo_base_src           = $image_data['data']['cd_logo_base_src'];
            $cd_logo_file_name          = $image_data['data']['cd_logo_file_name'];
            $cd_logo_file_extension     = $image_data['data']['cd_logo_file_extension'];
            
        }
        
        // check if the company is primary we need to make sure that we don't have any other
        // compoany is pramiry because we cannot have 2 companies primary
        if($cd_primary_company == 1 )
        {
            $CheckPrimary       = Companies::whereCdPrimaryCompany(1);
            $CheckPrimaryObj    = $CheckPrimary;
            if( $cd_id != null )
            {
                $CheckPrimaryObj = $CheckPrimary->where('cd_id',"!=",$cd_id);
            }
            $count = $CheckPrimaryObj->count();
            
            if($count > 0)
            {
                $result_array['is_error']  = 1;
                $result_array['error_msg'] = 'We cannot put a Company As Primary Twice !!';
                
                return Response()->json($result_array);
            }
        }
        
        $CompanyDetails = new Companies();
        if( $cd_id != null )
        {
            $CompanyDetails = Companies::find( $cd_id );
        }
        
        $CompanyDetails->cd_company_name            = $cd_company_name;
        $CompanyDetails->cd_about_company           = $cd_about_company;
        $CompanyDetails->cd_company_owner           = $cd_company_owner;
        $CompanyDetails->cd_company_phone           = $cd_company_phone;
        $CompanyDetails->cd_company_mobile          = $cd_company_mobile;
        $CompanyDetails->cd_company_email           = $cd_company_email;
        $CompanyDetails->cd_company_website         = $cd_company_website;
        $CompanyDetails->cd_company_currency        = $cd_company_currency;
        $CompanyDetails->cd_company_country         = $cd_company_country;
        $CompanyDetails->cd_primary_company         = $cd_primary_company;
        $CompanyDetails->cd_company_address         = $cd_company_address;
        $CompanyDetails->cd_contact_name            = $cd_contact_name;
        $CompanyDetails->cd_contact_mobile          = $cd_contact_mobile;
        $CompanyDetails->cd_contact_email           = $cd_contact_email;
        $CompanyDetails->cd_transportation_fees     = $cd_transportation_fees;
        $CompanyDetails->cd_company_tax             = $cd_company_tax;
        $CompanyDetails->cd_secondary_currency      = $cd_secondary_currency;
        $CompanyDetails->cd_default_item            = $cd_default_item;
        $CompanyDetails->cd_company_homepage        = $cd_company_homepage;
        
        if(strlen($cd_logo_base_src) > 0)
        {
            $CompanyDetails->cd_logo_base_src          = $cd_logo_base_src;
            $CompanyDetails->cd_logo_file_name         = $cd_logo_file_name;
            $CompanyDetails->cd_logo_file_extension    = $cd_logo_file_extension;
        }
        
        $CompanyDetails->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Company Details Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Company Detail Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $cd_id
     */
    public function EditForm( $cd_id )
    {
        $company_info  = Companies::find($cd_id);
        $lst_countries = Countries::all();
        $lst_currencies = Currency::all();
        $lst_taxes      = VatAccounts::whereAvIsDeleted(0)->get();
        
        $data = array(
            "company_info" => $company_info,
            "lst_taxes" => $lst_taxes,
            "lst_countries" => $lst_countries,
            "lst_currencies" => $lst_currencies
        );
        return view('system.editcompany',$data);
    }
    
    
    /**
     * Delete Company information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteCompanyInfo(Request $request)
    {
        
        $cd_id= $request->input('cd_id');
         
        $company = Companies::find( $cd_id);
        $company->cd_is_deleted          = 1;
        $company->cd_deleted_by          = Session('user_id');
        $company->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}