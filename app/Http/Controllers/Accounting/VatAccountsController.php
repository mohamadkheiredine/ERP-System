<?php
/***********************************************************
VatAccountsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 25, 2019
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
use App\Library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\BankAccounts;
use App\models\System\Currency;
use App\models\Accounting\VatAccounts;



class VatAccountsController extends Controller
{

    /**
     * Main Page of VAT & Tax Accounts
     * 
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function index()
    { 
        $data = array();
        return Response()->view('accounting.vataccounts',$data);
    }
    
    
    /**
     * Display list of Journals saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {            
       
 
        $lst_vat_accounts   = VatAccounts::whereAvIsDeleted(0)->orderBy('av_vat_code', 'asc')->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts , "aa_id");
        
        $data = array(
            "lst_vat_accounts" => $lst_vat_accounts,
            "accounts_array" => $accounts_array,
        );
        
        $result_array = array();
         
        $result_array['display'] = view("accounting.listtaxes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Open form of add new Bank Account
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_countries      = Countries::all();
        
        
        $data = array(
            'lst_accounts' => $lst_accounts,
            'lst_countries' => $lst_countries
        );
        
        return Response()->view('accounting.addvattaxform',$data);
    }
    
    /**
     * Edit VAT Info 
     * @param unknown $av_id
     * @return unknown
     */
    public function EditForm( $av_id )
    {
        $VatAccount         = VatAccounts::find( $av_id );
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        $lst_countries      = Countries::all();
        
        $data = array(
            'VatAccount' => $VatAccount,
            'lst_accounts' => $lst_accounts, 
            "lst_countries" => $lst_countries
        );
        
        return Response()->view('accounting.editvattaxform',$data);
        
    }
    
    
    /**
     * function to save data of VAT Accounts to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveVatAccountsInfo(Request $request)
    {
        $av_id                      = $request->input('av_id');
        $av_vat_code                = $request->input('av_vat_code');
        $av_vat_label               = $request->input('av_vat_label');
        $av_vat_rate                = $request->input('av_vat_rate');
        $av_sale_account_code       = $request->input('av_sale_account_code');
        $av_purchase_account_code   = $request->input('av_purchase_account_code');
        
        $VatAccounts = new VatAccounts();
        
        if($av_id > 0)
        {
            $VatAccounts   = VatAccounts::find( $av_id );
        }
        
        $VatAccounts->av_vat_code   = $av_vat_code;
        $VatAccounts->av_vat_label  = $av_vat_label;
        $VatAccounts->av_vat_rate   = $av_vat_rate;
        $VatAccounts->av_sale_account_code      = $av_sale_account_code;
        $VatAccounts->av_purchase_account_code  = $av_purchase_account_code;
        $VatAccounts->save();
        
 
        
        $result_array = array();
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
        
    }
    
    
    /**
     * Delete VAT Account info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteVatAccountInfo(Request $request)
    {
        $av_id  = $request->input('av_id');
        $result_array = array();
        
        
        
        $VatAccounts= VatAccounts::find( $av_id );
        $VatAccounts->av_is_deleted = 1;
        $VatAccounts->av_deleted_by = session('user_id');
        $VatAccounts->save();
        
        
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
 
}