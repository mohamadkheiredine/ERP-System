<?php
/***********************************************************
AccountingJournalsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 17, 2019
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



class AccountingJournalsController extends Controller
{

    /**
     * Page to Manage Chart of Accounting Journals for Our System
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    { 
        $data = array();
        return Response()->view('accounting.accountingjournals',$data);
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
       
 
        $lst_journals = AccountingJournals::whereAjIsDeleted(0)->orderBy('aj_journal_code', 'asc')->orderBy('aj_journal_label', 'asc')->get();
        
        $lst_journal_types = Journaltypes::whereTyIsDeleted(0)->get();
        $journal_types_array = CreateDatabaseArrayByIndex($lst_journal_types, "ty_id");
        
        $data = array(
            "lst_journals" => $lst_journals,
            "journal_types_array" => $journal_types_array,
        );
        
        $result_array = array();
         
        $result_array['display'] = view("accounting.listjournals",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    public function Changejournalstatus(Request $request)
    {
        $is_active  =  $request->input("is_active"); 
        $aj_id      =  $request->input("aj_id");
        $Journal = AccountingJournals::find($aj_id);
        $Journal->aj_is_active = $is_active;
        $Journal->save();
        
        $result_array = array();
         
        return Response()->json($result_array);
    }
 
}