<?php
/***********************************************************
ReceiptsController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 15, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

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
use App;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\library\AccountsManager;
use App\library\AccountingManager;
use Models\Account;
use App\models\CRM\CRMAccounts;
use App\models\Billing\Invoices;
use App\models\Accounting\BankAccounts;
use App\models\Billing\PaymentTypes;
use App\models\Billing\PaymentTerms;
use App\models\Billing\InvoiceProducts;
use App\models\Inventory\Products;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use App\models\Billing\InvoicePayments;
use App\models\Billing\Receipts;
use App\models\System\Companies;
use App\models\Inventory\Customers;
use Dompdf\Dompdf;



class ReceiptsController extends Controller
{
    
    /**
     * Page for receipt Management
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function index(Request $request)
    {
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        
        $params_array = array(
            "lst_invoices" => $lst_invoices,
            "lst_customers" => $lst_customers
        );
        
        return Response()->view("receipts.receipts",$params_array);
    }
    
    
    /**
     * Display List of receipts based of credentials 
     * @param Request $request
     */
    public function DisplayList(Request $request)
    {
        //search_query : search_query , start_date : start_date , end_date : end_date ,receipt_customer : receipt_customer , receipt_invoice : receipt_invoice
        
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $receipt_customer       = $request->input('receipt_customer');
        $receipt_invoice        = $request->input('receipt_invoice');
        $start_date             = $request->input('start_date');
        $start_date             = date("Y-m-d",strtotime($start_date));
        $end_date               = $request->input('end_date');
        $end_date               = date("Y-m-d",strtotime($end_date));
        $fisical_year =  $request->input('fisical_year')  !== null ? $request->input('fisical_year') : date("Y");
        
        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;
        
        $firstday = date("Y-m-d",strtotime($strfirstday));
        $lastday = date("Y-m-d",strtotime($strlastday));
        
        
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

        $receipts_cond = Receipts::whereBrIsDeleted(0);
        
        if(strlen($search_query) > 0)
        {
            $receipts_cond = $receipts_cond->where('br_receipt_label' , 'LIKE' , '%' . $search_query . '%');
            $receipts_cond = $receipts_cond->orWhere('br_receipt_note' , 'LIKE' , '%' . $search_query . '%');
        }
          
        if( $receipt_customer > 0 )
        {
            $receipts_cond = $receipts_cond->whereBrCustomerId($receipt_customer);
        }
        
        if( $receipt_invoice > 0 )
        {
            $receipts_cond = $receipts_cond->whereFkInvoiceId($receipt_invoice);
        }
        
        if( strlen($start_date) > 0 )
        {
            $receipts_cond = $receipts_cond->where('br_receipt_date', '>=' , $start_date);
        }
        
        if( strlen($end_date) > 0 )
        {
            $receipts_cond = $receipts_cond->where('br_receipt_date', '<' , $end_date);
        }
        
        if(strlen($start_date) ==  0 && strlen($end_date) ==  0)
        {
            $receipts_cond= $receipts_cond->whereBetween('br_receipt_date', [$firstday, $lastday]);
        }
        
        
         
        $receipts_count= $receipts_cond->count();
        
        
            $total_pages = ceil( $receipts_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $receipts =new Receipts();
      
            
        $receipts= $receipts_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('br_id', 'ASC')->get();

            $data = array(
                "receipts" => $receipts
            );
            
            $result_array = array();
            
            $result_array['total_pages'] = $total_pages;
            $result_array['display'] = view("receipts.displaylist",$data)->render();
            
            return Response()->json($result_array);
    }
    
    /**
     * Display Page for Add Receipt Form
     * 
     *  @author Moe Mantach
     *  @access public
     * @param Request $request
     */
    public function AddForm(Request $request)
    {
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::all();
        $lst_currencies= Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        $invoice_id = 0;
        $AccountingManager = new AccountingManager();
        
        $receipt_code = $AccountingManager->GenerateReceiptCode();
        
        $params_array = array(
            "receipt_code" => $receipt_code,
            "lst_payment_types" => $lst_payment_types,
            "lst_invoices" => $lst_invoices,
            "invoice_id" => $invoice_id,
            "lst_currencies" => $lst_currencies,
            "lst_accounts" => $lst_accounts,
            "lst_customers" => $lst_customers
        );
        return Response()->view("receipts.addform",$params_array);
    }
    
    
    public function AddReceiptFromInvoice(Request $request)
    {
        $invoice_id = $request->input('invoice_id');
        
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::all();
        $lst_currencies= Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        
        $AccountingManager = new AccountingManager();
        
        $receipt_code = $AccountingManager->GenerateReceiptCode();
        
        $params_array = array(
            "receipt_code" => $receipt_code,
            "lst_payment_types" => $lst_payment_types,
            "lst_invoices" => $lst_invoices,
            "lst_currencies" => $lst_currencies,
            "lst_accounts" => $lst_accounts,
            "invoice_id" => $invoice_id,
            "lst_customers" => $lst_customers
        );
        return Response()->view("receipts.addform",$params_array);
    }
    
    
    
    public function EditForm($br_id)
    { 
        
        $receipt_info = Receipts::find($br_id);
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::all();
        $lst_currencies= Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        
        $AccountingManager = new AccountingManager();
        
        $receipt_code = $AccountingManager->GenerateReceiptCode();
        
        $params_array = array(
            "receipt_code" => $receipt_code,
            "receipt_info" => $receipt_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_invoices" => $lst_invoices,
            "lst_currencies" => $lst_currencies,
            "lst_accounts" => $lst_accounts,
            "lst_customers" => $lst_customers
        );
        return Response()->view("receipts.editform",$params_array);
    }
    
    public function EditIReceiptForm($bi_id , $br_id)
    { 
        
        $receipt_info = Receipts::find($br_id);
        $lst_invoices = Invoices::whereBiIsDeleted(0)->get();
        $lst_customers = Customers::whereIcIsDeleted(0)->get();
        $lst_payment_types = PaymentTypes::all();
        $lst_currencies= Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        
        $AccountingManager = new AccountingManager();
        
        $receipt_code = $AccountingManager->GenerateReceiptCode();
        
        $params_array = array(
            "receipt_code" => $receipt_code,
            "receipt_info" => $receipt_info,
            "lst_payment_types" => $lst_payment_types,
            "lst_invoices" => $lst_invoices,
            "lst_currencies" => $lst_currencies,
            "invoice_redirect" => 1,
            "lst_accounts" => $lst_accounts,
            "lst_customers" => $lst_customers
        );
        return Response()->view("receipts.editform",$params_array);
    }
    
    
    public function SaveReceiptInfo(Request $request)
    {
         
        
        $br_id                      = $request->input("br_id");
     
        $br_receipt_number          = $request->input("br_receipt_number");
        $br_account_id              = $request->input("br_account_id");
        $br_customer_id             = $request->input("br_customer_id");
        $br_receipt_label           = $request->input("br_receipt_label");
        $br_receipt_date            = $request->input("br_receipt_date");
        $br_receipt_date            = date("Y-m-d",strtotime($br_receipt_date));
        $fk_payment_type            = $request->input("fk_payment_type");
        $fk_invoice_id              = $request->input("fk_invoice_id");
        $br_payment_value           = $request->input("br_payment_value");
        $br_receipt_currency        = $request->input("br_receipt_currency");
        $br_receipt_note            = $request->input("br_receipt_note");
        $br_second_currency_id      = $request->input("br_second_currency_id");
        $br_exchange_rate           = $request->input("br_exchange_rate");
        $br_receipt_paid            = $request->has("br_receipt_paid") ? 1 : 0;
         $at_id = 0;
        if($br_id > 0)
        {
            $receipt_info = Receipts::find($br_id);
            $receipt_info->br_last_updated_by = session("user_id");
            $at_id = $receipt_info->br_trans_id;
        }
        else 
        {
            $receipt_info = new Receipts();
            $receipt_info->br_created_by    = session("user_id");
        }
        $receipt_info->br_receipt_number = $br_receipt_number;
        $receipt_info->br_account_id        = $br_account_id;
        $receipt_info->br_customer_id       = $br_customer_id;
        $receipt_info->br_receipt_label     = $br_receipt_label;
        $receipt_info->br_receipt_date      = $br_receipt_date;
        $receipt_info->br_creation_date     = date("Y-m-d");
        $receipt_info->br_payment_type      = $fk_payment_type;
        $receipt_info->fk_invoice_id        = $fk_invoice_id;
        $receipt_info->br_payment_value     = $br_payment_value;
        $receipt_info->br_receipt_currency  = $br_receipt_currency;
        $receipt_info->br_receipt_note      = $br_receipt_note;
        $receipt_info->br_receipt_paid      = $br_receipt_paid;
        $receipt_info->br_second_currency_id= $br_second_currency_id;
        $receipt_info->br_exchange_rate     = $br_exchange_rate;
        $receipt_info->br_company_id        = Session("company_id");
        $receipt_info->save();
        
        $br_id = $receipt_info->br_id;
        
 
        
      
        $amount_value = $br_payment_value;
        $amount_currency = $br_receipt_currency;
        
        if($br_second_currency_id > 0)
        {
            $amount_value= $br_payment_value * $br_exchange_rate;
            $amount_currency= $br_second_currency_id;
        }
        
        // check if the receipt is paid 
        if($br_receipt_paid == 1)
        {   
            
            
            $trans_id = $receipt_info->br_trans_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();
                
            }
            
            $AccTransaction = new Transactions();
            $AccTransaction->at_transaction_date    = $br_receipt_date;
            $AccTransaction->at_creation_date       = date("Y-m-d");
            $AccTransaction->at_accounting_doc      = $br_receipt_label;
            $AccTransaction->fk_acc_journal_id      = 3;
            $AccTransaction->save();
            $at_id = $AccTransaction->at_id;
            
            $customer_info  = Customers::find($br_customer_id);
            $payment_info   = PaymentTypes::find($fk_payment_type);
            
            $pt_payment_account = $payment_info->pt_payment_account;
            
            
            
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
            $TransactionMovement->tm_ledger_label       = $br_receipt_label;
            $TransactionMovement->tm_debit              = 0;
            $TransactionMovement->tm_credit             = $br_payment_value;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $br_receipt_date;
            $TransactionMovement->tm_currency_id        = $br_receipt_currency;
            $TransactionMovement->save();
            
            $TransactionMovement = new TransactionMovements();
            $TransactionMovement->fk_tran_id            = $at_id;
            $TransactionMovement->tm_ledger_account     = $pt_payment_account;
            $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
            $TransactionMovement->tm_ledger_label       = $br_receipt_label;
            $TransactionMovement->tm_debit              = $amount_value;
            $TransactionMovement->tm_credit             = 0;
            $TransactionMovement->tm_creation_date      = date("Y-m-d");
            $TransactionMovement->tm_transaction_date   = $br_receipt_date;
            $TransactionMovement->tm_currency_id        = $amount_currency;
            $TransactionMovement->save();
          
            $receipt_info = Receipts::find($br_id); 
			if($at_id != null)
			{
				$receipt_info->br_trans_id = $at_id;
			}
			$receipt_info->save();
        }
		else
		{
			$trans_id = $receipt_info->br_trans_id;
            if( $trans_id > 0 )
            {
                $delete_trans = Transactions::where('at_id',$trans_id)->delete();
                $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();
                
            }
		}
		
        

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
   /**
    * Display List of Receipts related to the invoice bi_id
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    */
    public function DisplayListReceiptsInvoice(Request $request)
    {
        $bi_id                  = $request->input("bi_id");
        $lst_invoice_receipts   = Receipts::whereFkInvoiceId($bi_id)->get();
        $currency_info          = Currency::all();
        $currencies_array       = CreateDatabaseArrayByIndex($currency_info, "cc_id");
        $result_array           = array();
        
        $data = array(
            "lst_invoice_receipts" => $lst_invoice_receipts,
            "currencies_array" => $currencies_array
        );
        $result_array['display'] = view('billing.listinvoicereceipts',$data)->render();
        $result_array['receipt_count'] = count($lst_invoice_receipts);
         
        return Response()->json($result_array);
    }
    
    
    /**
     * Generate Receipts based on selected invoice and payments added to the invoice
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateReceipts(Request $request)
    {
        
        $bi_id          = $request->input('bi_id');
        $invoice_info   = Invoices::find($bi_id);
        $result_array   = array();
        $lst_invoice_payments   = InvoicePayments::whereFkInvoiceId($bi_id)->get();
        
        if(count($lst_invoice_payments) == 0) // there is no settlement payment
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "there is no payment settlement, Create Number of Payments to be able to generate receipts";
            return Response()->json($result_array);
        }
        
        $AccountingManager      = new AccountingManager();
        
        foreach ( $lst_invoice_payments as $key => $payment_info )
        {
            $invoiceReceipts                        = new Receipts();
            $invoiceReceipts->fk_invoice_id         = $bi_id;
            $invoiceReceipts->fk_payment_id         = $payment_info->ip_id;
            $invoiceReceipts->br_account_id         = $invoice_info->fk_account_id;
            $invoiceReceipts->br_customer_id        = $invoice_info->fk_customer_id;
            $invoiceReceipts->br_payment_type       = $payment_info->ip_payment_type;
            $invoiceReceipts->br_receipt_number     = $AccountingManager->GenerateReceiptCode($bi_id); 
            $invoiceReceipts->br_creation_date      = date('Y-m-d');
            $invoiceReceipts->br_receipt_date       = date('Y-m-d');
            $invoiceReceipts->br_company_id         = session('company_id');
            $invoiceReceipts->br_receipt_label      = $payment_info->ip_payment_label;
            $invoiceReceipts->br_payment_value      = ( $payment_info->ip_payment_percentage / 100 ) * $invoice_info->bi_total_price;
            $invoiceReceipts->br_receipt_currency   = $invoice_info->bi_invoice_currency;
            $invoiceReceipts->save();
        }
        
        unset($AccountingManager);
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * Generate PDF of the receipt and show it in a new page
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $br_id
     */
    public function DownloadReceipt( $br_id )
    {
        $receipt_info = Receipts::find( $br_id );
        $fk_invoice_id  = $receipt_info->fk_invoice_id;
        $br_client_id   = $receipt_info->br_client_id;
        $br_company_id  = $receipt_info->br_company_id;
        
        $company_info = Companies::find($br_company_id);
        if($fk_invoice_id == 0)
            $invoice_info = new Invoices();
        else
            $invoice_info = Invoices::find($fk_invoice_id);
        $lst_currencies = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        
        $data = array(
            "receipt_info" => $receipt_info,
            "currencies_array" => $currencies_array,
        );
        $receipt_table= view("billing.receiptpayment",$data)->render();
        
        
        $display = view("templates.receipts",array())->render();
        
        
        $display = str_replace("%company_name%",$company_info->cd_company_name, $display);
        $display = str_replace("%company_address%",$company_info->cd_company_address, $display);
        $display = str_replace("%company_phone%",$company_info->cd_company_phone, $display);
        if($fk_invoice_id > 0)
            $display = str_replace("%invoice_number%",$invoice_info->bi_invoice_ref, $display);
        else 
            $display = str_replace("%invoice_number%","", $display);
        $display = str_replace("%receipt_number%",$receipt_info->br_receipt_number, $display);
        $display = str_replace("%receipt_date%",$receipt_info->br_receipt_date, $display);
        $display = str_replace("%receipt_description%",$receipt_info->br_receipt_note, $display);
        $display = str_replace("%receipt_table%",$receipt_table, $display);
        
        $pdf = new Dompdf();
        $pdf->loadHTML($display);
        $pdf->render();
        return $pdf->stream('receipt-' . strtolower($receipt_info->br_receipt_number) . '.pdf');
    }
    
    
    
    /**
     * Change flag for paid from 0 to 1
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function PayReceipt(Request $request)
    {
        $br_id          = $request->input('br_id');
        $result_array   = array();
        
        $receipt_info   = Receipts::find($br_id);
        $invoice_id     = $receipt_info->fk_invoice_id;
        //get invoice information
        $invoice_info = Invoices::find($receipt_info->fk_invoice_id);
        
        $at_id = $invoice_info->bi_transaction_id;
        
        // if the transaction is 0 create a transaction record
        if($at_id == 0)
        {
            $transaction_obj = new Transactions();
            $transaction_obj->at_transaction_date   = date('Y-m-d');
            $transaction_obj->at_creation_date      = date('Y-m-d');
            $transaction_obj->at_accounting_doc     = "Transaction for Invoice #" . $invoice_info->bi_invoice_code;
            $transaction_obj->fk_acc_journal_id     = 1;
            $transaction_obj->at_currency_id        = $invoice_info->bi_invoice_currency;
            $transaction_obj->save();
            $at_id = $transaction_obj->at_id;
            
        }
        
        $invoice_info->bi_transaction_id = $at_id;
        $invoice_info->save();
        
        
        // get account of payment type
        $invoice_payment_type   = $invoice_info->bi_payment_type;
        $payment_type_info      = PaymentTypes::find($invoice_payment_type);
        $pt_payment_account     = $payment_type_info->pt_payment_account;
        
        //get information of the customer
        $customer_info          = Customers::find( $invoice_info->fk_customer_id );
        
        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
        $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
        $TransactionMovement->tm_ledger_label       = $receipt_info->br_receipt_number . " For the Invoice #" . $invoice_info->bi_invoice_code;
        $TransactionMovement->tm_debit              = $receipt_info->br_payment_value;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_currency_id        = $receipt_info->br_receipt_currency;
        $TransactionMovement->save();
        
        $receipt_info->br_receipt_paid = 1;
        $receipt_info->br_receipt_date = date("Y-m-d");
        $receipt_info->save();
        
        // check if all receipt for invoice is paid we convert the invoice to paid
        $count_receipts = Receipts::whereFkInvoiceId( $invoice_id )->whereBrReceiptPaid(0)->count();
        if($count_receipts == 0)
        { 
            $invoice_info->bi_invoice_paid = 1;
            $invoice_info->save();
        }
        
        $lst_invoice_items =  InvoiceProducts::whereFkInvoiceId($invoice_id)->get();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Receipt From the database
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function DeleteReceiptInfo(Request $request)
    {
        $br_id          = $request->input("br_id");
        $receipt_info   = Receipts::find($br_id);
        
        $trans_id = $receipt_info->br_trans_id;
        if( $trans_id > 0 )
        {
            $delete_trans = Transactions::where('at_id',$trans_id)->delete();
            $delete_mov = TransactionMovements::where('fk_tran_id',$trans_id)->delete();
            
        }
        
        $receipt_info->br_is_deleted = 1;
        $receipt_info->br_deleted_by = session('user_id');
        $receipt_info->save();
    
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";
        return Response()->json($result_array);
    }
    
    
    
}