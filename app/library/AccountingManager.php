<?php
/***********************************************************
AccountingManager.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
namespace App\library;


use Validator;
use Input;
use Config;
use Session;
use Redirect;
use Crypt;
use Cookie;
use Auth;
use DB;
use File;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMAccounts;
use App\models\Billing\InvoiceProducts;
use App\models\Accounting\VatAccounts;
use App\models\Billing\Invoices;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\CRM\CRMServices;
use App\models\System\Companies;
use App\models\Billing\Receipts;
use App\models\Billing\PaymentVouchers;
use App\models\Accounting\Transactions;
use App\models\Billing\CreditNotes;
use App\models\Billing\DebitNotes;
use App\models\Billing\InternalTransfers;
use App\models\Billing\JournalVouchers;
use App\models\Accounting\TransactionMovements;


class AccountingManager
{
    
        /**
         * Generate Array contain movement in the list of transactions
         * @param unknown $lst_transactions
         * @param unknown $lst_movements
         * @return array
         */
    public function GenerateTransactionsArray( $lst_movements , $transactions_array)
    {
        $transaction_movements_array = array(); 
       // create database array of movement 
        $index = 0;
 
        foreach ($lst_movements as $key => $movement_info ) 
        {
            //if the transaction section of the array not exist we need to reset the index to 0 in order to add a new parameter
            if(!isset( $transaction_movements_array[ $movement_info->fk_tran_id ]))
                $index = 0;
            else 
                $index = count($transaction_movements_array[ $movement_info->fk_tran_id ]);
            
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['tm_id']                 = $movement_info->tm_id;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['ledger_account']        = $movement_info->tm_ledger_account;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['sub_ledger_account']    = $movement_info->tm_sub_ledger_account;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['ledger_label']          = $movement_info->tm_ledger_label;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['debit']                 = $movement_info->tm_debit;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['credit']                = $movement_info->tm_credit;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['currency']                = $movement_info->currency->cc_currency_code;
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['transaction_date']      = $transactions_array[$movement_info->fk_tran_id]['at_transaction_date'];
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['accounting_doc']        = $transactions_array[$movement_info->fk_tran_id]['at_accounting_doc'];
            $transaction_movements_array[ $movement_info->fk_tran_id ][$index]['journal']               = $transactions_array[$movement_info->fk_tran_id]['fk_acc_journal_id'];
            $index++;
        }
        
        return $transaction_movements_array;
    }
    
    /**
     * Get Total Debit and credit for this list of movements as an array
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $lst_movements
     * 
     * @return array $result_array 
     */
    public function GetTotalDebitCredits( $lst_movements)
    {
        $result_array = array();
        $result_array['debit']  = 0;
        $result_array['credit'] = 0;
        
        foreach ($lst_movements as $key => $movement_info ) {
            $result_array['debit']  += $movement_info->tm_debit;
            $result_array['credit'] += $movement_info->tm_credit;
        }
        
        return $result_array;
    }
    
    /**
     * Get Total Account Balance Array 
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $lst_movements
     * @return array|number
     */
    public function GetTotalAccountBalance($lst_movements)
    {
        $result_array = array();
        
        foreach ( $lst_movements as $key => $movement_info ) {
            $currency_id = $movement_info->currency->cc_id;
            $account_id = $movement_info->tm_sub_ledger_account;
            if(!isset($result_array[ $currency_id ][$account_id]))
            {
                $result_array[ $currency_id ][ $account_id ]['debit']  = 0;
                $result_array[ $currency_id ][ $account_id ]['credit'] = 0;
            } 
            $result_array[ $currency_id ][ $account_id]['debit']                     = $result_array[ $currency_id ][ $account_id ]['debit'] + $movement_info->tm_debit;
            $result_array[ $currency_id ][$account_id]['credit']                    = $result_array[ $currency_id ][ $account_id ]['credit'] + $movement_info->tm_credit;
            $result_array[ $currency_id ][ $account_id]['currency']                  = $movement_info->currency->cc_currency_code;
            $result_array[ $currency_id ][ $account_id]['account_payable']           = $movement_info->tm_ledger_account;
            $result_array[ $currency_id ][ $account_id]['account_receivable']        = $movement_info->tm_sub_ledger_account;
        }
         
        return $result_array;
    }
    
    
    
    public function getTotalCumulativeAccountCreditAndDe($lst_movements)
    {
        
        $result_array = array();
        $index = 0;
        $cumulative_credit = array();
        $cumulative_debit =  array();
        
        foreach ( $lst_movements as $key => $movement_info ) {
            $tm_id      = $movement_info->tm_id;
            $trans_id   = $movement_info->fk_tran_id;
            $account_id   = $movement_info->tm_sub_ledger_account;
            $currency_code =  $movement_info->currency->cc_currency_code;
            $currency_id    =  $movement_info->tm_currency_id;
            
            if(!isset($cumulative_credit[$account_id]))
                $cumulative_credit[$account_id] = array();
            
            if(!isset($cumulative_debit[$account_id]))
                $cumulative_debit[$account_id] = array();
                
                if(!array_key_exists($currency_id, $cumulative_credit[$account_id]) )
            {
                if(!isset($cumulative_credit[$account_id][ $currency_id]))
                    $cumulative_credit[$account_id][ $currency_id] = 0;
                    $cumulative_credit[$account_id][ $currency_id] =  $movement_info->tm_credit;
            }
            else
            {
                $cumulative_credit[$account_id][ $currency_id] =  $cumulative_credit[$account_id][ $currency_id]  + $movement_info->tm_credit;
            }
            
            
            if(!array_key_exists($currency_id, $cumulative_debit[$account_id]) )
            {
                if(!isset($cumulative_debit[$account_id][ $currency_id]))
                    $cumulative_debit[$account_id][ $currency_id] = 0;
                
                    $cumulative_debit[$account_id][ $currency_id] =  $movement_info->tm_debit;
            }
            else
            {
                $cumulative_debit[$account_id][ $currency_id] =  $cumulative_debit[$account_id][ $currency_id]  + $movement_info->tm_debit;
            }
        }
        
        
        $result_array['cumulative_debit'] = $cumulative_debit;
        $result_array['cumulative_credit'] = $cumulative_credit;
        
        return $result_array;
        
    }
    
    
    public function GetListAccountBalanceDetails($lst_movements , $search_query = "" , $params_arrays = array())
    {
        $result_array = array();
       $index = 0;
       $cumulative_credit = array();
       $cumulative_debit =  array();
       $$ck_include_before = $params_arrays['$ck_include_before'];
       
       
        
        foreach ( $lst_movements as $key => $movement_info ) {
            $tm_id      = $movement_info->tm_id;
            $trans_id   = $movement_info->fk_tran_id;
            $transaction_description = "";
            $transaction_date = "";
            $transaction_code = "";
            $invoice_info = Invoices::whereBiTransactionId($trans_id)->whereBiIsDeleted(0)->get();
            $receipt_info = Receipts::whereBrTransId($trans_id)->get();
            $currency_code =  $movement_info->currency->cc_currency_code;
            $transaction_info = Transactions::find($trans_id);
            $voucher_info = PaymentVouchers::wherePvTransactionId($trans_id)->get();
            $internal_info = InternalTransfers::whereFkTransId($trans_id)->get();
            
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
                $transaction_description = $transaction_info->at_accounting_doc; 
                $transaction_date           = $transaction_info->at_transaction_date;
                $transaction_code           = $transaction_description;
            }
           
            
            if(!array_key_exists($currency_code , $cumulative_credit) )
            {
                $cumulative_credit[ $currency_code ] =  $movement_info->tm_credit;
            }
            else 
            {
                $cumulative_credit[ $currency_code ] =  $cumulative_credit[ $currency_code ]  + $movement_info->tm_credit;
            }
            
            
            if(!array_key_exists($currency_code , $cumulative_debit) )
            {
                $cumulative_debit[ $currency_code ] =  $movement_info->tm_debit;
            }
            else
            {
                $cumulative_debit[ $currency_code ] =  $cumulative_debit[ $currency_code ]  + $movement_info->tm_debit;
            }
            
             
            $currency_id    = $movement_info->currency->cc_id;
            $account_id     = $movement_info->tm_sub_ledger_account;
            
            if(isset($result_array[ $currency_id ][ $account_id ]))
            {
                $index = count($result_array[ $currency_id ][ $account_id ]);
                $result_array[ $currency_id ][ $account_id ][ $index ] = array();
            }
            else 
            {
                $result_array[ $currency_id ][ $account_id ][ $index ] = array();
            }
            
            $result_array[ $currency_id ][ $account_id ][ $index ]['debit']                        = $movement_info->tm_debit;
            $result_array[ $currency_id ][ $account_id ][ $index ]['credit']                       = $movement_info->tm_credit;
            
            if($index == 0)
            {
                $result_array[ $currency_id ][ $account_id ][ $index ]['balance'] = $movement_info->tm_debit - $movement_info->tm_credit;
            }
            else {
                
                if($index == 1)
                {
                   
                    //;
                    //dd($prev_balance);
                }
               
                if(isset( $result_array[ $currency_id ][ $account_id ][ $index - 1 ] ))
                    $prev_balance =  $result_array[ $currency_id ][ $account_id ][ $index - 1 ]['balance'];
                else 
                    $prev_balance = 0;
             
                $result_array[ $currency_id ][ $account_id ][ $index ]['balance'] = $prev_balance + $movement_info->tm_debit - $movement_info->tm_credit; 
                 
            }
            
              
            $result_array[ $currency_id ][ $account_id ][ $index ]['tm_id']                        = $tm_id;
            $result_array[ $currency_id ][ $account_id ][ $index ]['trans_id']                     = $trans_id;
            $result_array[ $currency_id ][ $account_id ][ $index ]['currency']                     = $movement_info->currency->cc_currency_code;
            $result_array[ $currency_id ][ $account_id ][ $index ]['date_creation']                = $transaction_date;
            $result_array[ $currency_id ][ $account_id ][ $index ]['account_payable']              = $movement_info->tm_ledger_account;
            $result_array[ $currency_id ][ $account_id ][ $index ]['account_receivable']           = $movement_info->tm_sub_ledger_account;
            $result_array[ $currency_id ][ $account_id ][ $index ]['mov_desc']                     = $transaction_description;
            $result_array[ $currency_id ][ $account_id ][ $index ]['code']                         = $transaction_code;
            
          //  $index++;
        }
        return $result_array;
    }
    
    /**
     * Generate Invoice Code 
     * @author Moe Mantach
     * @access public
     * 
     * @return string  $invoice_code
     * 
     */
    public function GenerateInvoiceCode( $params_array = array() )
    {
        $company_id     = isset( $params_array['company_id'] ) ? $params_array['company_id'] : session('company_id');
        $fyear     = isset( $params_array['fyear'] ) ? $params_array['fyear'] : date("Y");
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = $fyear;
        $count_invoices = Invoices::whereYear('bi_invoice_date' , $year)->count();
        
        $index = $count_invoices + 1;
    
        
        $invoice_code = "inv" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $invoice_code;
        
    }
    
    
    
    /**
     * Generate Credit Note Code Saved to save in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param array $params_array
     * @return string
     */
    public function GenerateINCode( $params_array = array() )
    {
        $company_id     = isset( $params_array['company_id'] ) ? $params_array['company_id'] : session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_internal = InternalTransfers::whereYear('in_transfer_date' , $year)->count();
        
        $index = $count_internal + 1;
        
        
        $credit_code = "IT". $year . "-" . sprintf('%04d', $index);
        
        return $credit_code;
        
    }
    
    
 
    
    
    /**
     * get voucher code
     * 
     * @author Moe Mantach
     * @access public
     * @return string
     */
    public function GetVoucherCode($fyear = "")
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = $fyear != "" ? $fyear : date("Y");
        $count_vouchers = PaymentVouchers::where('pv_is_deleted' , 0)->whereYear('pv_creation_date' , $year)->count();
        
        $index = $count_vouchers + 1;
    
        
        $vouchers_code = "PV" . strtoupper($cd_company_name[0])  . "-" . $year . "-" . sprintf('%04d', $index);
        
        return $vouchers_code;
        
    }
    
    
    /**
     * get code of journal voucher
     * 
     * @author Moe Mantach
     * @access public
     * @return string
     * 
     */
    public function GetJournalVoucherCode( $fyear = "" )
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = $fyear;
        $count_journal_vouchers = JournalVouchers::where('pj_is_deleted' , 0)->count();
        
        $index = $count_journal_vouchers+ 1;
        
        
        $vouchers_code = "JV" . strtoupper($cd_company_name[0])  . sprintf('%04d', $index);
        
        return $vouchers_code;
        
    }
    
    
    
    /**
     * Generate Receipt Code 
     * @author Moe Mantach
     * @access public
     * 
     * @return string  $receipt_code
     */
    public function GenerateReceiptCode( $bi_id = 0 , $fyear = "")
    {
        $fyear          = ($fyear != "") ? $fyear : date("Y");
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = $fyear;
        $count_receipts = Receipts::whereYear('br_creation_date' , $year)->count();
        
        $index = $count_receipts + 1;
        
        
        $receipt_code = "rec" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $receipt_code;
        
    }
   
    /**
     * Calculation of total price of the invoice ( saved products )
     * 
     * @author Moe Mantach
     * @access public
     * @param unknown $params_array
     * $params_array['invoice_id'] : id of invoice that should calculate the total in
     * $params_array['tax_id'] : tax used to apply in total invoice
     * 
     */
    public function CalculateTotalCostInvoice( $params_array )
    { 
        $invoice_info   = $params_array['invoice_info'];
        $invoice_id     = $invoice_info->bi_id;
        $tax_id         = $invoice_info->bi_vat_id;
        $bi_invoice_type= $invoice_info->bi_invoice_type;
        $result_array   = array(); 
        $lst_invoice_products   = InvoiceProducts::whereFkInvoiceId($invoice_id)->get();
        $tax_info               = VatAccounts::find($tax_id);
        $lst_currency           = Currency::all();
        $currencies_array       = CreateDatabaseArrayByIndex($lst_currency, "cc_id");
        $total_cost             = 0;
        $total_invoice_value    = 0;
        $invoice_currency       = $invoice_info->bi_invoice_currency;
        $items_array            = array();
        $index = 0;
         
        foreach ( $lst_invoice_products as $key => $item_info ) {
            $total_price                    = $item_info->ii_total_price;
            $ii_item_id                     = $item_info->ii_item_id;
            
            $item_currency  = $item_info->ii_price_currency;
            $item_price     = $item_info->ii_total_price;
            $item_cost      = $item_info->ii_item_price;
            
            
            // if currency product different then currency of invoice convert it to
            if($item_currency != $invoice_currency)
            {
                //$item_currency
                $cc_currency_code = ( $item_currency > 0 ) ? $currencies_array[$item_currency]['cc_currency_code'] : session("currency_symbol");
                $invoice_currency_code = ( $invoice_currency > 0 ) ? $currencies_array[$invoice_currency]['cc_currency_code'] : session("currency_symbol");

                $item_price = convertCurrency($item_price,$cc_currency_code,$invoice_currency_code);
                $item_cost  = convertCurrency($item_cost,$cc_currency_code,$invoice_currency_code);
            }
            
             
            switch($bi_invoice_type)
            {
                case 1:
                    {
                        $product_info = Products::find($ii_item_id);
                        $items_array[$index]['id']          = $item_info->ii_id;
                        $items_array[$index]['p_id']       = $product_info->p_id;
                        $items_array[$index]['label']       = $product_info->p_product_name;
                        $items_array[$index]['quantity']    = $item_info->ii_item_qyt;
                        $items_array[$index]['price']       = $item_price;
                        $items_array[$index]['cost']        = $item_cost;
                        $items_array[$index]['currency']    = $currencies_array[$invoice_currency]['cc_currency_code'];
                    }
                break;
                case 2:
                    {
                        $services_info = CRMServices::find($ii_item_id);
                        $items_array[$index]['id']          = $item_info->ii_id;
                        $items_array[$index]['cs_id']       = $services_info->cs_id;
                        $items_array[$index]['label']       = $services_info->cs_service_title;
                        $items_array[$index]['quantity']    = $item_info->ii_item_qyt;
                        $items_array[$index]['price']       = $item_price;
                        $items_array[$index]['cost']        = $item_cost;
                        $items_array[$index]['currency']    = $currencies_array[$invoice_currency]['cc_currency_code'];
                    }
                break;
                case 0:
                    {
                        $items_array[$index]['id']          = $item_info->ii_id;
                        $items_array[$index]['label']       = $item_info->ii_item_label;
                        $items_array[$index]['quantity']    = $item_info->ii_item_qyt;
                        $items_array[$index]['price']       = $item_price;
                        $items_array[$index]['cost']        = $item_cost;
                        $items_array[$index]['currency']    = $currencies_array[$invoice_currency]['cc_currency_code'];
                    }
                break;
            } 
            
            $total_invoice_value        = $total_invoice_value + $item_price;
            $index++;
        } 
        $av_vat_rate = ( $tax_id == 0 ) ? 0 : $tax_info->av_vat_rate;
        $result_array['items_array']    = $items_array;
        $result_array['total_cost']     = $total_invoice_value;
        $result_array['total_discount'] = $invoice_info->bi_discount;
        $result_array['total_tax']      = $av_vat_rate;
        $result_array['currency']       = $currencies_array[$invoice_currency]['cc_currency_code'];
        $result_array['total_price']    = $total_invoice_value  + ( $av_vat_rate* $total_invoice_value ) - ( ($result_array['total_discount']/100) * $total_invoice_value );
        return $result_array;
    }
    
     /**
      * Generate total amount of payable Accounts
      * @access public
      * @author Moe Mantach
      * @param Array $params_array
      * $params_array['fisical_year'] : year 
      * $params_array['account_id'] : account that we need to calculate 
      * 
      */                                                                                                                                                                                                                                                                                                                                                                                                                                                       
    public function GeneratTotalAccountAmount($params_array)
    {
        $fisical_year   = $params_array['fisical_year'];
        $account_id     = $params_array['account_id'];
        $strfirstday = 'first day of January ' . $fisical_year;
        $strlastday = 'last day of December ' . $fisical_year;
        $result_array = array();
        
        $firstday = date("Y-m-d",strtotime($strfirstday));
        $lastday = date("Y-m-d",strtotime($strlastday));
        
        $lst_movements = TransactionMovements::whereTmIsDeleted(0)->whereBetween('tm_transaction_date', [$firstday, $lastday])->whereTmSubLedgerAccount($account_id)->get();
        $movement_data = array();
        
        foreach ($lst_movements as $index => $movement_info) 
        {
            
            if(!isset($movement_data[$movement_info->tm_ledger_account][$movement_info->tm_sub_ledger_account][$movement_info->tm_currency_id]))
            {
                $movement_data[$movement_info->tm_ledger_account][$movement_info->tm_sub_ledger_account][$movement_info->tm_currency_id]['credit'] = $movement_info->tm_credit;
                $movement_data[$movement_info->tm_ledger_account][$movement_info->tm_sub_ledger_account][$movement_info->tm_currency_id]['dedit'] = $movement_info->tm_debit;
            }
            else {
                
                $movement_data[$movement_info->tm_ledger_account][$movement_info->tm_sub_ledger_account][$movement_info->tm_currency_id]['credit'] += $movement_info->tm_credit;
                $movement_data[$movement_info->tm_ledger_account][$movement_info->tm_sub_ledger_account][$movement_info->tm_currency_id]['dedit']  += $movement_info->tm_debit;
            }
            
             
        }
        
        $result_array['is_error'] = 0;
        $result_array['movement_data'] = $movement_data;
        
        return $result_array;
    }
}