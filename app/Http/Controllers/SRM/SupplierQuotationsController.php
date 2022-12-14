<?php
/***********************************************************
SupplierQuotationsController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\SRM;

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
use App\models\SRM\SupplierStatus;
use App\models\SRM\SuppliersBidding;
use App\models\SRM\SupplierQuotations;
use App\models\SRM\Suppliers;
use App\models\System\Currency;
use App\models\Users\Users;
use App\models\SRM\SupplierContracts;
use App\library\SRMManager;
use App\models\Inventory\Products;
use App\models\SRM\SupplierProducts;
use App\models\System\CurrencyExchangeRates;
use App\models\Inventory\Stocks;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Inventory\WareHouses;
use App\models\Inventory\StockIds;



class SupplierQuotationsController extends Controller
{

    /**
     * Page to control SRM Quotations Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_suppliers     = Suppliers::whereSsIsDeleted(0)->get();
        $lst_warehouses     = WareHouses::whereWIsDeleted(0)->get();
       
        $data = array(
            "lst_suppliers" => $lst_suppliers,
            "lst_warehouses" => $lst_warehouses,
        );
        return Response()->view('srm.quotations',$data);
    }
    
    
   /**
    * Display list of Supplier Quotations saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {        
        $lst_suppliers          = Suppliers::whereSsIsDeleted(0)->get();
        $suppliers_array        = CreateDatabaseArrayByIndex($lst_suppliers, "ss_id");
        $lst_currency           = Currency::all();
        $currencies_array       = CreateDatabaseArrayByIndex($lst_currency, 'cc_id');
        $lst_supplier_bidding   = SuppliersBidding::whereSbIsDeleted(0)->get();
        $bidding_array          = CreateDatabaseArrayByIndex($lst_supplier_bidding, "sb_id");
  
        
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('general_search');
        $quotation_warehouse    = $request->input('quotation_warehouse');
        $quotation_supplier     = $request->input('quotation_supplier');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
       
        $lst_supplier_quotations = SupplierQuotations::whereSqIsDeleted(0);
        
        if(strlen($search_query) > 0)//
            $lst_supplier_quotations = $lst_supplier_quotations->where('sq_quotation_notes','LIKE','%' . $search_query . '%');
        if( $quotation_warehouse > 0 )//
            $lst_supplier_quotations = $lst_supplier_quotations->where('sq_warehouse_id','=',$quotation_warehouse);
        if( $quotation_supplier > 0 )//
            $lst_supplier_quotations = $lst_supplier_quotations->where('fk_supplier_id','=',$quotation_supplier);
    
        $count_quotation =  $lst_supplier_quotations->count();
        $lst_supplier_quotations = $lst_supplier_quotations->skip($skip)->take($nbr_rows_per_pages)->get();
       
        
        $total_pages = ceil( $count_quotation/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $supplier_categories_array   = array(); 
        
        $data = array(
            "lst_supplier_quotations" => $lst_supplier_quotations,
            "suppliers_array" => $suppliers_array,
            "bidding_array" => $bidding_array,
            "currencies_array" => $currencies_array
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.lstquotations",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_suppliers          = Suppliers::whereSsIsDeleted(0)->get();
        $lst_currency           = Currency::all();
        $lst_supplier_bidding   = SuppliersBidding::whereSbIsDeleted(0)->get();
        $lst_users              = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_warehouses         = WareHouses::whereWIsDeleted(0)->whereWWarehouseStatus(1)->get();
        $company_currency       = session('company_currency');
        $user_id                = session('user_id');
        $SRMManager             = new \App\Library\SRMManager();
        $quotation_code         = $SRMManager->GenerateQuotationCode();
        
        $data = array(
            "lst_suppliers"         => $lst_suppliers,
            "lst_users"             => $lst_users,
            "lst_currency"          => $lst_currency,
            "lst_warehouses"          => $lst_warehouses,
            "company_currency"      => $company_currency,
            "user_id"               => $user_id,
            "quotation_code"        => $quotation_code,
            "lst_supplier_bidding"  => $lst_supplier_bidding,
        );
        return Response()->view('srm.addquotation',$data);
    }
    
    
    /**
     * Form to scan Serials 
     * @param Request $request
     */
    public function AddSerials(Request $request)
    {
        $index = $request->input('index');
        $serial_numbers = $request->input('serial_numbers');
        $stock_quantity = $request->input('stock_quantity');
        $serial_numbers_array = array();
        
        if(strlen($serial_numbers) > 0)
            $serial_numbers_array = explode(',', $serial_numbers);
        
        $data = array(
            "index" => $index,
            "stock_quantity" => $stock_quantity,
            "serial_numbers_array" => $serial_numbers_array
        );
        return Response()->view('srm.serials',$data);
    }
    
    /**
     * Edit Form Page for supplier Quotation
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $sq_id )
    {
        $bidding = new SuppliersBidding();
        $supplier_quotation     = SupplierQuotations::find($sq_id);
        $lst_suppliers          = Suppliers::whereSsIsDeleted(0)->get();
        $lst_currency           = Currency::all();
        $lst_users              = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_warehouses         = WareHouses::whereWIsDeleted(0)->whereWWarehouseStatus(1)->get();
        $quotation_products = SupplierProducts::whereFkQuotationId($sq_id)->get();
       
        $data = array(
            "supplier_quotation" => $supplier_quotation,
            "lst_supplier_bidding" => $bidding,
            "lst_warehouses" => $lst_warehouses,
            "lst_suppliers" => $lst_suppliers,
            "lst_users" => $lst_users,
            "lst_currency" => $lst_currency,
            "quotation_products" => $quotation_products
        );  
        return Response()->view('srm.editquotation',$data);
    }
    
    
    /**
     * View and display purshase quotation 
     * @param unknown $sq_id
     * @return unknown
     */
    public function ViewPurshaseQuotation( $sq_id )
    {
        
        $supplier_quotation     = SupplierQuotations::find($sq_id);
        $lst_suppliers          = Suppliers::whereSsIsDeleted(0)->get();
        $lst_currency           = Currency::all();
        $lst_users              = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        
        $quotation_products = SupplierProducts::whereFkQuotationId($sq_id)->get();
        
        $data = array(
            "supplier_quotation" => $supplier_quotation,
            "lst_suppliers" => $lst_suppliers,
            "lst_users" => $lst_users,
            "lst_currency" => $lst_currency,
            "quotation_products" => $quotation_products
        );
        return Response()->view('srm.viewpurchasequotation',$data);
    }
    
    /**
     * Approve Quotation and Create A new Contract with the information already
     * Added to the Quotation
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Approvequotation( Request $request )
    {
        $sq_id          = $request->input('sq_id');
        $quotation_info = SupplierQuotations::find($sq_id);
        $quotation_info->sq_quotation_approve = 1;
        $quotation_info->save();
        $bidding_info = SuppliersBidding::find( $quotation_info->fk_bid_id );
        
        $ContManager    = new SRMManager();
        $contract_code  = $ContManager->GenerateContractCode();
        
        $contract_info  = new SupplierContracts();
        $contract_info->fk_supplier_id              = $quotation_info->fk_supplier_id;
        $contract_info->sc_code                     = $contract_code;
        $contract_info->sc_contract_title           = $bidding_info->sb_bid_title;
        $contract_info->sc_contract_date            = date("Y-m-d");
        $contract_info->sc_contract_delivery_date   = $quotation_info->sq_due_date;
        $contract_info->sc_creation_date            = date("Y-m-d");
        $contract_info->sc_contract_description     = $bidding_info->sb_bid_description;
        $contract_info->fk_user_owner               = $quotation_info->sq_user_id;
        $contract_info->sc_contract_notes           = $quotation_info->sq_quotation_notes;
        $contract_info->sc_total_cost               = $quotation_info->sq_total_price;
        $contract_info->sc_discount                 = 0;
        $contract_info->sc_total_price              = $quotation_info->sq_total_price;
        $contract_info->sc_currency_id              = $quotation_info->sq_currency_id;
        $contract_info->save();
        $sc_id = $contract_info->sc_id;
        
        $result_array = array();
        
        $result_array['is_error']   = 0;
        $result_array['sc_id']      = $sc_id;
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Supplier Quotation from the database by change flag is_deleted of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierQuotationInfo(Request $request)
    {
        
        $sq_id= $request->input('sq_id');
        
        $supplier_quotation = SupplierQuotations::find( $sq_id );
        $supplier_quotation->sq_is_deleted      = 1;
        $supplier_quotation->sq_deleted_by      = Session('user_id');
        $supplier_quotation->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Save Supplier Status Info to saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierQuotationInfo(Request $request)
    {
        $sq_id                      = $request->input('sq_id');
        $sp_id                      = $request->input('sp_id');
        $fk_bid_id                  = $request->input('fk_bid_id');
        $fk_supplier_id             = $request->input('fk_supplier_id');
        $sq_user_id                 = $request->input('sq_user_id');
        $sq_date_submit             = date("Y-m-d");
        $sq_due_date                = $request->input('sq_due_date');
        $sq_quotation_notes         = $request->input('sq_quotation_notes');
        $sq_total_price             = $request->input('sq_total_price');
        $sq_currency_id             = $request->input('sq_currency_id');
        
        $serial_numbers             = $request->input('serial_numbers');
        $pr_quantity                = $request->input('pr_quantity');
        $pr_product_code            = $request->input('pr_product_code');
        $pr_product_name            = $request->input('pr_product_name');
        $pr_description             = $request->input('pr_description');
        $pr_pruchase_price          = $request->input('pr_pruchase_price');
        $pr_discount                = $request->input('pr_discount');
        $pr_selling_price           = $request->input('pr_selling_price');
        $pr_wholesale_price         = $request->input('pr_wholesale_price');
        $pr_vendor_price            = $request->input('pr_vendor_price');
        $product_id                 = $request->input('product_id');
        $currency_id                = $request->input('currency_id');
        $sq_warehouse_id            = $request->input('sq_warehouse_id');
        $sq_approve_quotation       = $request->has('sq_approve_quotation') ? 1 : 0;
        $warehouse_id               = session('warehouse_id');
        $todays_date = date('Y-m-d');
        $result_array = array();
        
        $product_info = Products::find($product_id);

        $supplier_quotation     = new SupplierQuotations();
        $action ='add';
        if($sq_id != null)
        {
            $supplier_quotation = SupplierQuotations::find($sq_id);
            $action ='edit';
        }
         
        $supplier_quotation->fk_supplier_id          = $fk_supplier_id; 
        $supplier_quotation->sq_user_id              = $sq_user_id;
        $supplier_quotation->sq_date_submit          = $sq_date_submit;
        $supplier_quotation->sq_due_date             = $sq_due_date;
        $supplier_quotation->sq_total_price          = $sq_total_price;
        $supplier_quotation->sq_quotation_notes      = $sq_quotation_notes;
        $supplier_quotation->sq_currency_id          = $sq_currency_id;
        $supplier_quotation->sq_quotation_approve    = $sq_approve_quotation;
        $supplier_quotation->sq_warehouse_id         = $sq_warehouse_id;
        $supplier_quotation->save();
        
        $sq_id = $supplier_quotation->sq_id;
        
        $supplier_info = Suppliers::find($fk_supplier_id);
        
        // delete old supplier product
        //$supplier_products = SupplierProducts::whereFkQuotationId($sq_id)->delete();
        
        
        $quotation_total_price = 0;
        
        $product_type_count = count($pr_product_name);
        
        
        for ($i = 0; $i < $product_type_count; $i++) 
        {
            $sup_p_id  = isset($sp_id[$i]) ? $sp_id[$i] : 0;
            $p_id   = isset($product_id[$i]) ? $product_id[$i] : 0;
            
            if( $p_id == 0 )
                continue;
            $product_info       = Products::find($p_id);
            $product_currency   = $product_info->p_product_currency;
            if( $sup_p_id != 0 && $sup_p_id != null )
                $quotation_product = SupplierProducts::find($sup_p_id);
            else 
                $quotation_product = new SupplierProducts();
            
           
            $quotation_product->sp_product_serial           = $serial_numbers[$i] != null ? $serial_numbers[$i] : "";
            $quotation_product->fk_product_id               = $p_id;
            $quotation_product->fk_quotation_id             = $sq_id;
            $quotation_product->sp_product_name             = $pr_product_name[$i];
            $quotation_product->sp_product_description      = $pr_description[$i];
            $quotation_product->sp_product_pruchase_price   = $pr_pruchase_price[$i];
            $quotation_product->sp_product_selling_price    = $pr_selling_price[$i];
            $quotation_product->sp_product_wholesale_price  = $pr_wholesale_price[$i];
            $quotation_product->sp_product_vendor_price     = $pr_vendor_price[$i];
            $quotation_product->sp_product_discount         = $pr_discount[$i];
            $quotation_product->sp_main_currency            = $sq_currency_id;
            $quotation_product->sp_product_currency         = $product_currency;
            $quotation_product->sp_product_quantity         = $pr_quantity[$i];
            
            $exchange_rate = 1;
            
            if($product_currency != $sq_currency_id)
            {
                $exch_rate_obj      = CurrencyExchangeRates::whereErFromCurrency($product_currency)->whereErToCurrency($sq_currency_id)->orderBy('er_id', 'DESC')->get();
                if(count($exch_rate_obj) > 0)
                {
                    $exchange_rate  = $exch_rate_obj[0]->er_exchange_rate;
                }

            } 
            $quotation_total_price = $quotation_total_price + ( ( $pr_pruchase_price[$i] * $pr_quantity[$i] ) * $exchange_rate);
            
            $quotation_product->sp_product_currency         = $product_currency;
            
            $quotation_product->save(); 
            $is_id = $quotation_product->sp_stock_id;
            
            if($is_id != 0)
                $stock_info = Stocks::find($is_id);
            else 
                $stock_info = new Stocks();
            
            if($stock_info->fk_warehouse_id == null) 
                $stock_info = new Stocks();
                
            $stock_info->fk_warehouse_id                = $sq_warehouse_id;
            $stock_info->fk_product_id                  = $p_id;
            $stock_info->is_supplier_id                 = $fk_supplier_id;
            $stock_info->is_stock_label                 = $pr_description[$i];
            $stock_info->is_stock_lot_person_in_charge  = session('user_id');
            $stock_info->is_created_by                  = session('user_id');
            $stock_info->is_quanity                     = $pr_quantity[$i];
            $stock_info->is_creation_date               = $todays_date;
            $stock_info->is_price_stock                 = $pr_pruchase_price[$i];
            $stock_info->is_selling_price               = $pr_selling_price[$i];
            $stock_info->is_wholesale_price             = $pr_wholesale_price[$i];
            $stock_info->is_vendor_price                = $pr_vendor_price[$i];
            $stock_info->is_price_item                  = $pr_selling_price[$i];
            $stock_info->is_discount                    = $pr_discount[$i];
            $stock_info->is_price_currency              = $sq_currency_id;
            $stock_info->is_stock_currency              = $sq_currency_id;
            $stock_info->is_stock_exchange_rate         = $exchange_rate;
            $stock_info->save();
            $is_id = $stock_info->is_id;
            
            $quotation_product->sp_stock_id = $is_id;
            $quotation_product->save();
             
            
            $stockids_delete =  StockIds::whereSiStockId($is_id)->delete();
            
            $serial_number_array = array();
            if(strlen(trim($serial_numbers[$i])) > 0 || $serial_numbers[$i] != null)
                $serial_number_array = explode( ",", $serial_numbers[$i] );
            
            // get list of existing records related to this stock ids
            $lst_existing_sn = StockIds::whereIn('si_stock_uid',$serial_number_array);
            $existing_sn_array = array();
            
            foreach ( $lst_existing_sn as $key => $sn_info ) 
            {
                $existing_sn_array[ $sn_info->si_stock_uid ] =  $sn_info->si_stock_id;
            }
                
            for ($j = 0; $j < count($serial_number_array); $j++) 
            {
                if($serial_number_array[$j] == '' || $serial_number_array[$j] == null || strlen(trim($serial_number_array[$j])) == 0 || isset($existing_sn_array[ $serial_number_array[$j] ] ))
                    continue;
                $stockids_info                  = new StockIds();
                $stockids_info->si_stock_id     = $is_id;
                $stockids_info->si_stock_uid    = $serial_number_array[$j];
                $stockids_info->save();
            }
        }
        
         
        
        // save total quotation value 
        $supplier_quotation =  SupplierQuotations::find($sq_id);
        $supplier_quotation->sq_total_price = $quotation_total_price;
        $supplier_quotation->save();
        
        if($sq_approve_quotation == 1)
        {
            $at_id  = $supplier_quotation->sq_trans_id;
            $mov_id = $supplier_quotation->sq_mov_id;
            
            // stock accounting records
            if($at_id != 0 )
                $transaction = Transactions::find($at_id);
            else
                $transaction = new Transactions();
            
            $transaction->at_transaction_date   = $todays_date;
            $transaction->at_creation_date      = $todays_date;
            $transaction->at_accounting_doc     = "Transaction For Purchase a Stock from Supplier " . $supplier_info->ss_supplier_name;
            $transaction->fk_acc_journal_id     = 1;
            $transaction->at_currency_id        = $sq_currency_id;
            $transaction->save();
            
            $at_id = $transaction->at_id;
            
            // add movement debit from company to supplier account
            if($mov_id != 0)
                $trans_mov= TransactionMovements::find($mov_id);
            else
                $trans_mov= new TransactionMovements();
            $trans_mov->fk_tran_id              = $at_id;
            $trans_mov->tm_ledger_account       = $supplier_info->ss_sale_account_id;
            $trans_mov->tm_sub_ledger_account   = $supplier_info->ss_purchase_account_id;
            $trans_mov->tm_debit                = $quotation_total_price;
            $trans_mov->tm_credit               = 0;
            $trans_mov->tm_creation_date        = date('Y-m-d');
            $trans_mov->tm_currency_id          = $sq_currency_id;
            $trans_mov->tm_ledger_label         = "Debit For Purchase a Stock from Supplier " . $supplier_info->ss_supplier_name;
            $trans_mov->save();
            
            $mov_id = $trans_mov->tm_id;
        }
        
        $supplier_quotation->sq_trans_id = $at_id;
        $supplier_quotation->sq_mov_id   = $mov_id;
        $supplier_quotation->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Supplier Quotation Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    /**
     * Find A product information by barcode
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function FindProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        
        $product_info = Products::wherePBarcode($barcode)->get();
        $result_array = array();
        
        // check if product exist if not we return message that is not exist
        if(count($product_info) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "this Product doesn't exist in our Database Please Add it before you add it to stcok page";
            
            
            return Response()->json($result_array);
        }
        
        $product_info = $product_info[0];
         
        $result_array['is_error']            = 0;
        $result_array['product_id']          = $product_info->p_id;
        $result_array['product_name']        = $product_info->p_product_name;
        $result_array['selling_price']       = $product_info->p_product_selling_price;
        $result_array['min_selling_price']   = $product_info->p_product_min_selling_price;
        $result_array['product_currency']    = $product_info->p_product_currency;
        $result_array['use_serial_number']   = $product_info->Category->pc_use_serial_number;
       
       
       
       return Response()->json($result_array);
        
    }

}