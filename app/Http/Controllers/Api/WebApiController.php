<?php
/***********************************************************
 * WebApiController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/5/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\library\AccountingManager;
use App\library\CustomersManager;
use App\library\OrdersManager;
use App\models\Billing\InvoiceProducts;
use App\models\Billing\Invoices;
use App\models\Billing\PaymentTypes;
use App\models\Inventory\Customers;
use App\models\Inventory\Vendors;
use App\models\Phones\PhoneLines;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
use App\models\System\Companies;
use League\Csv\Writer;
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
use models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use Swap\Swap;
use App\models\System\Units;
use App\models\Users\Users;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\System\CurrencyExchangeRates;
use App\models\Inventory\StockIds;
use App\models\SRM\Suppliers;
use App\models\Sales\Orders;
use App\models\Sales\OrderProducts;


class WebApiController extends Controller
{

    /**
     * Create new Customer based on incoming data
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SaveWebCustomer(Request $request)
    {
        $customer_id            = $request->has('customer_id') ? $request->input('customer_id') : 0;
        $ic_customer_name       = $request->input('ic_customer_name');
        $ic_customer_address    = $request->input('ic_customer_address');
        $ic_customer_email      = $request->input('ic_customer_email');
        $ic_customer_website    = $request->input('ic_customer_website');
        $ic_customer_phone      = $request->input('ic_customer_phone');
        $ic_customer_mobile     = $request->input('ic_customer_mobile');
        $ic_hobbies             = $request->input('ic_hobbies');
        $ic_favorite_foods             = $request->input('ic_favorite_foods');
        $ic_work_title             = $request->input('ic_work_title');
        $ic_sports             = $request->input('ic_sports');
        $ic_birth_date             = $request->input('ic_birth_date');



        $customer_manager = new CustomersManager();
        $params = array(
            'company_id' => 2
        );
        $ic_customer_code = $customer_manager->GenerateCustomerCode($params);

        if($customer_id != 0)
        {
            $customer_info = Customers::find($customer_id);
        }
        else
        {
            $customer_info = new Customers();
        }



        $customer_info->ic_customer_code    = $ic_customer_code;
        $customer_info->ic_customer_name    = $ic_customer_name;
        $customer_info->ic_customer_address = $ic_customer_address;
        $customer_info->ic_customer_email   = $ic_customer_email;
        $customer_info->ic_customer_website = $ic_customer_website;
        $customer_info->ic_customer_phone = $ic_customer_phone;
        $customer_info->ic_customer_mobile = $ic_customer_mobile;
        $customer_info->ic_hobbies = $ic_hobbies;
        $customer_info->ic_birth_date = $ic_birth_date;
        $customer_info->ic_favorite_foods = $ic_favorite_foods;
        $customer_info->ic_work_title = $ic_work_title;
        $customer_info->ic_sports = $ic_sports;


        if(isset($_FILES['ic_avatar_pic']))
        {
            $image_data =  $customer_manager->UploadCustomersAvatar($customer_id);
            $customer_info->ic_image_base_src      = $image_data['data']['ic_image_base_src'];
            $customer_info->ic_image_file_name     = $image_data['data']['ic_file_name'];
            $customer_info->ic_image_extension     = $image_data['data']['ic_file_extension'];
        }

        // if this add new customer we create a new account and save it as account info
        if($customer_id == 0)
        {
            $account_info   = ChartAccounts::where("aa_account_ref","=","4111")->get();
            $account_info = $account_info[0];

            $count   = ChartAccounts::where("aa_account_ref","LIKE","4111%")->count();

            $new_count      = $count + 1;
            $aa_account_ref = $account_info->aa_account . (String)sprintf('%05d', $new_count);

            $AccAccounting = new ChartAccounts();
            $AccAccounting->aa_parent_account   = $account_info->aa_id;
            $AccAccounting->aa_account_ref      = $aa_account_ref;
            $AccAccounting->aa_account          = $aa_account_ref;
            $AccAccounting->aa_sub_account      = $account_info->aa_id;
            $AccAccounting->aa_account_label    = $ic_customer_name;
            $AccAccounting->fk_country_id       = 0;
            $AccAccounting->save();

            $aa_id = $AccAccounting->aa_id;
            $customer_info->ic_account_number = $aa_id;
        }

        $customer_info->save();


        $result_array['is_error'] = 0;
        $result_array['customer_id'] = $customer_info->ic_id;
        $result_array['customer_name'] = $ic_customer_name;
        return Response()->json($result_array);
    }


    /**
     * get list of stock for every product with stock available value in the store
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function GetListProducts(Request $request)
    {
        $online_store = Stores::wherePsIsDeleted(0)->wherePsIsActive(1)->wherePsOnlineStore(1)->first();
        $result_array = array();
        $warehouses_array = array();
        $stock_array = array();

        if(!$online_store)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "Store not found";
            return Response()->json($result_array);
        }

        $ps_id = $online_store->ps_id;

        $lst_warehouses = StoreWarehouses::whereSwStoreId($ps_id)->get();

        if(count($lst_warehouses) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "No Warehouse Selected for this Store";
            return Response()->json($result_array);
        }

        foreach ($lst_warehouses as $index => $warehouse_info)
        {
            $warehouses_array[] = $warehouse_info->sw_warehouse_id;
        }



        $query ="SELECT p.p_id AS product_id,p.p_product_ref AS product_reference,p.p_product_name AS product_name,p.p_product_profile_base_src,p.p_product_profile_file_name,p.p_product_profile_extention, p.p_barcode AS barcode,SUM(ist.is_quanity) AS total_quantity,COUNT(ist.is_id) AS stock_records_count,sc.cc_id as currency_id FROM inventory_products p LEFT JOIN inventory_stocks ist ON p.p_id = ist.fk_product_id LEFT JOIN sys_currency sc ON ist.is_price_currency = sc.cc_id WHERE p.p_product_is_deleted = 0 AND ist.is_is_deleted = 0 AND ist.fk_warehouse_id IN(" . implode(',',$warehouses_array) . ") AND ist.is_quanity > 0 GROUP BY p.p_id, p.p_product_ref, p.p_product_name, p.p_barcode,ist.is_price_currency ORDER BY  p.p_product_name;";


        $lst_product_data = DB::select($query);


        if(count($lst_product_data) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "No Stock Available for this warehouses";
            return Response()->json($result_array);
        }


            foreach ($lst_product_data as $index => $product_data)
            {

                $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_data->p_product_profile_base_src.$product_data->p_product_profile_file_name.".".$product_data->p_product_profile_extention;
                $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_data->p_product_profile_base_src.$product_data->p_product_profile_file_name.".".$product_data->p_product_profile_extention;
                if(strlen($product_data->p_product_profile_base_src) > 0 ){
                    $img_src = $image_src_url;
                }else{
                    $img_src = url('images/NoImageAvailable.jpg');
                }

                $stock_array[] = array(
                   'product_id' =>  $product_data->product_id,
                    'product_profile' =>  $img_src,
                   'product_reference' =>  $product_data->product_reference,
                   'product_name' =>  $product_data->product_name,
                   'barcode' =>  $product_data->barcode,
                   'total_quantity' =>  $product_data->total_quantity,
                   'stock_records_count' =>  $product_data->stock_records_count,
                   'currency_id' =>  $product_data->currency_id,
                );
            }


            $result_array['is_error'] = 0;
            $result_array['stock_records'] = $stock_array;


            return Response()->json($result_array);

    }


    /**
     * get information of selected product
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Getproductinfo(Request $request)
    {

        $product_id = $request->get('product_id');

        $online_store = Stores::wherePsIsDeleted(0)->wherePsIsActive(1)->wherePsOnlineStore(1)->first();
        $result_array = array();
        $warehouses_array = array();
        $stock_array = array();

        if(!$online_store)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "Store not found";
            return Response()->json($result_array);
        }

        $ps_id = $online_store->ps_id;

        $lst_warehouses = StoreWarehouses::whereSwStoreId($ps_id)->get();

        if(count($lst_warehouses) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "No Warehouse Selected for this Store";
            return Response()->json($result_array);
        }

        foreach ($lst_warehouses as $index => $warehouse_info)
        {
            $warehouses_array[] = $warehouse_info->sw_warehouse_id;
        }



        $query ="SELECT p.p_id AS product_id,p.p_product_ref AS product_reference,p.p_product_name AS product_name,p.p_product_profile_base_src,p.p_product_profile_file_name,p.p_product_profile_extention,p.p_barcode AS barcode,SUM(ist.is_quanity) AS total_quantity,COUNT(ist.is_id) AS stock_records_count,sc.cc_id as currency_id FROM inventory_products p LEFT JOIN inventory_stocks ist ON p.p_id = ist.fk_product_id LEFT JOIN sys_currency sc ON ist.is_price_currency = sc.cc_id WHERE p.p_product_is_deleted = 0 AND ist.is_is_deleted = 0 AND ist.fk_warehouse_id IN(" . implode(',',$warehouses_array) . ") AND ist.is_quanity > 0 AND p.p_id=" . $product_id . " GROUP BY p.p_id, p.p_product_ref, p.p_product_name, p.p_barcode,ist.is_price_currency ORDER BY  p.p_product_name;";


        $lst_product_data = DB::select($query);


        if(count($lst_product_data) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "No Stock Available for this warehouses";
            return Response()->json($result_array);
        }

        $product_data = $lst_product_data[0];
        $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_data->p_product_profile_base_src.$product_data->p_product_profile_file_name.".".$product_data->p_product_profile_extention;
        $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_data->p_product_profile_base_src.$product_data->p_product_profile_file_name.".".$product_data->p_product_profile_extention;
        if(strlen($product_data->p_product_profile_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }


        $stock_info_array = array(
            'product_id' =>  $product_data->product_id,
            'product_profile' =>  $img_src,
            'product_reference' =>  $product_data->product_reference,
            'product_name' =>  $product_data->product_name,
            'barcode' =>  $product_data->barcode,
            'total_quantity' =>  $product_data->total_quantity,
            'stock_records_count' =>  $product_data->stock_records_count,
            'currency_id' =>  $product_data->currency_id,
        );


        $result_array['is_error'] = 0;
        $result_array['stock_records'] = $stock_info_array;


        return Response()->json($result_array);

    }


    public function CreateOrder(Request $request)
    {
        $company_currency   = $request->input('company_currency');
        $order_items        = $request->input('order_items');
        $order_items        = json_decode( $order_items , true );
        $pos_sub_total      = $request->input('pos_sub_total');
        $pos_discount       = $request->input('pos_discount');
        $pos_total          = $request->input('pos_total');
        $vendor_id          = $request->input('vendor_id');
        $customer_id          = $request->input('customer_id');

        $store_info = Stores::wherePsOnlineStore(1)->get();

        if(count($store_info) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "Store not found";
            return Response()->json($result_array);
        }
        $store_info = $store_info[0];

        $store_warehouses = StoreWarehouses::whereSwStoreId($store_info->ps_id)->get();
        if(count($store_warehouses) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "Warehouse not found";
            return Response()->json($result_array);
        }

        $warehouse_id = $store_warehouses[0]->sw_warehouse_id;



        $delcustomername          = $request->input('delcustomername');
        $delcustomerphone          = $request->input('delcustomerphone');
        $delcustomeraddress          = $request->input('delcustomeraddress');
        $delivery_id          = strlen($delcustomername) > 0 ? 1 : 0;


        if($delivery_id != 0)
        {

            if($customer_id > 0)
            {
                $customer_info = Customers::find($customer_id);
                $customer_info->ic_customer_name = $delcustomername;
                $customer_info->ic_customer_address = $delcustomeraddress;
                $customer_info->ic_customer_phone = $delcustomerphone;
                $customer_info->ic_customer_mobile = $delcustomerphone;
                $customer_info->save();
            }
            else
            {
                // check if the customer exist
                $customer_check = Customers::whereIcCustomerName($delcustomername)->get();

                $customer_id = 0;

                if(count($customer_check) == 0)
                {
                    $customer_manager = new CustomersManager();
                    $params = array(
                        'company_id' => 2
                    );
                    $ic_customer_code = $customer_manager->GenerateCustomerCode($params);


                    $customer_info = new Customers();
                    $customer_info->ic_customer_name = $delcustomername;
                    $customer_info->ic_customer_address = $delcustomeraddress;
                    $customer_info->ic_customer_phone = $delcustomerphone;
                    $customer_info->ic_customer_mobile = $delcustomerphone;
                    $customer_info->ic_customer_code = $ic_customer_code;

                    $account_info   = ChartAccounts::where("aa_account_ref","=","4111")->get();
                    $account_info = $account_info[0];

                    $count   = ChartAccounts::where("aa_account_ref","LIKE","4111%")->count();

                    $new_count      = $count + 1;
                    $aa_account_ref = $account_info->aa_account . (String)sprintf('%05d', $new_count);

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
                    $customer_info = Customers::find($customer_id);
                    $customer_info->ic_customer_name = $delcustomername;
                    $customer_info->ic_customer_address = $delcustomeraddress;
                    $customer_info->ic_customer_phone = $delcustomerphone;
                    $customer_info->save();
                }
            }

        }




        // get default customer id
        $customer_info = Customers::find($customer_id);


        $order_manager = new OrdersManager();

        $company_id = 2;

        $company_info   = Companies::find($company_id);

        $params_array = array(
            'company_id' => 2
        );
        $so_order_code      = $order_manager->GenerateWebOrderCode( $params_array );
        $so_order_label     = "";

        $so_order_barcode = rand(100000000,999999999);

        $creation_date    = date("Y-m-d");
        $creation_time = date("H:i:s");
        $so_vat_id = 0;

        // create a new order
        $order_info = new Orders();



        $order_info->so_order_code       = $so_order_code;
        $order_info->so_order_barcode    = $so_order_barcode;
        $order_info->fk_user_id          = 0;
        $order_info->so_assign_to        = $delivery_id;
        $order_info->fk_warehouse_id     = $warehouse_id;
        $order_info->so_order_status     = 1;

        $order_info->so_vendor_id        = $vendor_id;
        $order_info->so_creation_date    = $creation_date;
        $order_info->so_product_type     = 1;
        $order_info->so_payment_type     = 1;
        $order_info->so_order_label      = $so_order_label;
        $order_info->so_order_note       = "";
        $order_info->so_order_date       = $creation_date;
        $order_info->so_delivery_date    = $creation_date;
        $order_info->so_vat_id           = $so_vat_id;
        $order_info->so_pos_order        = 1;
        $order_info->so_order_customer   = $customer_id;
        $order_info->so_sub_total        = $pos_sub_total;
        $order_info->so_total_discount   = $pos_discount;
        $order_info->so_total_cost       = $pos_total;
        $order_info->so_order_currency   = $company_currency;
        $order_info->so_order_customer   = $customer_id;

        $order_info->save();

        $so_id = $order_info->so_id;

        $sub_total = 0;

        $delete_items = OrderProducts::whereFkOrderId($so_id)->delete();

        // save order rproducts
        foreach ( $order_items as $key => $item_order )
        {
            $discount_product = isset($item_order['product_discount']) ? $item_order['product_discount'] : 0;

            $sub_total = $sub_total + ($item_order['product_cost'] - ( $item_order['product_cost'] * $discount_product /100));

            if($item_order['is_id'] == 'UNITS')
            {
                $orderitem = new OrderProducts();
                $orderitem->fk_order_id          = $so_id;
                $orderitem->fk_product_id        = -1;
                $orderitem->so_stock_id          = -1;
                $orderitem->so_product_cost      = $item_order['product_cost'];
                $orderitem->so_product_price     = $item_order['product_cost'] - ( $item_order['product_cost'] * $discount_product /100);
                $orderitem->so_product_quantity  = $item_order['product_quantity'];
                $orderitem->so_unit_number       = $item_order['number_id'];
                $orderitem->so_unit_label        = $item_order['product_name'];
                $orderitem->save();

                // change the amount of numbers in the number stock

                $number_info = PhoneLines::find($item_order['number_id']);
                $units_amount = intval($item_order['units_amount']) + 0.45;
                $pl_total_units = $number_info->pl_total_units - $units_amount;
                $number_info->pl_total_units = $pl_total_units;

            }
            else
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

                // change stock id if exist to sold
                DB::statement("UPDATE `inventory_stock_ids` SET si_stock_sold=1 WHERE si_stock_uid='" . $item_order['uid'] . "'");
            }
        }

        // update order
        if($order_info->so_sub_total == null)
        {
            $order_info = Orders::find($so_id);
            $order_info->so_sub_total = $sub_total;
            $order_info->so_total_discount   = $pos_discount;
            $order_info->so_total_cost       = $sub_total - (( $pos_total * $pos_discount ) /100) ;
            $order_info->save();
        }


        $company_id = 2;

        // save invoice information
        $AccountingManager = new AccountingManager();
        $params_array = array(
            'company_id' => $company_id
        );
        $invoice_code = $AccountingManager->GenerateInvoiceCode($params_array);
        $invoice_info = new Invoices();
        $invoice_info->bi_invoice_ref       = $invoice_code;
        $invoice_info->bi_invoice_code      = $invoice_code;
        $invoice_info->fk_account_id        = $customer_info->ic_account_number;
        $invoice_info->fk_customer_id       = $customer_id;
        $invoice_info->bi_invoice_date      = $creation_date;
        $invoice_info->bi_due_date          = $creation_date;
        $invoice_info->bi_payment_terms     = 1;
        $invoice_info->bi_payment_type      = 2;
        $invoice_info->bi_invoice_note      = $order_info->so_order_note;
        $invoice_info->bi_total_cost        = $order_info->so_sub_total;
        $invoice_info->bi_vat_id            = 1;
        $invoice_info->bi_discount          = $pos_discount;
        $invoice_info->bi_total_price       = $order_info->so_total_cost;
        $invoice_info->bi_invoice_currency  = $order_info->so_order_currency;
        $invoice_info->bi_invoice_note      = "New Invoice For Order #" . $so_order_code;
        $invoice_info->bi_invoice_paid      = 1;
        $invoice_info->bi_number_payments   = 1;
        $invoice_info->save();
        $bi_id = $invoice_info->bi_id;

        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();
        foreach ($lst_order_items as $key => $oi_info )
        {
            $invoice_items = new InvoiceProducts();

            if($item_order['is_id'] || $item_order['is_id'] == 0)
            {
                $invoice_items->fk_invoice_id        = $bi_id;
                $invoice_items->ii_item_id           = -1;
                $invoice_items->ii_stock_id          = -1;
                $invoice_items->ii_item_type         = $order_info->so_product_type;
                $invoice_items->ii_item_label        = $oi_info->so_unit_label;
                $invoice_items->ii_item_price        = $oi_info->so_product_price;
                $invoice_items->ii_item_qyt          = $oi_info->so_product_quantity;
                $invoice_items->ii_price_currency    = $oi_info->so_product_currency;
                $invoice_items->save();
            }
            else
            {

                $stock_id           = $oi_info->so_stock_id;
                $stock_info         = Stocks::find($stock_id);
                $invoice_items->fk_invoice_id        = $bi_id;

                $invoice_items->ii_item_id           = $oi_info->fk_product_id;
                $invoice_items->ii_stock_id          = $stock_id;
                $invoice_items->ii_item_type         = $order_info->so_product_type;
                $invoice_items->ii_item_label        = $stock_info->products ? $stock_info->products->p_product_name : '';
                $invoice_items->ii_item_price        = $oi_info->so_product_price;
                $invoice_items->ii_item_qyt          = $oi_info->so_product_quantity;
                $invoice_items->ii_price_currency    = $oi_info->so_product_currency;
                $invoice_items->save();

                $product_id = $oi_info->fk_product_id;
                $stock_id   = $oi_info->so_stock_id;

                $stock_info = Stocks::find($stock_id);
                $is_quanity = $stock_info->is_quanity - $oi_info->so_product_quantity;
                $stock_info->is_quanity = $is_quanity;
                $stock_info->is_price_stock = $is_quanity * $oi_info->is_price_item;
                $stock_info->save();

            }
        }


        // save transaction and movement to the accounting table
        $payment_type_info      = PaymentTypes::find(2);
        $pt_payment_account     = $payment_type_info->pt_payment_account;

        $transaction_info = new Transactions();
        $transaction_info->at_transaction_date    = $invoice_info->bi_invoice_date;
        $transaction_info->at_creation_date       = date("Y-m-d");
        $transaction_info->at_accounting_doc      = $invoice_info->bi_invoice_code;
        $transaction_info->fk_acc_journal_id      = 3;
        $transaction_info->save();
        $at_id = $transaction_info->at_id;



        $movement_info = new TransactionMovements();
        $movement_info->fk_tran_id            = $at_id;
        $movement_info->tm_ledger_account     = $customer_info->ic_account_number;
        $movement_info->tm_sub_ledger_account = $pt_payment_account;
        $movement_info->tm_ledger_label       = $invoice_info->bi_invoice_code;
        $movement_info->tm_debit              = $invoice_info->bi_total_price;
        $movement_info->tm_credit             = 0;
        $movement_info->tm_creation_date      = date("Y-m-d");
        $movement_info->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $movement_info->save();


        $movement_info                        = new TransactionMovements();
        $movement_info->fk_tran_id            = $at_id;
        $movement_info->tm_ledger_account     = $customer_info->ic_account_number;
        $movement_info->tm_sub_ledger_account = $pt_payment_account;
        $movement_info->tm_ledger_label       = $invoice_info->bi_invoice_code;
        $movement_info->tm_debit              = 0;
        $movement_info->tm_credit             = $invoice_info->bi_total_price;
        $movement_info->tm_creation_date      = date("Y-m-d");
        $movement_info->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $movement_info->save();

        $tax_info = VatAccounts::find(1);

        //$tax_total =  ( $tax_info->av_vat_rate / 100 ) * $pos_total;
        $total = $pos_total;

        $result_array['is_error']           = 0;
        $result_array['order_id']           = $so_id;
        $result_array['error_msg']          = "Order Saved";

        return Response()->json($result_array);
    }

}
