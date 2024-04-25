<?php
/***********************************************************
ServicesController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\CRM;

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
use App\models\CRM\CRMServiceCategories;
use App\library\ServiceCategoriesManager;
use App\models\CRM\CRMServices;
use App\models\Accounting\ChartAccounts;
use App\models\System\Currency;
use App\models\Billing\PaymentTypes;
use App\models\CRM\CRMServicesPaymentTypes;


class ServicesController extends Controller
{
    /**
     * Page to control Service Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('services.services',$data);
    }
    
    
    /**
     * Display list of Service
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $lst_services = CRMServices::whereCsIsDeleted(0)->get();
        
        $service_categories_array   = array();
        $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
        foreach ( $lst_service_categories as $key => $sc_info )
        {
            $service_categories_array[ $sc_info->sc_id ] =  $sc_info->sc_service_category;
        }
        
        $lst_currency = Currency::all();
        $currency_array = CreateDatabaseArrayByIndex($lst_currency, "cc_id");
        
        $data = array(
            "currency_array" => $currency_array,
            "lst_services" => $lst_services,
            "service_categories_array" => $service_categories_array
        );
        
        $result_array = array();
         
        $result_array['display'] = view("services.listservices",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display List of Payment Types and Selected Account related to every 
     * one in the selected services
     * 
     * @author Moe Mantach
     * @access public
     */
    public function ListPaymentTypes(Request $request)
    {
        $cs_id = $request->input('cs_id');
        
        $lst_srv_paymenttypes = CRMServicesPaymentTypes::whereStServiceId($cs_id)->get();
        
        
        $result_array = array();
        
        $data = array(
            "lst_srv_paymenttypes" => $lst_srv_paymenttypes
        );
        
        $result_array['display'] = view("services.lstsrvpaymenttypes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Payment Type Accounting for the Services
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SavePaymentType(Request $request)
    {
        $cs_id  = $request->input('cs_id');
        $st_id  = $request->input('st_id');
        $pt_payment_type = $request->input('pt_payment_type');
        $st_account_income_id   = $request->input('st_account_income_id');
        $st_account_purchase_id = $request->input('st_account_purchase_id');
        $result_array = array();
        
        
        $ptype_info = new CRMServicesPaymentTypes();
        
        if($st_id != '')
        {
            $ptype_info = CRMServicesPaymentTypes::find($st_id);
        }
        $ptype_info->st_service_id      = $cs_id;
        $ptype_info->st_payment_type_id = $pt_payment_type;
        $ptype_info->st_account_income_id= $st_account_income_id;
        $ptype_info->st_account_purchase_Id = $st_account_purchase_id;
        $ptype_info->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Payment Type Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    /**
     * get payment type information for selected row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetPaymentTypeInfo(Request $request)
    {
        $st_id  = $request->input('st_id');
        
        
        $ptype_info = CRMServicesPaymentTypes::find($st_id);
        
        
        
        $result_array['is_error']  = 0; 
        $result_array['pt_payment_type']  = $ptype_info->st_payment_type_id; 
        $result_array['st_account_income_id']  = $ptype_info->st_account_income_id; 
        $result_array['st_account_purchase_id']  = $ptype_info->st_account_purchase_Id; 
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Linked payment type to Selected Service
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeletePaymentType(Request $request)
    {
        $st_id = $request->input('st_id');
        
        CRMServicesPaymentTypes::find($st_id)->delete();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg']  = "Delete Payment Type"; 
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
        $lst_accounts               = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies             = Currency::all();
        $data = array(
            "lst_service_categories" => $lst_service_categories,
            "lst_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        return view('services.addservice',$data);
    }
    
    
    /**
     * Save Service Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveServiceInfo(Request $request)
    {
        $cs_id                          = $request->input('cs_id');
        $fk_category_id                 = $request->input('fk_category_id');
        $cs_service_code                = "SRV" . rand(99,99999);
        $cs_service_title               = $request->input('cs_service_title');
        $cs_service_description         = $request->input('cs_service_description');
        $cs_cost_per_hour               = $request->input('cs_cost_per_hour');
        $cs_sale_accounting_code        = $request->input('cs_sale_accounting_code');
        $cs_purchase_accounting_code    = $request->input('cs_purchase_accounting_code');
        $cs_currency_id                 = $request->input('cs_currency_id');
        $cs_validate_payment_type       = $request->has('cs_validate_payment_type') ? 1 : 0;
        
        $result_array = array();
 
        
        $Services = new CRMServices();
        if( $cs_id != null )
        {
            $Services = CRMServices::find($cs_id);
        }
        else 
        {
            $Services->cs_service_code                  = $cs_service_code;
        }
        
        $Services->fk_category_id                   = $fk_category_id;
        $Services->cs_service_title                 = $cs_service_title;
        $Services->cs_service_description           = $cs_service_description;
        $Services->cs_cost_per_hour                 = $cs_cost_per_hour;
        $Services->cs_sale_accounting_code          = $cs_sale_accounting_code;
        $Services->cs_purchase_accounting_code      = $cs_purchase_accounting_code;
        $Services->cs_currency_id                   = $cs_currency_id;
        $Services->cs_validate_payment_type         = $cs_validate_payment_type;
 
        
        $Services->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Service Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Service Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $cs_id
     */
    public function EditForm( $cs_id )
    {
        $services                   = CRMServices::find($cs_id);
        $lst_service_categories     = CRMServiceCategories::whereScIsDeleted(0)->get();
        $lst_accounts               = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_currencies             = Currency::all();
        
        
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        
        $data = array(
            "services" => $services,
            "lst_service_categories" => $lst_service_categories,
            "lst_payment_types" => $lst_payment_types,
            "lst_accounts" => $lst_accounts,
            "lst_currencies" => $lst_currencies
        );
        return view('services.editservice',$data);
    }
    
    
    /**
     * Delete Service information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteServiceInfo(Request $request)
    {
        
        $cs_id= $request->input('cs_id');
        
        $services = CRMServices::find( $cs_id);
        $services->cs_is_deleted          = 1;
        $services->cs_deleted_by          = Session('user_id');
        $services->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}