<?php
/***********************************************************
PaymentTypesController.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 16, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\Http\Controllers\Billing;

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
use App\models\Accounting\AccountCategories;
use App\models\Billing\PaymentTypes;



class PaymentTypesController extends Controller
{
    
    /**
     * Page to manage information of the payment types
     * 
     * @author Moe Mantach
     * @access public
     */
    public function index(){
        $lst_payment_types = PaymentTypes::wherePtIsDeleted(0)->get();
        $lst_chart_accounts = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
        
        $data = array(
            "lst_payment_types" => $lst_payment_types,
            "lst_chart_accounts" => $lst_chart_accounts,
        );
        return Response()->view('billing.paymenttypes',$data);
    }
    
    
    /**
     * Save Payment types info in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SavePaymentTypes(Request $request)
    {
        $pt_id              = $request->input('pt_id');
        $pt_payment_type    = $request->input('pt_payment_type');
        $pt_payment_account = $request->input('pt_payment_account');
        
        
        foreach ( $pt_id as $index => $id ) 
        {
            $payment_types = PaymentTypes::find($id);
            $payment_types->pt_payment_type     = $pt_payment_type[$index];
            $payment_types->pt_payment_account  = $pt_payment_account[$index];
            $payment_types->save();
            
            $payment_types = null;
            unset($payment_types);
        }
        
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
}
 