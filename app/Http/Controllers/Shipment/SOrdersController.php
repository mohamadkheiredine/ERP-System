<?php
/***********************************************************
SOrdersController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/




namespace App\Http\Controllers\Shipment;

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
use App\models\Shipment\OrderStatus;
use App\models\Shipment\shippingOrders;
use App\models\Users\Users;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use App\library\OrdersManager;
use App\models\Shipment\OrderCategories;
use App\models\Inventory\Products;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\CurrencyExchangeRates;
use App\models\Inventory\Stocks;
use App\models\Billing\Invoices;
use App\library\AccountingManager;
use App\models\Billing\InvoiceProducts;
use App\models\Billing\PaymentTypes;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Inventory\Vendors;
use Milon\Barcode\DNS1D;
use App\models\Inventory\StockIds;
use App\models\SRM\Suppliers;
use App\models\Inventory\ProductCategories;
use App\models\Shipment\PackingPrices;



class SOrdersController extends Controller
{

    /**
     * Page to control Order Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_customers  = Customers::whereIcIsDeleted(0)->get();
        $lst_vendors    = Vendors::whereIvIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->whereWWarehouseStatus(1)->get();
        
        $data = array(
            'lst_warehouses' => $lst_warehouses,
            'lst_customers' => $lst_customers,
            'lst_vendors' => $lst_vendors
        );
        return Response()->view('shipment.orders.orders',$data);
    }
    
    
    /**
     * Display list of Orders saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        
        $so_order_warehouse     = $request->input('so_order_warehouse');
        $so_vendor_id           = $request->input('so_vendor_id');
        $so_order_customer      = $request->input('so_order_customer');
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');
        
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
        
        
        $list_orders = shippingOrders::whereSoIsDeleted(0); 
        if($so_order_warehouse > 0)
            $list_orders = $list_orders->whereFkWarehouseId($so_order_warehouse);
        if($so_vendor_id > 0)
            $list_orders = $list_orders->whereSoVendorId($so_vendor_id);
        if($so_order_customer > 0)
            $list_orders = $list_orders->whereSoOrderCustomer($so_order_customer);
         if( strlen($general_search)  > 0)
         {
             $list_orders = $list_orders->where('so_order_label','LIKE','%' . $general_search . '%');
             $list_orders = $list_orders->orwhere('so_order_code','LIKE','%' . $general_search . '%');
         }
             
        
         $order_count = $list_orders->count();
       
        
         $total_pages = ceil( $order_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);
             
         $list_orders = $list_orders->skip($skip)->take($nbr_rows_per_pages)->get();
        $data = array(
            "list_orders" => $list_orders, 
        );
        
        $result_array = array(); 
        $result_array['display'] = view("shipment.orders.displaylist",$data)->render();
        $result_array['total_pages'] = $total_pages;
        
        return Response()->json($result_array);
    }
    
     
    
    /**
     * Display list of products for selected order
     * 
     * @author Moe Masntach
     * @access public 
     * @param Request $request
     */
    public function DisplayListCategories(Request $request)
    {
        $so_id = $request->input('order_id');
        
        $lst_order_categories = OrderCategories::whereFkOrderId($so_id)->get();
   
        
        
        $data = array(
            "lst_order_categories" => $lst_order_categories
        );
        
        $result_array = array();
        $result_array['display'] = view("shipment.orders.displaylistcategories",$data)->render();
        
        return Response()->json($result_array);
    }
    
      public function GetPackingPrice(Request $request)
    {
        $so_id              = $request->input('so_id');
        $category_id        = $request->input('category_id');
        $so_package_weight  = $request->input('so_package_weight');
        
        $result_array = array();
        
        $packing_cost_obj  = PackingPrices::where('fk_category_id',$category_id)->where('cp_weight_from','<=',$so_package_weight)->where('cp_weight_to','>',$so_package_weight)->get();
        
        if(count($packing_cost_obj) == 0)
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'No Price for this Weight';
            return Response()->json($result_array);
        }
        
        $result_array['is_error']  = 0;
        $result_array['package_cost']  = $packing_cost_obj[0]->cp_price_range;
        $result_array['error_msg'] = 'Order Item Has been saved';
        
        return Response()->json($result_array);
    }
    
    /**
     * Function of Adding a new Order
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $rand_barcode       = rand(10000000,99999999999);
        $bar_code_png       = DNS1D::getBarcodePNG($rand_barcode , "C39+",150 , 50 );
        
        $lst_order_status   = OrderStatus::whereSsIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_vat_tax        = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_currency       = Currency::all();
        $lst_customers      = Customers::whereIcIsDeleted(0)->get();
        $lst_vendors        = Vendors::whereIvIsDeleted(0)->get();
        $lst_warehouses     = WareHouses::whereWIsDeleted(0)->get();
        
        $lst_suppliers  = Suppliers::whereSsIsDeleted(0)->get();
        
        $OrderManager   = new OrdersManager();
        $order_code     = $OrderManager->GenerateOrdereCode();
        unset($OrderManager);
        
        
        $data = array(
            "lst_order_status" => $lst_order_status,
            "bar_code_png" => $bar_code_png, 
            "rand_barcode" => $rand_barcode, 
            "order_code" => $order_code, 
            "lst_users" => $lst_users,
            "lst_vendors" => $lst_vendors,
            "lst_warehouses" => $lst_warehouses,
            "lst_customers" => $lst_customers,
            "lst_currency" => $lst_currency,
            "lst_suppliers" => $lst_suppliers,
            "lst_vat_tax" => $lst_vat_tax
        );
        return view('shipment.orders.addform',$data);
    }
    
    
    /**
     * Save Order Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveOrderInfo(Request $request)
    {
        $so_id                      = $request->input('so_id');
        $so_order_code              = $request->input('so_order_code'); 
        $so_assign_to               = $request->input('so_assign_to'); 
        $so_supplier_id               = $request->input('so_supplier_id'); 
        $so_order_status            = $request->input('so_order_status');
        $so_payment_type            = $request->input('so_payment_type'); 
        $so_order_label             = $request->input('so_order_label'); 
        $so_order_note              = $request->input('so_order_note'); 
        $so_order_date              = $request->input('so_order_date');
        $so_order_date              = date("Y-m-d",strtotime($so_order_date));
        $so_delivery_date           = $request->input('so_delivery_date'); 
        $so_delivery_date           = date("Y-m-d",strtotime($so_delivery_date));
        $so_vat_id                  = $request->input('so_vat_id'); 
        $so_total_cost              = $request->input('so_total_cost'); 
        $so_order_currency          = $request->input('so_order_currency'); 
        $fk_warehouse_id            = $request->input('fk_warehouse_id');
        $so_customer_payment        = $request->input('so_customer_payment');
        $so_order_customer          = $request->input('so_order_customer') != null ? $request->input('so_order_customer') : 0; 
        $so_vendor_id               = $request->input('so_vendor_id') != null ? $request->input('so_vendor_id') : 0; 
         
        $result_array = array();
 
        
        $Orders = new shippingOrders();
         $total_packing_value = 0;
        if( $so_id != null )
        {
            $Orders = shippingOrders::find($so_id);
            
            
            $lst_order_categories = OrderCategories::whereFkOrderId($so_id)->get();
            foreach ( $lst_order_categories as $key => $cat_info ) {
                $total_packing_value = $total_packing_value + $cat_info->so_package_price;
            }
        }
        else {
            $so_creation_date           = date("Y-m-d");
            $fk_user_id                 = session('user_id');
            $Orders->fk_user_id          = $fk_user_id;
            $Orders->so_creation_date    = $so_creation_date;
        }
        
        
        
        
        
        $total_price = $so_customer_payment + $total_packing_value;
         
        $Orders->so_order_code       = $so_order_code;
        $Orders->so_assign_to        = $so_assign_to;
        $Orders->fk_status_id     = $so_order_status;
        $Orders->so_customer_id   = $so_order_customer;
        $Orders->so_payment_type     = $so_payment_type;
        $Orders->so_order_label      = $so_order_label;
        $Orders->so_order_note       = $so_order_note;
        $Orders->so_creation_date       = $so_order_date;
        $Orders->so_delivery_date    = $so_delivery_date;
        $Orders->so_vat_id           = $so_vat_id;
        $Orders->so_currency_id   = $so_order_currency;
        $Orders->so_supplier_id   = $so_supplier_id;
        $Orders->so_assign_to   = $so_assign_to;
        $Orders->so_customer_payment   = $so_customer_payment;
        $Orders->so_total_price   = $total_price;
        
        $Orders->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Order Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Order Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $so_id )
    {
        $lst_order_status   = OrderStatus::whereSsIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_vat_tax        = VatAccounts::whereAvIsDeleted(0)->get();
        $order_info         = shippingOrders::find($so_id);
        $lst_vendors        = Vendors::whereIvIsDeleted(0)->get();
        $lst_currency       = Currency::all();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_customers      = Customers::whereIcIsDeleted(0)->get();
        $lst_warehouses     = WareHouses::whereWIsDeleted(0)->get();
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        $lst_categories     = ProductCategories::wherePcIsDeleted(0)->get();
        
        $order_code = "";
        if($order_info->so_order_code != null)
        {
            $OrderManager = new OrdersManager();
            $order_code = $OrderManager->GenerateOrdereCode();
            unset($OrderManager);
        }

        
        $data = array(
            "order_info" => $order_info,
            "lst_order_status" => $lst_order_status,
            "lst_users" => $lst_users,
            "lst_products" => $lst_products,
            "lst_customers" => $lst_customers,
            "lst_warehouses" => $lst_warehouses,
            "order_code" => $order_code,
            "lst_vat_tax" => $lst_vat_tax,
            "lst_vendors" => $lst_vendors,
            "lst_suppliers" => $lst_suppliers,
            "lst_categories" => $lst_categories,
            "lst_currency" => $lst_currency
        );
        return view('shipment.orders.editform',$data);
    }
    
    
    
    /**
     * save packing category
     * @param Request $request
     */
    public function SavePackingCategory(Request $request)
    {
        $order_id               = $request->input('order_id');
        $so_product_category    = $request->input('so_product_category');
        $so_package_weight      = $request->input('so_package_weight');
        $so_package_cost        = $request->input('so_package_cost');
        $currency_id            = $request->input('currency_id');
        
        $order_item = new OrderCategories();
        $order_item->fk_order_id = $order_id;
        $order_item->fk_category_id = $so_product_category;
        $order_item->so_package_weight = $so_package_weight;
        $order_item->so_package_cost = $so_package_cost;
        $order_item->so_package_price = $so_package_cost;
        $order_item->so_package_currency = $currency_id;
        $order_item->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Order Item Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    /**
     * add product from stock to selected order based on validation of certain criteria
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function AddOrderCategory( Request $request )
    {   
        $order_id               = $request->input('order_id');
        $order_product_id       = $request->input('order_product');
        $so_product_cost        = $request->input('so_product_cost');
        $stock_id               = $request->input('stock_id');
        $so_product_serial      = $request->input('so_product_serial');
        $so_product_quantity    = $request->input('so_product_quantity');
        $warehouse_id           = $request->input('warehouse_id');
        $product_info           = Products::find($order_product_id);
        $result_array           = array();
        
        $order_info = Orders::find($order_id); 
        // Get Stock information for the product
        $product_stock = Stocks::whereFkProductId($order_product_id)->whereFkWarehouseId($warehouse_id)->get();
        
        if(count($product_stock) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "We dont have any stock for this Product in this warehouse";
            return Response()->json($result_array);
        }
        
        $stock_serial_info = StockIds::whereSiStockUid($so_product_serial)->get();
       
        // check if we have the queanty ordered 
        if($product_stock[0]['is_quanity'] < $so_product_quantity)
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "We don't have quantity in the stock for this product ";
            return Response()->json($result_array);
        }
        
        
      /**  $product_cost       = $so_product_cost;**/
        $product_currency_id   = $product_info->p_product_currency;
        $so_order_currency_id  = $order_info->so_order_currency;

        $today_date         = date("Y-m-d");
        $currency_exchange  = CurrencyExchangeRates::whereErFromCurrency($product_currency_id)->whereErToCurrency($so_order_currency_id)->where('er_date_exchange','=',$today_date)->get();
        $op_product_cost    = $so_product_cost;
        $exchange_rate      = 0;

        /**if(count($currency_exchange) == 0)
        {
            $order_currency = Currency::find($so_order_currency_id);
            $product_currency = Currency::find($product_currency_id);
            $op_product_cost= convertCurrency($product_cost, $product_currency->cc_currency_code, $order_currency->cc_currency_code);
        }
        else 
        {
            $exchange_rate = $currency_exchange[0]['er_exchange_rate'];
            $op_product_cost = $op_product_cost * $exchange_rate;
        }*/
        
        
        $order_products = new OrderCategories();
        $order_products->fk_order_id            = $order_id;
        $order_products->fk_product_id          = $order_product_id;
        $order_products->so_product_quantity    = $so_product_quantity;
        $order_products->so_product_cost        = $op_product_cost;
        $order_products->so_product_price       = $op_product_cost * $so_product_quantity;
        $order_products->so_product_currency    = $so_order_currency_id;
        $order_products->so_exchange_rate       = $exchange_rate;
        $order_products->so_stock_id            = $product_stock[0]['is_id'];
        $order_products->save();
        
        
        
        $order_info = Orders::find($order_id);
        
        // check currency of order and currency of products
        //$product_currency_id
        
       
        
   /** if($so_order_currency_id != $product_currency_id)
        {
            // calculation new total order info
            if(count($currency_exchange) == 0)
            {
                $order_currency     = Currency::find($so_order_currency_id);
                $product_currency   = Currency::find($product_currency_id);
                $total_order        = convertCurrency($total_order, $product_currency->cc_currency_code, $order_currency->cc_currency_code);
            }
            else
            {
                $exchange_rate  = $currency_exchange[0]['er_exchange_rate'];
                $total_order    = $total_order * $exchange_rate;
            }
        }
       
        dd($op_product_cost);*/
        
        $total_order                = $order_info->so_total_cost + ( $op_product_cost * $order_products->so_product_quantity );
        
        $order_info->so_total_cost  = $total_order;
        $order_info->save();
        
        $result_array['is_error']       = 0;
        $result_array['total_order']    = $total_order;
        $result_array['error_msg']      = 'Order Information Has been saved';
        
        return Response()->json($result_array);
        
    }
    
    
    /**
     * get price of product with currency selected in the order
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetProductPrice(Request $request)
    {
        $so_id          =  $request->input('so_id'); 
        $product_id     =  $request->input('product_id');
        $result_array   = array();
        
        $lst_currency   = Currency::all();
        $currency_array = CreateDatabaseArrayByIndex($lst_currency, 'cc_id');
        
        $order_info     = Orders::find($so_id);
        $product_info   = Products::find($product_id); 
        $product_currency   = $product_info->p_product_currency;
        $order_currency     = $order_info->so_order_currency;
        
        $today_date = date("Y-m-d");
        $currency_exchange = CurrencyExchangeRates::whereErFromCurrency($product_currency)->whereErToCurrency($order_currency)->where('er_date_exchange','=',$today_date)->get();
        
        $selling_price = $product_info->p_product_selling_price;
        $op_product_cost = 0;
        if(count($currency_exchange) == 0)
        {
            $op_product_cost    = convertCurrency($selling_price,$currency_array[ $product_currency ]['cc_currency_code'], $currency_array[ $order_currency ]['cc_currency_code']);
        }
        else 
        {
            $exchange_rate      = $currency_exchange[0]['er_exchange_rate'];
            $op_product_cost    = $selling_price * $exchange_rate;
        }
        
        $result_array['is_error']        = 0;
        $result_array['product_price']   = $op_product_cost;
        $result_array['selling_price']   = $selling_price;
        $result_array['error_msg']       = 'Operation completed successfully';
        
        return Response()->json($result_array);
    }
    
    /**
     * Pay Order by changing status and generate invoice and receipts
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function PayOrder(Request $request)
    {
        $so_id = $request->input('so_id');
        $result_array = array();
        
        $order_info = Orders::find($so_id);
        $order_info->so_pay_date    = date("Y-m-d");
        $order_info->so_order_paied = 1;
        $order_info->save();
        
        $customer_id    = $order_info->so_customer_id;
        $customer_info  = Customers::find($customer_id);
        
        $AccountingManager = new AccountingManager();
        
        $invoice_code = $AccountingManager->GenerateInvoiceCode();
        
        // Create a new invoice and create a payment record /receipt record
        // give the ability from the order page to generate the invoice and receipt file
        $invoice_info = new Invoices();
        $invoice_info->bi_invoice_ref   = $invoice_code;
        $invoice_info->bi_invoice_code  = $invoice_code;
        $invoice_info->fk_account_id    = $customer_info->ic_account_number;
        $invoice_info->fk_customer_id   = $customer_id;
        $invoice_info->bi_invoice_date  = date("Y-m-d");
        $invoice_info->bi_due_date      = date("Y-m-d");
        $invoice_info->bi_payment_terms = 1;
        $invoice_info->bi_payment_type  = 2;
        $invoice_info->bi_invoice_note  = $order_info->so_order_note;
        $invoice_info->bi_total_cost    = $order_info->so_total_cost;
        $invoice_info->bi_vat_id        = $order_info->so_vat_id;
        $invoice_info->bi_discount      = 0;
        $invoice_info->bi_total_price    = $order_info->so_total_cost;
        $invoice_info->bi_invoice_currency= $order_info->so_order_currency;
        $invoice_info->bi_invoice_paid= 1;
        $invoice_info->bi_number_payments = 1;
        $invoice_info->save();
        
        $bi_id = $invoice_info->bi_id;
        

        
        
        // Save the transaction and movememnt data to the accounting table
        
        $payment_type_info      = PaymentTypes::find(2);
        $pt_payment_account     = $payment_type_info->pt_payment_account;
        
        $AccTransaction = new Transactions();
        $AccTransaction->at_transaction_date    = $invoice_info->bi_invoice_date;
        $AccTransaction->at_creation_date       = date("Y-m-d");
        $AccTransaction->at_accounting_doc      = $invoice_info->bi_invoice_code;
        $AccTransaction->fk_acc_journal_id      = 3;
        $AccTransaction->save();
        $at_id = $AccTransaction->at_id;
        
        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $customer_info->ic_account_number;
        $TransactionMovement->tm_sub_ledger_account = $pt_payment_account;
        $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
        $TransactionMovement->tm_debit              = $invoice_info->bi_total_price;
        $TransactionMovement->tm_credit             = 0;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $TransactionMovement->save();

        
        $TransactionMovement = new TransactionMovements();
        $TransactionMovement->fk_tran_id            = $at_id;
        $TransactionMovement->tm_ledger_account     = $pt_payment_account;
        $TransactionMovement->tm_sub_ledger_account = $customer_info->ic_account_number;
        $TransactionMovement->tm_ledger_label       = $invoice_info->bi_invoice_code;
        $TransactionMovement->tm_debit              = 0;
        $TransactionMovement->tm_credit             = $invoice_info->bi_total_price;
        $TransactionMovement->tm_creation_date      = date("Y-m-d");
        $TransactionMovement->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $TransactionMovement->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = 'Operation completed successfully';
        
        return Response()->json($result_array);
    }
    
    /**
     * Delete Order information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteOrderInfo(Request $request)
    {
        
        $so_id= $request->input('so_id');
         
        $order_info  = shippingOrders::find( $so_id );
        $order_info->so_is_deleted          = 1;
        $order_info->so_deleted_by          = Session('user_id');
        $order_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}