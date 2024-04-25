<?php
/***********************************************************
DefaultAccountsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Accounting;

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
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\DefaultAccounts;



class DefaultAccountsController extends Controller
{

    /**
     * Page to Manage Default accounts values
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
      
        $data = array();
        
        return Response()->view('accounting.defaultaccounts',$data);
    }
    
    
    /**
     * Display list of Accounts saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {            
        
        $lst_accounts           = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_default_accounts   = DefaultAccounts::all();
         
        $data = array(
            "lst_accounts" => $lst_accounts, 
            "lst_default_accounts" => $lst_default_accounts
        );
        
        $result_array = array();
         
        $result_array['display'] = view("accounting.listdefaultaccounts",$data)->render();
        
        return Response()->json($result_array);
    }
   
    
    /**
     * Save link of default account to account saved in the chart of accounts
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveDefaultAccounts(Request $request)
    {
        $da_id              = $request->input("da_id");
        $da_account_value   = $request->input("da_account_value");
        
        for ($i = 0; $i < count($da_id); $i++) {
            $DefaultAccount = DefaultAccounts::find($da_id[$i]);
            $DefaultAccount->da_account_value = $da_account_value[$i];
            $DefaultAccount->save();
        }
        
        $result_array = array();
        
        $result_array['is_error'] = "0";
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    

}