<?php
/***********************************************************
DashboardController.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Dec 29, 2023
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
use Milon\Barcode\DNS1D;
use Models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\Library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use Swap\Swap;
use App\models\System\Units;
use App\models\Users\Users;
use App\models\Accounting\DefaultAccounts;
use App\models\Billing\PaymentTypes;

class DashboardController extends Controller
{
    
    /**
     * get daily sales
     * 
     * @author Moe Mantach
     * @access public
     */
    public function GetTodaysTotalOrders(Request $request)
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
        
        
        // Your custom SQL query
        $sql = "select SUM(so_total_cost) as total_cost , so_order_currency from sales_orders where so_order_date = CURDATE()  group by so_order_currency;";
        
        // Bind values if needed
        $values = [];
        
        // Execute the query
       $total_todays_orders = DB::select($sql, $values);
     
       
       $total_orders_array = array();
       
       
       foreach ($total_todays_orders as $key => $total_info) {
           
           $currency_info = Currency::find($total_info->so_order_currency);
           
           $total_orders_array[] = array(
               'total_order' => $total_info->total_cost,
               'currency_code' => $currency_info->cc_currency_code,
               'currency_id' => $currency_info->cc_id
           );
       }
       
       
       $result_array['total_orders_array'] = $total_orders_array;

        
        
        return Response()->json($result_array);
    }
     
    /**
     * get list of orders throw year
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetListOfOrdersByDate(Request $request)
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
        
        
        $sql = "select SUM(so_total_cost) as total_cost , so_order_date , so_order_currency from sales_orders where YEAR(so_order_date) = YEAR(CURRENT_DATE()) group by so_order_date , so_order_currency;";
        
        // Bind values if needed
        $values = [];
        
        // Execute the query
        $lst_orders_data = DB::select($sql, $values);
        
        
        $lst_orders = array();
        
        foreach ($lst_orders_data as $key => $order_info) {
            
            $currency_info = Currency::find($order_info->so_order_currency);
            
            $lst_orders[] = array(
                'total_cost' => $order_info->total_cost,
                'so_order_date' => $order_info->so_order_date,
                'currency_id' => $currency_info->cc_id,
                'so_order_currency' => $currency_info->cc_currency_code
            );
        }
        
        $result_array['lst_orders'] = $lst_orders;
        
        return Response()->json($result_array);
    }
}