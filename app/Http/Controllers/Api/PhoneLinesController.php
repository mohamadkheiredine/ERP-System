<?php
/***********************************************************
PhoneLinesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\Http\Controllers\Api;

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
use App\models\Users\Users;
use App\models\Phones\PhoneUnits;



class PhoneLinesController extends Controller
{
    
    /**
     * get list of numbers saved on category
     * @param Request $request
     * @return unknown
     */
    public function GetListofNumbers(Request $request)
    {
        
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');  
        $category_id         = $request->input('category_id');  
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
        $lst_lines = PhoneLines::wherePlPhoneType($category_id)->get();
        
        $lines_array = array();
        foreach ( $lst_lines as $key => $line_info ) 
        {
            $lines_array[ $line_info->pl_id ]['id'] = $line_info->pl_id;
            $lines_array[ $line_info->pl_id ]['line_title'] = $line_info->pl_line_title;
            $lines_array[ $line_info->pl_id ]['line_number'] = $line_info->pl_line_number;
        }
        
        $result_array['is_error'] = 0;
        $result_array['list_numbers'] = $lines_array;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * 
     * @param Request $request
     */
    public function Getunitpackages(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash'); 
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        
        
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
        $lst_units = PhoneUnits::all();
        
        $units_array = array();
        foreach ( $lst_units as $key => $unit_info )
        {
            $units_array[ $unit_info->pu_id ]['id']                 = $unit_info->pu_id;
            $units_array[ $unit_info->pu_id]['package_label']       = $unit_info->pu_unit_label;
            $units_array[ $unit_info->pu_id]['package_amount']      = $unit_info->pu_unit_amount;
            $units_array[ $unit_info->pu_id]['package_currency']    = $unit_info->pu_currency_id;
        }
        
        $result_array['is_error'] = 0;
        $result_array['list_units'] = $units_array;
        
        return Response()->json($result_array);
    }
    
}