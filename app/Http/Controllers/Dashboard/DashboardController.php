<?php
/***********************************************************
UsersController.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 5, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Sales\Orders;
use App\models\Inventory\Customers;
use App\models\Billing\Invoices;
use App\models\System\CurrencyExchangeRates;
use App\models\System\Currency;



class DashboardController extends Controller
{
    /**
     * Display Page of Dashboard for this System
     *
     * @author Moe Mantach
     * @access public
     */
    public function Dashboard()
    {
        
        // get list of orders related to this user
        $warehouse_id = session('warehouse_id');
        $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
        
        
        $count_orders = Orders::whereSoIsDeleted(0);
        
        if( $warehouse_id != null && $warehouse_id != 0)
        {
            $count_orders = $count_orders->whereFkWarehouseId($warehouse_id);
        }
        
        $count_orders    = $count_orders->whereBetween('so_order_date',[$monday,$friday])->count();
        
        
        $count_customers = Customers::whereIcIsDeleted(0)->whereBetween('ic_date_creation',[$monday,$friday])->count();
        
        $count_invoices = Invoices::whereBetween('bi_invoice_date',[$monday,$friday])->whereBiIsDeleted(0)->count();
        
        $todays_date = date("Y-m-d");
        $count_rates   = CurrencyExchangeRates::whereErDateExchange($todays_date)->count();
       
        //
        
        $data = array(
            "count_orders" => $count_orders,
            "count_customers" => $count_customers,
            "count_rates" => $count_rates,
            "count_invoices" => $count_invoices,
        );
        return Response()->view("dashboard.dashboard",$data);
    }
    
    
    
    public function CallcenterDashboard()
    {
         
        $data = array( );
        return Response()->view("dashboard.callcenters",$data);
    }
    
    
    /**
     * Crm Dashboard
     */
    public function CrmDashboard()
    {
        $data = array( );
        return Response()->view("dashboard.crm",$data);
    }
    
    
    
    public function Services()
    {
        // get list of orders related to this user
        $warehouse_id = session('warehouse_id');
        $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
 
        
        $count_customers = Customers::whereIcIsDeleted(0)->whereBetween('ic_date_creation',[$monday,$friday])->count();
        
        $count_invoices = Invoices::whereBetween('bi_invoice_date',[$monday,$friday])->whereBiIsDeleted(0)->count();
        
        $todays_date = date("Y-m-d"); 
 
        $data = array( 
            "count_customers" => $count_customers, 
            "count_invoices" => $count_invoices,
        );
        return Response()->view("dashboard.services",$data);
    }
    
    
    /**
     * Display list of account totals
     * @param Request $request
     * @return unknown
     */
    public function Displaylistaccounttotals(Request $request)
    {
        $start_date     = $request->input("start_date");
        $end_date       = $request->input("end_date");
        $search_query   = $request->input("search_query");
        $fisical_year   = $request->input("fisical_year");
        
        $query_cond = "";
        $query = "SELECT cc_id,tm_sub_ledger_account,cc_currency_code,accounts.aa_account_ref,accounts.aa_account_label,SUM(tm_debit) as total_debit,SUM(tm_credit) as total_credit, SUM(tm_debit) - SUM(tm_credit) AS total_balance  FROM acc_transaction_movements tm left join acc_accounting_accounts accounts on tm.tm_sub_ledger_account = accounts.aa_id left join currency curr on tm.tm_currency_id = curr.cc_id where  tm_debit != 1  ";
        
        if(strlen($search_query) > 0)
            $query .= " AND ( tm.tm_ledger_label LIKE '%" . $search_query . "%' OR accounts.aa_account_ref LIKE '%" . $search_query . "%' OR accounts.aa_account_label LIKE '%" . $search_query . "%' )";
            
            $query .= " AND YEAR(tm_transaction_date) ='" . $fisical_year . "'";
            
            $query = $query . " group by tm_sub_ledger_account,tm_currency_id  order by accounts.aa_account_ref,tm_currency_id DESC;";
            $lst_accounts = DB::select($query);
            
            
            $data = array(
                "lst_accounts" => $lst_accounts,
            );
            $result_array['is_error'] = 0;
            $result_array['display'] = view("dashboard.lstaccountstatmentgroup",$data)->render();
            
            return Response()->json($result_array);
    }
    
    
    /**
     * 
     * @param Request $request
     */
    public function GetServicesPieChart(Request $request)
    {
        
    }
    
    public function GetDailyServicesSales(Request $request)
    { 
        $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
        $result_array = array();
        $daily_orders_array = array();
         
        
        $lst_invoices = Invoices::whereBiIsDeleted(0);
        $lst_invoices   = $lst_invoices->whereBetween('bi_invoice_date',[$monday,$friday])->orWhere('bi_invoice_date',$monday)->orWhere('bi_invoice_date',$friday)->get();
        
        $order_array = array();
        
        foreach ( $lst_invoices as $key => $invoice_info ) {
            $bi_invoice_date    = date('N',strtotime($invoice_info->bi_invoice_date));
            $bi_total_cost      = $invoice_info->bi_total_cost;
            $bi_currency        = $invoice_info->Currency->cc_currency_code;
            
            if(isset($order_array[$bi_invoice_date]))
                $order_array[$bi_currency][$bi_invoice_date] = $order_array[$bi_invoice_date] + $bi_total_cost;
                else
                    $order_array[$bi_currency][$bi_invoice_date] =$bi_total_cost;
        }
        
        
        $invoice_daily_data = array();
       
        for( $i = 0; $i <= 6; $i++ )
        {
            $date = $monday . " + " . $i . " day";
            $str_date = strtotime($date);
            $current_date = date('N',$str_date);
          
            foreach ( $order_array as $key => $order_currency_data ) 
            {
               
                if( isset( $order_array[$key][$current_date]) )
                {
                    $invoice_daily_data[$key][ $i ] = $order_array[$key][$current_date];
                }
                else
                {
                    $invoice_daily_data[$key][ $i ] = 0;
                }
            } 
           
            
        }
         
        $result_array['is_error'] = 0;
        foreach ($invoice_daily_data as $key => $invoice_data ) 
        {
            $result_array['daily_orders_' . strtolower($key) . '_array'] = $invoice_data;
        }
        
        
        return Response()->json($result_array);
    }
    
    
    public function GetServicesInvoicePercentage(Request $request)
    {
        $lst_yearly_invoices = DB::select("SELECT YEAR(billing_invoices.bi_invoice_date) As year_invoices, cs_service_title, SUM(ii_total_price) , ii_price_currency FROM billing_invoice_items LEFT JOIN billing_invoices ON bi_id = fk_invoice_id LEFT JOIN crm_services on cs_id = ii_item_id WHERE ii_item_type = 2 GROUP BY ii_item_id,ii_price_currency;");
        
        $result_array = array();
        return Response()->json($result_array);
    }
    
    
    
    public function GetOutboundInvoices(Request $request)
    {
        $lst_outbound_invoices = DB::select("SELECT  MONTH( bi_invoice_date ) As creation_month , SUM(bi_total_price) as total_cost , bi_invoice_currency  FROM billing_invoices  where YEAR(bi_invoice_date) = YEAR(CURDATE())  group by MONTH( bi_invoice_date ) , bi_invoice_currency;");
        $lst_inbound_receipts = DB::select("SELECT  MONTH( br_receipt_date ) As creation_month , SUM(br_payment_value) as total_cost , br_receipt_currency  FROM billing_receipts  where YEAR(br_receipt_date) = YEAR(CURDATE())  group by MONTH( br_receipt_date ) , br_receipt_currency;");
 
        $currency_id        = session('company_currency');
        $currency_symbol    = session('currency_symbol');
        
        $order_stock_amount = array();
        $receipt_amount = array();
        $total_order_quantity   = 0;
        $total_order_amount     = array();
        
         
        
        foreach ($lst_outbound_invoices as $key => $invoice_info )
        {
            
            $total_price = $invoice_info->total_cost;
            $exchange_rate = 0; 
            if($total_price != 0)
                $order_stock_amount[$invoice_info->bi_invoice_currency][ $invoice_info->creation_month ] = $total_price;
        }
        
        
        foreach ($lst_inbound_receipts as $key => $receipt_info )
        {
            
            $total_price = $receipt_info->total_cost;
            $exchange_rate = 0;
            if($total_price != 0)
                $receipt_amount[$receipt_info->br_receipt_currency][ $receipt_info->creation_month ] = $total_price;
        }
        
        
        
        foreach ($order_stock_amount as $currency_id => $invoice_data ) {
            $currency_info = Currency::find($currency_id);
            $currency_code = $currency_info->cc_currency_code;
            for ($i = 1; $i <= 12; $i++) {
                if(!isset($order_stock_amount[$currency_id][$i]))
                    $order_stock_amount[$currency_id][$i] = 0;
                
                    if(!isset($total_order_amount[$currency_code]))
                        $total_order_amount[$currency_code] = 0;
            }
        }
        
        $amount_stock_array = array();
         
        foreach ($order_stock_amount as $currency_id => $total_invoice_data  ) {
            $currency_info = Currency::find($currency_id);
            $currency_code = $currency_info->cc_currency_code;
            foreach ($total_invoice_data as $index => $value) {
                $amount_stock_array[$currency_code][$index - 1] = $value;
                if(isset($total_order_amount[$currency_code]))
                    $total_order_amount[$currency_code] = $total_order_amount[$currency_code] + $value;
                else 
                    $total_order_amount[$currency_code] = $value;
            }
        } 
        

     
        $total_order_amount_display = array();
        foreach ($total_order_amount as $key => $total_amount) { 
            $total_order_amount_display[$key] = number_format($total_amount) . " " . $key;
            $amount_stock_array[$key] = json_encode($amount_stock_array[$key]);
        }  
        
        $result_array['is_error'] = 0;
        $result_array['total_order_amount'] = $total_order_amount_display;
        $result_array['order_stock_amount'] = $amount_stock_array;
        
        
        return Response()->json($result_array);
    }
    
    
    /**
     * get yearly supplier inbound stock
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetInboundSupplier(Request $request)
    {
        $lst_inbound_stocks = DB::select("SELECT MONTH(sq_due_date) As month_supplier_stock , SUM(sq_total_price) as total_price , sq_currency_id FROM icsolution_db.srm_supplier_quotations where YEAR(sq_due_date) = YEAR(CURDATE())  group by MONTH(sq_due_date) , sq_currency_id;");
        $currency_id        = session('company_currency');
        $currency_symbol    = session('currency_symbol');
        
        $supplier_stock_amount = array();
        $total_supplier_stock = 0;
        
       
        
        
        foreach ($lst_inbound_stocks as $key => $stock_info) 
        {
            
            $total_price = $stock_info->total_price;
            $exchange_rate = 0;
            if($currency_id != $stock_info->sq_currency_id )
            {
                $currency_exchange  = CurrencyExchangeRates::whereErFromCurrency($stock_info->sq_currency_id )->whereErToCurrency($currency_id)->orderby('er_date_exchange',"DESC")->get();
                $exchange_rate      = $currency_exchange[0]->er_exchange_rate;
                $total_price        = $total_price * $exchange_rate;
                
            }
            if($total_price != 0)
            $supplier_stock_amount[ $stock_info->month_supplier_stock ] = $total_price;
        }
        
        for ($i = 1; $i <= 12; $i++) {
            if(!isset($supplier_stock_amount[$i]))
                $supplier_stock_amount[$i] = 0;
        }
        $amount_stock_array = array();
         
         
        foreach ($supplier_stock_amount as $index => $value) {
            $amount_stock_array[$index - 1] = $value;
            $total_supplier_stock = $total_supplier_stock + $value;
        }
        $total_supplier_stock = number_format($total_supplier_stock) . " " . $currency_symbol;
        ksort($amount_stock_array); 
        $result_array['is_error'] = 0;
        $result_array['total_supplier_stock'] = $total_supplier_stock;
        $result_array['supplier_stock_amount'] = json_encode($amount_stock_array);
        
        
        return Response()->json($result_array);
    }
    
    
    /**
     * get yearly projection of orders by month
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function GetOutboundOrders(Request $request)
    {
        $lst_outbound_stocks = DB::select("SELECT  MONTH( so_creation_date ) As creation_month , SUM(so_total_cost) as total_cost , so_order_currency , SUM(so_product_quantity) as product_quantity FROM icsolution_db.sales_orders left join sales_order_products on fk_order_id = so_id  where YEAR(so_creation_date) = YEAR(CURDATE())  group by MONTH( so_creation_date ) , so_order_currency;");
        $currency_id        = session('company_currency');
        $currency_symbol    = session('currency_symbol');
        
        $order_stock_amount = array();
        $total_order_quantity   = 0;
        $total_order_amount     = 0;
        
        
        
        
        foreach ($lst_outbound_stocks as $key => $order_info)
        {
            
            $total_price = $order_info->total_cost;
            $exchange_rate = 0;
            if($currency_id != $order_info->so_order_currency)
            {
                $currency_exchange  = CurrencyExchangeRates::whereErFromCurrency($order_info->so_order_currency)->whereErToCurrency($currency_id)->orderby('er_date_exchange',"DESC")->get();
                $exchange_rate      = $currency_exchange[0]->er_exchange_rate;
                $total_price        = $total_price * $exchange_rate;
                
            }
            if($total_price != 0)
                $order_stock_amount[ $order_info->creation_month ] = $total_price;
        }
        
        for ($i = 1; $i <= 12; $i++) {
            if(!isset($order_stock_amount[$i]))
                $order_stock_amount[$i] = 0;
        }
        $amount_stock_array = array();
        
        
        foreach ($order_stock_amount as $index => $value) {
            $amount_stock_array[$index - 1] = $value;
            $total_order_amount = $total_order_amount + $value;
        }
         
        
        $total_order_amount = number_format($total_order_amount) . " " . $currency_symbol;
        ksort($amount_stock_array);
        $result_array['is_error'] = 0;
        $result_array['total_order_amount'] = $total_order_amount;
        $result_array['order_stock_amount'] = json_encode($amount_stock_array);
        
        
        return Response()->json($result_array);
    }
    
    /**
     * Dashboard Page for Accounting Module
     */
    public function Accounting()
    {
        
    }
    
    /**
     * get product stock by categories
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetStockByCategories( Request $request )
    {
        $lst_product_categories = DB::select("select cat.pc_category,SUM(inventory_products.p_product_quantity) as stock ,  sum(inventory_stocks.is_quanity) as stock_quantity from inventory_products left join inventory_stocks on inventory_stocks.fk_product_id = inventory_products.p_id left join inventory_product_categories as cat on cat.pc_id = inventory_products.fk_pc_id where 1 group by inventory_products.fk_pc_id;");
        
        $result_array = array();
        
        $stock_categories_array  = array();
     
        foreach ($lst_product_categories as $label => $info) {
            $stock_categories_array[] = array(
                'country' => $info->pc_category,
                'value' => $info->stock + $info->stock_quantity 
            );
        }
        
        $result_array['is_error'] = 0;
        $result_array['stock_categories_array'] = json_encode($stock_categories_array);
         
        
        return Response()->json($result_array);
        
    }
    
    
    public function GetStockProducts()
    {
        $lst_products = DB::select("SELECT fk_product_id , ip.p_product_name as product_name , SUM(is_quanity) as total_quantity FROM inventory_stocks as stocks Left join inventory_products as ip on ip.p_id = stocks.fk_product_id group by fk_product_id;");
        
        $result_array = array();
        
        $stock_products_array  = array();
     
        foreach ($lst_products as $label => $info) {
            $stock_products_array[] = array(
                'product' => $info->product_name,
                'quantity' => $info->total_quantity
            );
        }
        
        $result_array['is_error'] = 0;
        $result_array['stock_products_array'] = json_encode($stock_products_array);
         
        
        return Response()->json($result_array);
        
    }
    
    /**
     * Get array for daily Sales saved in the database to show it in the dashboard
     * 
     * @author Moe Mantach
     * @access public
     * 
     * 
     */
    public function GetDailySales()
    {
        
        $warehouse_id = session('warehouse_id');
        $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
        $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
        $result_array = array();
        $daily_orders_array = array();
        
        
        $lst_orders = Orders::whereSoIsDeleted(0);
        
        if( $warehouse_id != null && $warehouse_id != 0)
        {
            $lst_orders = $lst_orders->whereFkWarehouseId($warehouse_id);
        }
        
        $lst_orders    = $lst_orders->whereBetween('so_order_date',[$monday,$friday])->get();
        
  
        $order_array = array();
        
        foreach ( $lst_orders as $key => $order_info ) {
            $so_creation_date   = date('N',strtotime($order_info->so_creation_date));
            $so_total_cost      = $order_info->so_total_cost;
  
            if(isset($order_array[$so_creation_date]))
                $order_array[$so_creation_date] = $order_array[$so_creation_date] + $order_info->so_total_cost;
            else
                $order_array[$so_creation_date] = $order_info->so_total_cost;
        }
        
        
       
        for( $i = 0; $i <= 6; $i++ )
        {
            $date = $monday . " + " . $i . " day";
            $str_date = strtotime($date);
           
            $current_date = date('N',$str_date); 
            if( isset( $order_array[$current_date]) )
            {
                $daily_orders_array[ $i ] = $order_array[$current_date];
            }
            else 
            {
                $daily_orders_array[ $i ] = 0;
            }
            
        } 
        
         
        $result_array['is_error'] = 0;
        $result_array['daily_orders_array'] = $daily_orders_array;
        
        return Response()->json($result_array);
    }
}