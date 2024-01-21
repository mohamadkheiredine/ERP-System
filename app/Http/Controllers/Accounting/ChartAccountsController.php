<?php
/***********************************************************
ChartAccountsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
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
use App\models\Accounting\AccountCategories;



class ChartAccountsController extends Controller
{
    
    /**
     * Page to Manage Chart of Accounts for Our System
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_countries    = Countries::all();
        $data = array(
            "lst_countries" => $lst_countries
        );
        
        return Response()->view('accounting.chartaccounts',$data);
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
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $search_query = strval($search_query); 
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
         else
            $skip = 0;
          
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0);
        if(strlen($search_query) > 0)
        {   
            $lst_accounts = $lst_accounts->where(
                function($query) use ($search_query){ 
                    return $query
                    ->where("aa_account","LIKE",'%' . strval($search_query) . '%')
                    ->orWhere("aa_account_label","LIKE",'%' . $search_query. '%')
                    ->orWhere("aa_account_label","LIKE",'%' . $search_query. '%');
               }); 
        }
        
        $accounts_count = $lst_accounts->count();
        
        $total_pages = ceil( $accounts_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        
        $lst_accounts = $lst_accounts->skip($skip)->take($nbr_rows_per_pages)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
         
        $chart_accounts_array = CreateDatabaseArrayByIndex($lst_accounts, "aa_id");
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "chart_accounts_array" => $chart_accounts_array,
        );
        
        $result_array = array();
        
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("accounting.listaccounts",$data)->render();
        
        return Response()->json($result_array);
    }
   
    /**
     * Function of Adding a new Department
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->orderby("aa_account","ASC")->get();
        $lst_account_categories = AccountCategories::all();
        $lst_countries = Countries::all();
         
        
        
        
        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_account_categories" => $lst_account_categories,
            "lst_countries" => $lst_countries
        );
        return view('accounting.addaccount',$data);
    }
    
    
    /**
     * Save Chart Account Record Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveAccountInfo(Request $request)
    {
        $aa_id                      = $request->input('aa_id');
        $aa_parent_account          = $request->input('aa_parent_account');
        $aa_account_ref             = $request->input('aa_account_ref');
        $aa_account                 = $request->input('aa_account');
        $aa_sub_account             = $request->input('aa_sub_account');
        $aa_account_label           = $request->input('aa_account_label'); 
        $aa_group_account           = $request->input('aa_group_account'); 
        $aa_subgroup_account        = $request->input('aa_subgroup_account'); 
        $fk_country_id              = $request->input('fk_country_id'); 
        $aa_category_id             = $request->input('aa_category_id'); 
        $aa_account_information     = $request->input('aa_account_information'); 
        
        $result_array = array();
 
        
        $ChartAccounts = new ChartAccounts();
        if($aa_id != null)
        {
            $ChartAccounts= ChartAccounts::find($aa_id);
        }
         
        $ChartAccounts->aa_parent_account         = $aa_parent_account;
        $ChartAccounts->aa_account_ref            = $aa_account_ref;
        $ChartAccounts->aa_account                = $aa_account;
        $ChartAccounts->aa_sub_account            = $aa_sub_account; 
        $ChartAccounts->aa_account_label          = $aa_account_label; 
        $ChartAccounts->aa_group_account          = $aa_group_account; 
        $ChartAccounts->aa_subgroup_account       = $aa_subgroup_account; 
        $ChartAccounts->fk_country_id             = $fk_country_id; 
        $ChartAccounts->aa_category_id            = $aa_category_id; 
        $ChartAccounts->aa_account_information    = $aa_account_information; 
        
        $ChartAccounts->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Department Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    /**
     * Import csv file of the chart account and save it into the datbaase
     * 
     *  @author Moe Mantach
     *  @access public
     *  @param Request $request
     */
    public function ImportChartAccount( Request $request )
    {
        $fk_country_id = $request->input("fk_country_id");
        $csv_file= $request->file("csv_file");
        $path = $request->file('csv_file')->getRealPath();
        $csv_array = csvToArray($path);
        $result_array  = array();
      
        foreach ( $csv_array as $key => $csv_record ) 
        {
             $account       = $csv_record['Account'];
             $sub_account   = $csv_record['Sub Account'];
             $label         = $csv_record['label'];
             
             // check if this account exist in the database if exist we escape the current row
             $count_account   =  ChartAccounts::whereAaAccount($account)->count();
             if($count_account > 0)
                 continue;
             
             $sub_account_id = 0;  
             if($sub_account != '')
             {
                 $sub_account_obj   =  ChartAccounts::whereAaAccount($sub_account)->get();
                 $sub_account_id    = $sub_account_obj[0]['aa_id'];
 
             }
             
             $AccountData = new ChartAccounts();
             $AccountData->aa_account_ref   = $account;
             $AccountData->aa_account       = $account;
             $AccountData->aa_sub_account   = $sub_account_id;
             $AccountData->aa_account_label = $label;
             $AccountData->fk_country_id    = $fk_country_id;
             $AccountData->save();
             
        }
        
        $result_array['is_error'] = 0;
        
        return Response()->json($result_array);
    }
    
    /**
     * 5311000008
     * @param Request $request
     * @return unknown
     */
    public function Generatelatestaccount(Request $request)
    { 
        $account_id = $request->input('account_id');
        $id = $request->input('id');
        
      
        if( $request->has('account_id'))
        {
            
            // check if this account exist
            $account_info   = ChartAccounts::where("aa_account_ref","LIKE",$account_id. "%")->whereRaw('LENGTH(aa_account_ref) >= 10')->orderBy('aa_account_ref','ASC')->get();
        }
        else 
        {
            $account_info = ChartAccounts::find($id); 
            $account_id = $account_info->aa_account_ref;
            $account_info   = ChartAccounts::where("aa_account_ref","LIKE",$account_id. "%")->whereRaw('LENGTH(aa_account_ref) >= 10')->orderBy('aa_account_ref','ASC')->get();
        }
        
      
       
        $account_count = strlen($account_id); 
        if(count($account_info) == 0)
            $aa_account_ref = $account_id . $account_count;
        else
            $aa_account_ref = $account_info[count($account_info) - 1]->aa_account_ref;
        
        $aa_account_ref = intval($aa_account_ref); 
        $aa_account_ref++;
       
        
        $result_array['is_error'] = 0;
        $result_array['account_ref'] = $aa_account_ref;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display Edit Chart Accounting Account Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $aa_id
     */
    public function EditForm( $aa_id )
    {
        $chartaccount_info      = ChartAccounts::find($aa_id);
        $lst_countries          = Countries::all();
        $lst_account_categories = AccountCategories::all();
        $lst_accounts           = ChartAccounts::whereAaIsDeleted(0)->where("aa_id","!=",$aa_id)->orderby("aa_account","ASC")->get();
        
        $data = array(
            "chartaccount_info" => $chartaccount_info,
            "lst_countries" => $lst_countries,
            "lst_account_categories" => $lst_account_categories,
            "lst_accounts" => $lst_accounts,
        );
        return view('accounting.editaccount',$data);
    }
    
    
    /**
     * Delete Account information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAccountInfo(Request $request)
    {
        
        $aa_id= $request->input('aa_id');
         
        $AccountingAccount = ChartAccounts::find( $aa_id);
        $AccountingAccount->aa_is_deleted          = 1;
        $AccountingAccount->aa_deleted_by          = Session('user_id');
        $AccountingAccount->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Account Accounting and link it to any page
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAccAccounting(Request $request )
    {
        $parent_account     = $request->input("parent_account");
        $account_label      = $request->input("account_label");
        $country_id         = session("company_country");
        $result_array       = array();
        
        $acc_info = ChartAccounts::find($parent_account);
        
        
        $count_ref_account = ChartAccounts::whereAaAccountRef($parent_account)->count();
        
        
        // check if this account exist
        $account_info = ChartAccounts::whereAaParentAccount($parent_account)->get();
        
        $new_count = count($account_info) + 1;
        
        $aa_account_ref = $acc_info->aa_account_ref. (String)$new_count;
        $AccAccounting = new ChartAccounts();
        $AccAccounting->aa_parent_account   = $parent_account;
        $AccAccounting->aa_account_ref      = $aa_account_ref;
        $AccAccounting->aa_account          = $aa_account_ref;
        $AccAccounting->aa_sub_account      = $parent_account;
        $AccAccounting->aa_account_label    = $account_label;
        $AccAccounting->fk_country_id       = $country_id;
        $AccAccounting->save();
        
        $aa_id = $AccAccounting->aa_id;
        
        
        $result_array['is_error']           = 0;
        $result_array['error_msg']          = "Operation Complete Successfully";
        $result_array['accounting_label']   = $account_label;
        $result_array['aa_id']              = $aa_id;
        $result_array['account_ref']        = $aa_account_ref;
        return Response()->json($result_array);
    }

}