<?php
/***********************************************************
ProductsController.php
Product :
Version : 1.0
Release : 2
Date Created :Oct 7, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\Http\Controllers\Inventory;

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
use PDF;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use Milon\Barcode\DNS1D;
use models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Users\Users;
use App\models\System\Currency;
use App\models\System\CurrencyExchangeRates;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\SRM\Suppliers;
use App\models\Inventory\StockIds;
use App\library\WarehouseManager;
use App\models\Inventory\StockMovementItems;



class ProductStocksController extends Controller
{

    public function index()
    {
        $lst_warehouse      = WareHouses::whereWIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies     = Currency::all();


        $data = array(
            "lst_warehouse" => $lst_warehouse,
            "lst_products"  => $lst_products,
            "lst_currencies"  => $lst_currencies,
        );
        return Response()->view("stocks.stockmanagement",$data);
    }


    /**
     * Open Create new Stock popup
     *
     * @author Moe mantach
     * @access public
     *
     */
    public function CreateNewStock( $p_id )
    {
        $product_info       = Products::find($p_id);
        $lst_warehouse      = WareHouses::whereWIsDeleted(0)->get();
        $lst_currencies     = Currency::all();
        $currency_array     = CreateDatabaseArrayByIndex($lst_currencies, 'cc_id');
        $company_currency   = session('company_currency');
        $secondary_currency = session('secondary_currency');
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        $rand_barcode                 = rand(10000000,99999999999);
        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($rand_barcode , "C39+",150 , 50 );

        $data = array(
            "lst_warehouse" => $lst_warehouse,
            "p_id" => $p_id,
            "product_info" => $product_info,
            "lst_currencies" => $lst_currencies,
            "currency_array" => $currency_array,
            "company_currency" => $company_currency,
            "secondary_currency" => $secondary_currency,
            "lst_suppliers" => $lst_suppliers,
            "bar_code_png" => $bar_code_png,
            "rand_barcode" => $rand_barcode
        );
        return Response()->view("stocks.addstock",$data);
    }


    /**
     * generate pdf page to display list of labels to print it
     *
     * @author Moe mantach
     * @param integer $ps_id
     */
    public function Displaybarodelabels($ps_id)
    {
        $lst_serial_numbers = StockIds::whereSiStockId($ps_id)->get();

        $serial_numbers_array = array();
        foreach ( $lst_serial_numbers as $key => $serial_number ) {
            $serial_numbers_array[ $serial_number->si_stock_uid ] = DNS1D::getBarcodePNG($serial_number->si_stock_uid, "C39+",150 , 50 );
        }


        $data = array(
            "serial_numbers_array" => $serial_numbers_array
        );

        return Response()->view('stocks.labels',$data);
    }


    /**
     * get list of stock and displayu it into the datatable of the stock page
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayList(Request $request)
    {
        $system_currencies              = Currency::all();
        $currency_array                 = CreateDatabaseArrayByIndex($system_currencies, "cc_id");

        $page_number                    = $request->input('page_number');
        $stock_warehouse                = $request->input('stock_warehouse');
        $stock_product                  = $request->input('stock_product');
        $stock_currency                 = $request->input('stock_currency');
        $nbr_rows_per_pages             = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
         else
            $skip = 0;


        $result_array =array();

        $lst_stocks     =Stocks::whereIsIsDeleted(0);
        if( $stock_warehouse > 0 )
            $lst_stocks= $lst_stocks->whereFkWarehouseId($stock_warehouse);
        if( $stock_product > 0 )
            $lst_stocks= $lst_stocks->whereFkProductId($stock_product);
        if( $stock_currency > 0 )
            $lst_stocks= $lst_stocks->whereIsStockCurrency($stock_currency);

            $count_stocks = $lst_stocks->count();
            $total_list_stocks = $lst_stocks->get();
        $lst_stocks = $lst_stocks->skip($skip)->take($nbr_rows_per_pages)->get();

        $total_pages = ceil( $count_stocks/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $stock_management = new WarehouseManager();
        $data_array = array(
            "lst_stocks" => $total_list_stocks
        );
        $total_stock_amount = $stock_management->getTotalStockAmount( $data_array );
        $view_data = array(
            "total_stock_amount" => $total_stock_amount
        );
        $total_amount_block = view('templates.displaytotalblock',$view_data )->render();

        $data = array(
            "currency_array" => $currency_array,
            "lst_stock" => $lst_stocks
        );
        $result_array['is_error'] = 0;
        $result_array['total_pages']      = $total_pages;
        $result_array['total_amount_block']      = $total_amount_block;
        $result_array['display']  = view("stocks.displayliststock",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * open add stock form to add new stock into a warehouse
     *
     * @author Moe Mantach
     * @access public
     */
    public function AddForm()
    {
        $lst_products       = Products::wherePProductIsDeleted(0)->get();

        $lst_warehouse      = WareHouses::whereWIsDeleted(0)->get();
        $lst_currencies     = Currency::all();

        $currency_array     = CreateDatabaseArrayByIndex($lst_currencies,'cc_id');
        $company_currency   = session('company_currency');
        $secondary_currency = session('secondary_currency');
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        $rand_barcode                 = rand(10000000,99999999999);
        $barcode_obj = new DNS1D();
        $bar_code_png = $barcode_obj->getBarcodePNG($rand_barcode , "C39+",150 , 50 );


        $data = array(
            'lst_products'      => $lst_products,
            'lst_currencies'    => $lst_currencies,
            'company_currency'  => $company_currency,
            'secondary_currency'  => $secondary_currency,
            'lst_suppliers'  => $lst_suppliers,
            'rand_barcode'  => $rand_barcode,
            'bar_code_png'  => $bar_code_png,
            'currency_array'    => $currency_array,
            'lst_warehouse'     => $lst_warehouse
        );

        return Response()->view('stocks.addform',$data);
    }


    /**
     * Display Edit form for existing Stock record
     *
     * @author Moe Mantach
     * @param Integer $is_id
     *
     * @return View Edit View
     */
    public function EditForm( $is_id )
    {
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_warehouse      = WareHouses::whereWIsDeleted(0)->get();
        $InventoryStock     = Stocks::find($is_id);
        $lst_currencies     = Currency::all();
        $currency_array     = CreateDatabaseArrayByIndex($lst_currencies,'cc_id');
        $company_currency   = session('company_currency');
        $secondary_currency = session('secondary_currency');
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        $lst_serial_numbers = StockIds::whereFkStockId($is_id)->get();
        $serial_numbers     = array();
        foreach ( $lst_serial_numbers as $key => $sn_info )
        {
            $serial_numbers[] = $sn_info->si_stock_uid;
        }

        $product_info = Products::find($InventoryStock->fk_product_id);
        $data = array(
            'serial_numbers' => $serial_numbers,
            'lst_products' => $lst_products,
            'lst_warehouse' => $lst_warehouse,
            'lst_currencies' => $lst_currencies,
            'InventoryStock' => $InventoryStock,
            'product_info' => $product_info,
            'currency_array' => $currency_array,
            'lst_suppliers' => $lst_suppliers,
            'secondary_currency' => $secondary_currency,
            'company_currency' => $company_currency
        );

        return Response()->view('stocks.editform',$data);
    }



    /**
     * Add new Stock to warehouse and selected product
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array $result_array
     */
    public function AddStock(Request $request)
    {
        $p_id               = $request->input("p_id");
        $fk_warehouse_id    = $request->input("fk_warehouse_id");
        $is_quanity         = $request->input("is_quanity");
        $fk_zone_id         = $request->input("fk_zone_id");
        $company_currency   = $request->input("company_currency");
        $is_price_stock     = $request->input("is_price_stock");
        $is_selling_price   = $request->input("is_selling_price");
        $is_discount        = $request->input("is_discount");
        $is_wholesale_price = $request->input("is_wholesale_price");
        $is_vendor_price    = $request->input("is_vendor_price");
        $is_stock_currency  = $request->input("is_stock_currency");
        $is_stock_uid       = $request->input("is_stock_uid");
        $is_supplier_id     = $request->input("is_supplier_id");
        $serial_ids         = $request->input("serial_ids");
        $creation_date      = date("Y-m-d H:i:s");

        $ProductInfo            = Products::find($p_id);
        $stock_label            = "Add Stock to " . $ProductInfo->p_product_name . " On " . $creation_date;
        $stock_account_label    = " Ledger of adding Stock to " . $ProductInfo->p_product_name . " On " . $creation_date;
        $price_stock            = $is_price_stock * $is_quanity;

        $warehouse_info = WareHouses::find($fk_warehouse_id);

        $supplier_info = Suppliers::find($is_supplier_id);


        $warehouse_type = $warehouse_info->w_warehouse_size_type;
        $product_type   = $ProductInfo->p_product_unit_type;

        $stock = new Stocks();
        $stock->fk_product_id                   =  $p_id;
        $stock->fk_warehouse_id                 =  $fk_warehouse_id ;
        $stock->is_supplier_id                  =  $is_supplier_id;
        $stock->fk_zone_id                      =  $fk_zone_id;
        $stock->is_stock_label                  =  $stock_label;
        $stock->is_created_by                   =  session("user_id");
        $stock->is_quanity                      =  $is_quanity;
        $stock->is_creation_date                =  $creation_date;
        $stock->is_price_item                   =  $is_price_stock;
        $stock->is_selling_price                =  $is_selling_price;
        $stock->is_discount                     =  $is_discount;
        $stock->is_wholesale_price              =  $is_wholesale_price;
        $stock->is_vendor_price                 =  $is_vendor_price;
        $stock->is_price_stock                  =  $price_stock;
        $stock->is_stock_currency               =  $is_stock_currency;
        $stock->is_stock_uid                    =  $is_stock_uid;
        $stock->is_price_currency               =  session('company_currency');
        $stock->is_stock_lot_person_in_charge   =  session('user_id');

        // Save Accounting Records in the database to show it in inventory accounting chart
        $delete = Transactions::whereAtId($stock->is_trans_id)->delete();
        $transactions = new Transactions();
        $transactions->at_transaction_date  = $creation_date;
        $transactions->at_creation_date     = $creation_date;
        $transactions->at_accounting_doc    = $stock_label;
        $transactions->fk_acc_journal_id    = 3;
        $transactions->at_currency_id       = $is_stock_currency;
        $transactions->save();
        $at_id = $transactions->at_id;


        $payable_account    = 0;
        $receivable_account = 0;

        $delete_mov = TransactionMovements::whereFkTranId($stock->is_trans_id)->delete();
        $payable_account        = $supplier_info->ss_sale_account_id;
        $receivable_account     = $supplier_info->ss_purchase_account_id;

        // insert transaction movement
        $transaction_movements = new TransactionMovements();
        $transaction_movements->fk_tran_id              = $at_id;
        $transaction_movements->tm_ledger_account       = $payable_account;
        $transaction_movements->tm_sub_ledger_account   = $receivable_account;
        $transaction_movements->tm_ledger_label         = $stock_account_label;
        $transaction_movements->tm_debit                = 0;
        $transaction_movements->tm_credit               = $price_stock;
        $transaction_movements->tm_creation_date        = $creation_date;
        $transaction_movements->tm_currency_id          = $is_stock_currency;
        $transaction_movements->tm_ledger_label         = "Credit Inside supplier for Stock from Supplier " . $supplier_info->ss_supplier_name;
        $transaction_movements->save();


        $trans_mov= new TransactionMovements();
        $trans_mov->fk_tran_id              = $at_id;
        $trans_mov->tm_ledger_account       = 601;
        $trans_mov->tm_sub_ledger_account   = 601;
        $trans_mov->tm_debit                = $is_selling_price;
        $trans_mov->tm_credit               = 0;
        $trans_mov->tm_creation_date        = date('Y-m-d');
        $trans_mov->tm_transaction_date        = date('Y-m-d');
        $trans_mov->tm_currency_id          = $is_stock_currency;
        $trans_mov->tm_ledger_label         = "Debit Inside Purchasing for Stock from Supplier " . $supplier_info->ss_supplier_name;
        $trans_mov->save();


        $tm_id = $transaction_movements->tm_id;


        $stock->is_trans_id = $at_id;
        $stock->is_mov_id   = $tm_id;
        $stock->save();
        $stock_id = $stock->is_id;
        // Add Serial Ids in the table of ids
        $serial_number_array = explode(",",$serial_ids);

        foreach ($serial_ids as $key => $serial_number)
        {
            $count_serial_number_rows = StockIds::whereSiStockId($stock_id)->whereSiStockUid($serial_number)->count();
            if($count_serial_number_rows > 0)
                continue;

            $serial_number_obj = new StockIds();
            $serial_number_obj->si_stock_id = $stock_id;
            $serial_number_obj->si_stock_uid= $serial_number;
            $serial_number_obj->save();
        }

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Page to Add List of Searial Numbers to save it in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Addserialnumbers(Request $request)
    {
        $is_id = $request->input('is_id');
        $serial_numbers= $request->input('serial_ids');
        if($serial_numbers != null)
        {
            $serial_numbers_array = explode(',',$serial_numbers);
        }
        else
        {
            $serial_numbers_array = array();
        }
        $data = array(
            "is_id" => $is_id,
            "serial_numbers_array" => $serial_numbers_array,
        );
        return Response()->view('stocks.manageserialnumbers',$data);
    }


    public function SaveProductStockInfo(Request $request)
    {
        $p_id                   = $request->input("p_id");
        $is_id                  = $request->input("is_id");
        $fk_warehouse_id        = $request->input("fk_warehouse_id");
        $is_quanity             = $request->input("is_quanity");
        $fk_zone_id             = $request->input("fk_zone_id");
        $company_currency       = $request->input("company_currency");
        $is_price_stock         = $request->input("is_price_stock");
        $is_stock_exchange_rate = $request->input("is_stock_exchange_rate");
        $is_selling_price       = $request->input("is_selling_price");
        $is_discount            = $request->input("is_discount");
        $is_wholesale_price     = $request->input("is_wholesale_price");
        $is_vendor_price        = $request->input("is_vendor_price");
        $is_stock_uid           = $request->input("is_stock_uid");
        $serial_ids             = $request->input("serial_ids");
        $is_supplier_id         = $request->input("is_supplier_id");
        $is_stock_currency      = $request->input("is_stock_currency");
        $creation_date          = date("Y-m-d");
        $result_array           = array();

        {
            $serial_ids_array       = explode(',', $serial_ids);
            $product_info           = Products::find($p_id);
            $stock                  = new Stocks();
            $stock_label            = "Add Stock to " . $product_info->p_product_name . " On " . $creation_date;
            $stock_account_label    = " Ledger of adding Stock to " . $product_info->p_product_name . " On " . $creation_date;
        }





        if($is_id == 0)
        {
            $stock->is_stock_label      = "Add Stock to " . $product_info->p_product_name . " On " . date("Y-m-d H:i:s");
            $stock->is_created_by       = session("user_id");
            $stock->is_creation_date    =  date("Y-m-d H:i:s");
        }
        elseif( $is_id > 0 )
        {
            $stock = Stocks::find($is_id);
            if($stock->is_price_currency  == 0)
            {
                $stock->is_price_currency = $is_stock_currency;
            }
        }



       /** if($is_id > 0)
        {
            $stock = Stocks::find($is_id);
            if($stock->is_price_currency  == 0)
            {
                $stock->is_price_currency = $is_stock_currency;
            }
        }
        else
        {
            $ExistingStock = Stocks::whereFkProductId($p_id)->whereFkWarehouseId($fk_warehouse_id)->get();
            if(count($ExistingStock) > 0)
            {
                $exs_stock_id = $ExistingStock[count($ExistingStock) - 1]['is_id'];
                $stock = Stocks::find($exs_stock_id);
                $is_quanity = $is_quanity + $stock->is_quanity;
            }
            else
            {
                $stock->is_price_currency   = $is_stock_currency;
                $stock->is_stock_label      = "Add Stock to " . $product_info->p_product_name . " On " . date("Y-m-d H:i:s");
                $stock->is_created_by       = session("user_id");
                $stock->is_creation_date    =  date("Y-m-d H:i:s");
            }
        }*/

        if($is_id == 0)
            $price_stock = $is_price_stock * $is_quanity;
        else
            $price_stock = $is_price_stock;

        $stock->is_stock_currency               =  $is_stock_currency;
        $stock->is_price_currency               =  $is_stock_currency;
        $stock->fk_product_id                   = $p_id;
        $stock->fk_warehouse_id                 = $fk_warehouse_id;
        $stock->fk_zone_id                      = $fk_zone_id;
        $stock->is_quanity                      = $is_quanity;
        $stock->is_price_item                   =  $is_price_stock;
        $stock->is_price_stock                  =  $price_stock;
        $stock->is_stock_exchange_rate          =  $is_stock_exchange_rate;
        $stock->is_selling_price                =  $is_selling_price;
        $stock->is_discount                     =  $is_discount;
        $stock->is_wholesale_price              =  $is_wholesale_price;
        $stock->is_vendor_price                 =  $is_vendor_price;
        $stock->is_stock_uid                    =  $is_stock_uid;
        $stock->is_supplier_id                  =  $is_supplier_id;

        $stock->save();

        if($is_id == null)
            $is_id = $stock->is_id;

        // add all serial numbers to the stock_id record
        foreach ($serial_ids_array as $key => $serial_id)
        {

            $count_serial_number_rows = StockIds::whereFkStockId($is_id)->whereSiStockUid($serial_id)->count();
            if($count_serial_number_rows > 0)
                continue;
            if($serial_id != "")
            {
                $stock_ids = new StockIds();
                $stock_ids->fk_product_id     = $p_id;
                $stock_ids->fk_stock_id     = $is_id;
                $stock_ids->si_stock_uid    = $serial_id;
                $stock_ids->save();
            }

        }

        $at_id  = $stock->is_trans_id;
        $mov_id = $stock->is_mov_id;

        // check stock transaction and movememnt if exist we edit on them else we create a transaction
        // and movement for the stock
        $transaction_obj    = new Transactions();
        $movememnt_obj      = new TransactionMovements();

        if( $mov_id > 0 )
        {
            $transaction_obj    = Transactions::find($at_id);
            $movememnt_obj      = TransactionMovements::find($mov_id);
        }


        // create transaction if not exist
        if($at_id == 0)
        {
            $transaction_obj= new Transactions();
            $transaction_obj->at_transaction_date  = $creation_date;
            $transaction_obj->at_creation_date     = $creation_date;
            $transaction_obj->at_accounting_doc    = $stock_label;
            $transaction_obj->fk_acc_journal_id    = 3;
            $transaction_obj->at_currency_id       = $is_stock_currency;
            $transaction_obj->save();
            $at_id = $transaction_obj->at_id;
        }


        if( $mov_id > 0 )
        {
            $movememnt_obj->tm_debit                = $price_stock;
            $movememnt_obj->tm_credit               = 0;
            $movememnt_obj->tm_currency_id          = $is_stock_currency;
            $movememnt_obj->save();
        }
        else
        {

            $payable_account    = 0;
            $receivable_account = 0;


            $movememnt_obj->fk_tran_id              = $at_id;
            $movememnt_obj->tm_ledger_account       = $product_info->p_sale_accounting_code;
            $movememnt_obj->tm_sub_ledger_account   = $product_info->p_purchase_accounting_code;
            $movememnt_obj->tm_ledger_label         = $stock_account_label;
            $movememnt_obj->tm_debit                = $price_stock;
            $movememnt_obj->tm_credit               = 0;
            $movememnt_obj->tm_creation_date        = $creation_date;
            $movememnt_obj->tm_currency_id          = $is_stock_currency;
            $movememnt_obj->save();

            $mov_id = $movememnt_obj->tm_id;
        }

        $stock_info  = Stocks::find($is_id);
        $stock_info->is_trans_id    = $at_id;
        $stock_info->is_mov_id      = $mov_id;
        $stock_info->save();

        unset($stock_info);
        $stock_info = null;
        unset($movememnt_obj);
        $movememnt_obj= null;
        unset($transaction_obj);
        $transaction_obj = null;
        unset($stock);
        $stock = null;

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Delete Stock info saved into the database
     *
     * @author Moe mantach
     * @param Request $request
     */
    public function DeleteStockInfo(Request $request)
    {
        $is_id              = $request->input("is_id");

        $StockInfo = Stocks::find($is_id);
        $StockInfo->is_is_deleted   = 1;
        $StockInfo->is_deleted_by   = session('user_id');
        $StockInfo->save();


        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Display page of transfer stock from product page
     *
     * @author Moe Mantach
     * @access public
     * @param Integer $p_id
     */
    public function Displaytransferstocks($p_id)
    {
        $ProductInfo = Products::find($p_id);

        $lst_warehouse  = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            "lst_warehouse" => $lst_warehouse,
            "p_id" => $p_id,
            "ProductInfo" => $ProductInfo
        );
        return Response()->view("stocks.product-transferstock",$data);
    }






    /**
     * Save stock transfer reduce quantity from one warehouse and take it from another
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return json $result_array
     */
    public function StockTransfer(Request $request)
    {
        $p_id                   = $request->input("p_id");
        $warehouse_source       = $request->input("warehouse_source");
        $warehouse_destination  = $request->input("warehouse_destination");
        $list_transfer_items          = $request->input("list_transfer_items");
        $sm_movement_label          = $request->input("sm_movement_label");
        $result_array           = array();
        $transfer_items = json_decode($list_transfer_items);

        $product_obj = new ProductManager();

        $transfer_stock = new StockMovements();
        $transfer_stock->sm_transfer_code = $product_obj->GenerateStockTransferCode();
        $transfer_stock->fk_warehouse_from = $warehouse_source;
        $transfer_stock->fk_warehouse_to   = $warehouse_destination;
        $transfer_stock->sm_date_movement  = date("Y-m-d H:i:s");
        $transfer_stock->sm_movement_label = $sm_movement_label;
        $transfer_stock->sm_created_by     = session("user_id");
        $transfer_stock->save();
        $sm_id = $transfer_stock->sm_id;

        $total_quantity = 0;
        $total_price = 0;

        foreach ($transfer_items as $index => $item_info)
        {
            $product_info = Products::find($item_info->mp_product_id);

            $StockProductWarehouse = Stocks::whereFkProductId($item_info->mp_product_id)->whereFkWarehouseId($warehouse_source)->get();

            $transfer_stock_items = new StockMovementItems();
            $transfer_stock_items->mp_movement_id = $sm_id;
            $transfer_stock_items->mp_product_id = $item_info->mp_product_id;
            $transfer_stock_items->mp_label = "Move Stock " . $item_info->mp_product_name;
            $transfer_stock_items->mp_item_notes = $item_info->mp_item_notes;
            $transfer_stock_items->mp_movement_quantity = $item_info->mp_movement_quantity;
            $transfer_stock_items->mp_movement_cost = $product_info->p_product_cost_price * $item_info->mp_movement_quantity;
            $transfer_stock_items->mp_currency_id = $product_info->p_product_currency;
            $transfer_stock_items->save();

            $total_quantity = $total_quantity + $item_info->mp_movement_quantity;
            $total_price = $total_price + $product_info->p_product_cost_price * $item_info->mp_movement_quantity;

            // add a minus stock in source warehouse
            $source_stock = new Stocks();
            $source_stock->fk_product_id = $item_info->mp_product_id;
            $source_stock->fk_warehouse_id = $warehouse_source;
            $source_stock->is_stock_label = "Minus stock for Product" . $product_info->p_product_name;
            $source_stock->is_stock_lot_person_in_charge = session('user_id');
            $source_stock->is_created_by = session('user_id');
            $source_stock->is_quanity = -1 * $item_info->mp_movement_quantity;
            $source_stock->is_creation_date = date("Y-m-d H:i:s");
            $source_stock->is_price_currency = $product_info->p_product_currency;
            $source_stock->is_stock_currency = $product_info->p_product_currency;
            $source_stock->is_price_item = $product_info->p_product_cost_price;
            $source_stock->is_price_stock = $product_info->p_product_cost_price * $item_info->mp_movement_quantity;
            $source_stock->save();

            // add stock in destination warehouse
            $destination_stock = new Stocks();
            $destination_stock->fk_product_id = $item_info->mp_product_id;
            $destination_stock->fk_warehouse_id = $warehouse_destination;
            $destination_stock->is_stock_label = "Add stock for Product" . $product_info->p_product_name;
            $destination_stock->is_stock_lot_person_in_charge = session('user_id');
            $destination_stock->is_created_by = session('user_id');
            $destination_stock->is_quanity = $item_info->mp_movement_quantity;
            $destination_stock->is_creation_date = date("Y-m-d H:i:s");
            $destination_stock->is_price_currency = $product_info->p_product_currency;
            $destination_stock->is_stock_currency = $product_info->p_product_currency;
            $destination_stock->is_price_item = $product_info->p_product_cost_price;
            $destination_stock->is_price_stock = $product_info->p_product_cost_price * $item_info->mp_movement_quantity;
            $destination_stock->save();
        }

        // update quantity and price
        $transfer_stock = StockMovements::find($sm_id);
        $transfer_stock->sm_stock_quantity = $total_quantity;
        $transfer_stock->sm_stock_total_price = $total_price;
        $transfer_stock->save();


        $result_array['is_error']  = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Generate Transfer Stock Voucher and download pdf file
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateTransferVoucher(Request $request)
    {
        $sm_id = $request->input('sm_id');
        $stock_transfer = StockMovements::find($sm_id);
        $company_logo   = session('company_logo');

        $lst_transfer_items = StockMovementItems::whereMpMovementId($sm_id)->whereMpIsDeleted(0)->get();

        $data = array(
            "lst_transfer_items" => $lst_transfer_items
        );
        $lst_items = view('templates.liststocktransfer',$data)->render();


        $display = view('templates.stock-transfer',array())->render();

       $display = str_replace("%COMPANY_LOGO%", $company_logo, $display);
       $display = str_replace("%STOCK_TRANSFER_LABEL%", $stock_transfer->sm_movement_label, $display);
       $display = str_replace("%STOCK_TRANSFER_CODE%", $stock_transfer->sm_transfer_code, $display);
       $display = str_replace("%STOCK_TRANSFER_DATE%", $stock_transfer->sm_date_movement, $display);
       $display = str_replace("%STOCK_TRANSFER_DESCRIPTION%", $stock_transfer->sm_transfer_description, $display);
       $display = str_replace("%TOTAL_TRANSFER_QUANTITY%", $stock_transfer->sm_stock_quantity, $display);
       $display = str_replace("%TRANSFER_LST_PRODUCTS%", $lst_items, $display);
       $display = str_replace("%STOCK_SOURCE_WAREHOUSE%", $stock_transfer->SourceWarehouse->w_warehouse_name  . " ( ". $stock_transfer->SourceWarehouse->w_warehouse_ref . " )", $display);
       $display = str_replace("%STOCK_DESTINATION_WAREHOUSE%", $stock_transfer->DestinationWarehouse->w_warehouse_name  . " ( ". $stock_transfer->DestinationWarehouse->w_warehouse_ref . " )", $display);

         return PDF::loadHTML($display)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->download('stock-transfer-' . strtolower($stock_transfer->sm_transfer_code) . '.pdf');
    }



    /**
     * get product information and return it as json file to
     * show it whenever we need in stock section
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetProductinfo(Request $request)
    {
        $p_id = $request->input('p_id');

        $product_info = Products::find($p_id);
        $result_array = array();

        $result_array['category_id']                = $product_info->fk_pc_id;
        $result_array['barcode']                    = $product_info->p_barcode;

         $barcode_obj = new DNS1D();
          $bar_code_png = $barcode_obj->getBarcodePNG($product_info->p_barcode , "C39+",150 , 50 );

        $result_array['barcode_img']                = "data:image/png;base64," . $bar_code_png;
        $result_array['p_id']                = $p_id;
        $result_array['image_base_src']             = $product_info->p_product_profile_base_src;
        $result_array['image_file_name']            = $product_info->p_product_profile_file_name;
        $result_array['image_extention']            = $product_info->p_product_profile_extention;
        $result_array['p_product_ref']              = $product_info->p_product_ref;
        $result_array['p_product_description']              = $product_info->p_product_description;
        $result_array['p_product_name']             = $product_info->p_product_name;
        $result_array['p_product_type']             = $product_info->p_product_type;
        $result_array['p_product_color']            = $product_info->p_product_color;
        $result_array['p_product_currency']         = $product_info->p_product_currency;
        $result_array['p_product_selling_price']    = $product_info->p_product_selling_price;
        $result_array['stock_has_serial_number']    = $product_info->Category->pc_use_serial_number;

        $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        if(strlen($product_info->p_product_profile_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }
        $result_array['product_profile']        = $img_src;

       return Response()->json($result_array);
    }
}
