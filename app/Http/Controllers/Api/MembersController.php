<?php
/***********************************************************
MembersController.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Dec 5, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
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



class MembersController  extends Controller
{
    
    /**
     * 
     * @param Request $request
     */
    public function GetListMembers(Request $request)
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
        
        
        
        $result_array['is_error'] = 0;
        $result_array['list_units'] = $units_array;
        
        return Response()->json($result_array);
    }
    
}