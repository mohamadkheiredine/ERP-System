<?php
/***********************************************************
PaymentsController.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 8, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
API Requests for Customers
***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CustomersManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Inventory\Vendors;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\DefaultAccounts;
use App\models\Accounting\TransactionMovements;
use App\library\AccountingManager;
use App\models\System\Currency;
use App\models\Billing\PaymentTypes;
use App\models\Accounting\Transactions;
use App\models\Billing\Invoices;
use App\models\Billing\Receipts;
use App\models\Billing\PaymentVouchers;
use App\models\Billing\InternalTransfers;
use App\library\OrdersManager;
use App\models\System\Companies;
use App\models\Sales\Orders;
use App\models\Sales\OrderProducts;

class PaymentsController extends Controller
{
    /**
     * Add Payment Debit 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DebitCustomerOrder(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $currency_id         = $request->input('currency_id');
	$customer_id         = $request->input('customer_id');
        $user_info           = Users::find($user_id);
        
        $warehouse_id       = $request->input('warehouse_id');
        $company_currency   = $request->input('company_currency');
        $pos_sub_total      = $request->input('pos_sub_total');
        $pos_discount       = $request->input('pos_discount');
        $pos_total          = $request->input('pos_total');
        $order_items        = $request->input('order_items');
        $order_items        = json_decode( $order_items , true );
        
        $delcustomername          = $request->input('delcustomername');
        $delcustomerphone          = $request->input('delcustomerphone');
        $delcustomeraddress          = $request->input('delcustomeraddress');
        $delivery_id          = $request->input('delivery_id');
        
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        
        $date = date("Y-m-d");
		$creation_time = date("H:i:s");
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
         
        if($delivery_id != 0)
        {
            // check if the customer exist 
            $customer_check = Customers::whereIcCustomerMobile($delcustomerphone)->get();
            
            
            if(count($customer_check) == 0)
            {
                 $customer_manager = new CustomersManager();
                    $params = array(
                       'company_id' => $user_info->fk_company_id
                   );
                   $ic_customer_code = $customer_manager->GenerateCustomerCode($params);


                   $customer_info = new Customers();
                   $customer_info->ic_customer_name = $delcustomername;
                   $customer_info->ic_customer_address = $delcustomeraddress;
                   $customer_info->ic_customer_phone = $delcustomerphone;
                   $customer_info->ic_customer_mobile = $delcustomerphone;
                   $customer_info->ic_customer_code = $ic_customer_code;

                    $account_info   = ChartAccounts::where("aa_account_ref","=","41")->get();
                   $account_info = $account_info[0];

                   $count   = ChartAccounts::where("aa_account_ref","LIKE","41%")->count();

                   $new_count      = $count + 1;
                   $aa_account_ref = $account_info->aa_account . (String)$new_count;

                   $AccAccounting = new ChartAccounts();
                   $AccAccounting->aa_parent_account   = $account_info->aa_id;
                   $AccAccounting->aa_account_ref      = $aa_account_ref;
                   $AccAccounting->aa_account          = $aa_account_ref;
                   $AccAccounting->aa_sub_account      = $account_info->aa_id;
                   $AccAccounting->aa_account_label    = $delcustomername;
                   $AccAccounting->fk_country_id       = 0;
                   $AccAccounting->save();

                   $aa_id = $AccAccounting->aa_id;
                   $customer_info->ic_account_number = $aa_id;


                   $customer_info->save();
                   $customer_id = $customer_info->ic_id;
            }
            else
            {
                $customer_id = $customer_check[0]->ic_id;
            }
        }
        
         $order_manager = new OrdersManager();
        
        $company_id = $user_info->fk_company_id;
        
        $company_info   = Companies::find($company_id); 
        
        $params_array = array(
            'company_id' => $user_info->fk_company_id
        );
        $so_order_code      = $order_manager->GeneratePOSOrderCode( $params_array );
        $so_order_label     = "";
        
        $so_order_barcode = rand(100000000,999999999);
        
        $creation_date    = date("Y-m-d");
        $so_vat_id = 0;
        
        $order_info = new Orders();
       $order_info->so_order_code       = $so_order_code;
       $order_info->so_order_barcode    = $so_order_barcode;
       $order_info->fk_user_id          = $user_id;
       $order_info->so_assign_to        = $delivery_id;
       $order_info->fk_warehouse_id     = $warehouse_id;
       $order_info->so_order_status     = 1;
       $order_info->so_order_customer   = $customer_id;
       $order_info->so_vendor_id        = 0;
       $order_info->so_creation_date    = $creation_date;
       $order_info->so_product_type     = 1;
       $order_info->so_payment_type     = 1;
       $order_info->so_order_label      = $so_order_label;
       $order_info->so_order_note       = "";
       $order_info->so_order_date       = $creation_date;
       $order_info->so_delivery_date    = $creation_date;
       $order_info->so_vat_id           = $so_vat_id;
       $order_info->so_pos_order        = 1;
        
        $order_info->so_sub_total        = $pos_sub_total;
        $order_info->so_total_discount   = $pos_discount;
        $order_info->so_total_cost       = $pos_total;
        $order_info->so_order_currency   = $company_currency; 
        $order_info->save();
        
        $so_id = $order_info->so_id;
        
        foreach ( $order_items as $key => $item_order ) 
        { 
            $discount = isset($item_order['product_discount']) ? $item_order['product_discount'] : 0;
            $orderitem = new OrderProducts();
            $orderitem->fk_order_id          = $so_id;
            $orderitem->fk_product_id        = $item_order['p_id'];
            $orderitem->so_stock_id          = $item_order['is_id'];
            $orderitem->so_product_cost      = $item_order['product_cost'];
            $orderitem->so_discount          = $discount;
            $orderitem->so_product_price     = $item_order['product_cost'] - ( $item_order['product_cost'] * $discount /100);
            $orderitem->so_product_quantity  = $item_order['product_quantity'];
            $orderitem->so_product_currency  = $company_currency;
            $orderitem->save();
        }
        
        
        
        
        $customer_info  = Customers::find($customer_id);
        $payment_info   = PaymentTypes::find(2);
        $label = "Payment For Order " . $customer_info->ic_customer_name . " On " . $date;
        
        $AccTransaction = new Transactions();
        $AccTransaction->at_transaction_date    = $date;
        $AccTransaction->at_creation_date       = date("Y-m-d");
        $AccTransaction->at_accounting_doc      = $label;
        $AccTransaction->fk_acc_journal_id      = 3;
        $AccTransaction->save();
        $at_id = $AccTransaction->at_id;



        $pt_payment_account = $payment_info->pt_payment_account;



        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
        $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
        $TransactionMovement->tm_ledger_label       = $label;
        $TransactionMovement->tm_debit              = $pos_total;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = $date;
        $TransactionMovement->tm_transaction_date   = $date;
        $TransactionMovement->tm_currency_id        = $currency_id;
        $TransactionMovement->save();

        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();

        $discount_total =  ( $pos_discount / 100 ) * $pos_total;
        $total = ( $pos_total - $discount_total );
            
            
         $data = array(
            "company_info"      => $company_info,
            "lst_order_items"   => $lst_order_items,
            "order_info"        => $order_info,
            "user_info"         => $user_info,
            "cost_total"        => $total,
            "pos_sub_total"        => $pos_sub_total,
            "pos_discount"         => $pos_discount,
            "creation_date"     => $creation_date,
            "so_order_code" => $so_order_code,
			"creation_time" => $creation_time
        );
        
        if( $delivery_id != 0 )
        {
            $customer_info = Customers::find($customer_id);
            $data['delivery_id'] = $delivery_id;
            $data['customer_info'] = $customer_info;
        }
        
        $pos_receipt = view('templates.posinvoices',$data)->render();
            
        $result_array['pos_receipt'] = $pos_receipt;
        
        
         return Response()->json($result_array);
    }
    
    public function Getaccountstatment(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
         $customer_id         = $request->input("customer_id");
        $currency_id        = $request->input("currency_id");
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();

        
        $customer_info = Customers::find($customer_id);
        
        $account_id = $customer_info->ic_account_number;
        
        $date = date("Y-m-d");
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
       
        
        $currency_info = Currency::find($currency_id); 
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");
         
        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;
        
        $firstday = date("Y-m-d",strtotime($strfirstday));
        $lastday = date("Y-m-d",strtotime($strlastday)); 
        
        
        $lst_movements = TransactionMovements::where('tm_sub_ledger_account',$account_id);
        
        
        $lst_movements = $lst_movements->whereBetween('tm_transaction_date', [$firstday, $lastday]);
        
        $lst_movements = $lst_movements->where('tm_currency_id','=',$currency_id);
        
        $lst_movements = $lst_movements->orderby('tm_sub_ledger_account',"ASC")->orderby('tm_transaction_date',"ASC")->get(); 
      
        $movement_data_array = array();
        
        $total_balance = 0; // total balance for including before
        // if including before checked we calculate the cumilative from start date to current date
        $inc_before_total = array();
        
        
        
        
        $cumulative_credit = array();
        $cumulative_debit =  array();
        $index = 0; 
        foreach ( $lst_movements as $key => $movement_info ) {
            $tm_id      = $movement_info->tm_id;
            $trans_id   = $movement_info->fk_tran_id;
            
            $invoice_info = Invoices::whereBiTransactionId($trans_id);

            $invoice_info = $invoice_info->whereBiIsDeleted(0)->get();
            
            $receipt_info = Receipts::whereBrTransId($trans_id);

            $receipt_info = $receipt_info->get();
            
            $currency_code =  $movement_info->currency->cc_currency_code;
            $transaction_info = Transactions::find($trans_id);
            $voucher_info = PaymentVouchers::wherePvTransactionId($trans_id);

            $voucher_info = $voucher_info->get();
            
            
            $internal_info = InternalTransfers::whereFkTransId($trans_id);

            $internal_info = $internal_info->get();
            
            
            if(count($invoice_info) > 0 )
            {
                foreach ($invoice_info as $key => $inv)
                {
                    $transaction_description    = strip_tags($inv->bi_invoice_note);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $inv->bi_invoice_date;
                    $transaction_code           = $inv->bi_invoice_code;
                }
                
            }
            elseif(count($receipt_info) > 0)
            {
                
                $receipts_info = Receipts::whereBrTransId($trans_id)->get();
                
                if( count($receipts_info) > 0 )
                {
                    foreach ($receipts_info as $key => $receipt)
                    {
                        $transaction_description = strip_tags($receipt->br_receipt_note);
                        $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                        $transaction_date           = $receipt->br_receipt_date;
                        $transaction_code           = $receipt->br_receipt_number;
                    }
                }
            }
            elseif(count($voucher_info) > 0 ) {
                foreach ($voucher_info as $key => $voucher)
                {
                    $transaction_description = strip_tags($voucher->pv_voucher_description);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $voucher->pv_creation_date;
                    $transaction_code           = $voucher->pv_code;
                }
            }
            elseif(count($internal_info) > 0 ) {
                foreach ($internal_info as $key => $internal)
                {
                    $transaction_description = strip_tags($internal->in_transfer_notes);
                    $transaction_description = str_replace("&nbsp;", " ", $transaction_description);
                    $transaction_date           = $internal->in_transfer_date;
                    $transaction_code           = $internal->in_transfer_code;
                }
            }
            else
            {
                //elseif( $transaction_info->fk_acc_journal_id == 8 )
                $transaction_description = $movement_info->tm_ledger_label;
                $transaction_date           = $movement_info->tm_creation_date;
                $transaction_code           = $transaction_description;
            }
            
            
            if(!array_key_exists($currency_code , $cumulative_credit) )
            {
                $cumulative_credit[ $currency_code ] =  $total_balance + $movement_info->tm_credit;
            }
            else
            {
                $cumulative_credit[ $currency_code ] =  $cumulative_credit[ $currency_code ]  + $movement_info->tm_credit;
            }
            
            
            if(!array_key_exists($currency_code , $cumulative_debit) )
            {
                $cumulative_debit[ $currency_code ] =  $total_balance + $movement_info->tm_debit;
            }
            else
            {
                $cumulative_debit[ $currency_code ] =  $cumulative_debit[ $currency_code ]  + $movement_info->tm_debit;
            }
            
            
            $currency_id    = $movement_info->currency->cc_id;
            $account_id     = $movement_info->tm_sub_ledger_account;
            
            if(isset($movement_data_array[ $currency_id ][ $account_id ]))
            {
                $index = count($movement_data_array[ $currency_id ][ $account_id ]);
                $movement_data_array[ $currency_id ][ $account_id ][ $index ] = array();
            }
            else
            {
                $index = 0;
                $movement_data_array[ $currency_id ][ $account_id ][ $index ] = array();
            }
            
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['debit']                        = $movement_info->tm_debit;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['credit']                       = $movement_info->tm_credit;
            
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['balance'] = $movement_info->tm_debit - $movement_info->tm_credit;
            
            if($index == 0)
            {
                $movement_data_array[ $currency_id ][ $account_id ][ $index ]['balance'] = $total_balance + $movement_info->tm_debit - $movement_info->tm_credit;
            }
            else {
               
                
                if(isset( $movement_data_array[ $currency_id ][ $account_id ][ $index - 1 ] ))
                    $prev_balance =  $movement_data_array[ $currency_id ][ $account_id ][ $index - 1 ]['balance'];
                    else
                        $prev_balance = 0;
                        
                        $movement_data_array[ $currency_id ][ $account_id ][ $index ]['balance'] = $prev_balance + $movement_info->tm_debit - $movement_info->tm_credit;
                        
             }
            
            
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['tm_id']                        = $tm_id;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['trans_id']                     = $trans_id;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['currency']                     = $movement_info->currency->cc_currency_code;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['date_creation']                = $movement_info->tm_transaction_date;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['account_payable']              = $movement_info->tm_ledger_account;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['account_receivable']           = $movement_info->tm_sub_ledger_account;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['mov_desc']                     = $transaction_description;
            $movement_data_array[ $currency_id ][ $account_id ][ $index ]['code']                         = $transaction_code; 
        } 
                
        
        
        
            $AccountingManager = new AccountingManager(); 
            
            $account_balance    =$movement_data_array;
            $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
            $accounts_array     = CreateDatabaseArrayByIndex($lst_accounts, "aa_id"); 
            $data = array(
                "account_balance" => $account_balance,
                "account_id" => $account_id,
                "currency_info" => $currency_info,
                "show_back" =>1,
                "accounts_array" => $accounts_array
            ); 
            
            $result_array['is_error'] = 0;
            $result_array['display'] = view("accounting.lstposaccountstatment",$data)->render();
            
            return Response()->json($result_array);
        
        
    }
    
    
    /**
     * Credit Payment Customer 
     * @param Request $request
     * @return type
     */
    public function CreditPaymentCustomer(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $customer_id         = $request->input('customer_id');
        $pos_total           = $request->input('pos_total');
        $currency_id         = $request->input('currency_id');
        $user_info           = Users::find($user_id);
        
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();
        
        $date = date("Y-m-d");
        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
            
            return Response()->json($result_array);
        }
        
                 $customer_info  = Customers::find($customer_id);
            $payment_info   = PaymentTypes::find(2);
        $label = "Payment For Order " . $customer_info->ic_customer_name . " On " . $date;
        
         $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $label;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;
            
   
            
            $pt_payment_account = $payment_info->pt_payment_account;
            
            
            
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
            $TransactionMovement->tm_ledger_label       = $label;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $pos_total;
            $TransactionMovement->tm_creation_date      = $date;
            $TransactionMovement->tm_transaction_date   = $date;
            $TransactionMovement->tm_currency_id        = $currency_id;
            $TransactionMovement->save();
        
        $result_array['is_error'] = 0;
         return Response()->json($result_array);
    }
}