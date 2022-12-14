<?php
/***********************************************************
PhoneUnitsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 31, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/




namespace App\Http\Controllers\Phones;

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
use App\models\Phones\PhoneLines;
use App\models\Phones\PhoneUnits;
use App\models\System\Currency;



class PhoneUnitsController extends Controller
{
    
    /**
     * Page to control Phone units Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('phones.units',$data);
    }
    
    
    /**
     * Display list of phone unit packages
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    { 
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');

        $phone_units = new PhoneUnits();
        
        if(strlen($search_query) > 0)
            $phone_units    = $phone_units->where('pu_unit_label', 'LIKE' , '%' . $search_query . '%');
            $phone_units    = $phone_units->orderBy('pu_id', 'ASC')->get();
             
        $data = array(
            "phone_units" => $phone_units,
        );
        
        $result_array = array(); 
        $result_array['display'] = view("phones.displayunits",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new unit package
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_currencies = Currency::all();
        
        $data = array(
            "lst_currencies" => $lst_currencies
        );
        return view('phones.addunits',$data);
    }
    
    
    /**
     * Save Line Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveUnitsInfo(Request $request)
    {
        $pu_id                      = $request->input('pu_id');
        $pu_unit_label              = $request->input('pu_unit_label');
        $pu_units                   = $request->input('pu_units');
        $pu_unit_amount             = $request->input('pu_unit_amount');
        $pu_currency_id             = $request->input('pu_currency_id');
        $result_array = array();
        
        $phoneunits  = new PhoneUnits();
        if($pu_id!= null)
        {
            $phoneunits= PhoneUnits::find($pu_id);
        }
        
        $phoneunits->pu_unit_label              = $pu_unit_label;
        $phoneunits->pu_units                   = $pu_units;
        $phoneunits->pu_unit_amount             = $pu_unit_amount;
        $phoneunits->pu_currency_id             = $pu_currency_id;
        
        
        
        $phoneunits->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Phone Units Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit  Form of Phone units Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pu_id
     */
    public function EditForm( $pu_id )
    {
        $phone_units        = PhoneUnits::find($pu_id);
        $lst_currencies = Currency::all();
        $data = array(
            "phone_units" => $phone_units,
            "lst_currencies" => $lst_currencies
        );
        return view('phones.editunits',$data);
    }
    
    
    /**
     * Delete phone Units information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeletePhoneUnitsInfo(Request $request)
    {
        
        $pu_id = $request->input('pu_id');
        
        $phone_units = PhoneUnits::find( $pu_id); 
        $phone_units->delete();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
}