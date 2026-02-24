<?php

/***********************************************************
OrdersController.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 20, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

 ***********************************************************/



namespace App\Http\Controllers\Api;

use App\library\OrdersExport;

use App\Http\Controllers\Controller;
use App\models\Billing\Receipts;
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
use App\models\Accounting\DefaultAccounts;
use App\models\Sales\Orders;
use App\library\OrdersManager;
use App\models\Sales\OrderProducts;
use App\library\AccountingManager;
use App\models\Billing\Invoices;
use App\models\Inventory\Customers;
use App\models\Billing\InvoiceProducts;
use App\models\Accounting\Transactions;
use App\models\Accounting\TransactionMovements;
use App\models\Billing\PaymentTypes;
use App\models\System\Companies;
use App\models\System\CurrencyExchangeRates;
use App\models\Billing\InvoicePayments;
use App\models\Inventory\StockIds;
use App\models\Inventory\Vendors;
use App\models\Phones\PhoneLines;
use League\Csv\Writer;
use App\library\CustomersManager;
use App\models\FnB\FnbPosShift;
use App\models\FnB\SalesPosShift;
use App\models\Sales\Terminals;
use App\models\Sales\StoreEmployees;
use Maatwebsite\Excel\Facades\Excel;


class OrdersController extends Controller
{


    /**
     * Create POS Order and generate all accounting information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function CreatePOSOrder(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $order_id           = $request->input('order_id');
        $warehouse_id       = $request->input('warehouse_id');
        $company_currency   = $request->input('company_currency');
        $order_items        = $request->input('order_items');
        $order_items        = json_decode($order_items, true);
        $pos_sub_total      = $request->input('pos_sub_total');
        $pos_discount       = $request->input('pos_discount');
        $pos_total          = $request->input('pos_total');
        $vendor_id          = $request->input('vendor_id');
        $customer_id          = $request->input('customer_id');
        $big_invoice          = $request->input('big_invoice');
        $store_id          = $request->input('store_id');
        $company_id          = $request->input('company_id');
        $payment_type          = $request->input('payment_type');

        $delcustomername          = $request->input('delcustomername');
        $delcustomerphone          = $request->input('delcustomerphone');
        $delcustomeraddress          = $request->input('delcustomeraddress');
        $deliveryFee          = $request->input('deliveryFee');
        $delivery_id          = strlen($delcustomerphone) > 0 ? 1 : 0;

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        //
        if($customer_id > 0 && $payment_type == 'credit')
        {
            $customer_info = Customers::find($customer_id);
            if($customer_info->ic_allow_credit == 0)
            {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'customer account is not allowed  to credit !!';
                return Response()->json($result_array);
            }
        }

        if ($delivery_id != 0) {

            if(strlen($delcustomername) > 0  && strlen($delcustomerphone) > 0)
            {
                if ($customer_id > 0)
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

                    if (count($customer_check) == 0) {
                        $customer_manager = new CustomersManager();
                        $params = array(
                            'company_id' => $user_info->fk_company_id
                        );
                        $ic_customer_code = $customer_manager->GenerateCustomerCode($params);

                        $customer_info = new Customers();
                        $customer_info->ic_company_id = $company_id;
                        $customer_info->ic_customer_name = $delcustomername;
                        $customer_info->ic_customer_address = $delcustomeraddress;
                        $customer_info->ic_customer_phone = $delcustomerphone;
                        $customer_info->ic_customer_mobile = $delcustomerphone;
                        $customer_info->ic_customer_code = $ic_customer_code;

                        $account_info   = ChartAccounts::where("aa_account_ref", "=", "4111")->get();
                        $account_info = $account_info[0];

                        $count   = ChartAccounts::where("aa_account_ref", "LIKE", "4111%")->count();

                        $new_count      = $count + 1;
                        $aa_account_ref = $account_info->aa_account . (string)sprintf('%05d', $new_count);

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
                    } else {
                        $customer_id = $customer_check[0]->ic_id;
                        $customer_info = Customers::find($customer_id);
                        $customer_info->ic_customer_name = $delcustomername;
                        $customer_info->ic_customer_address = $delcustomeraddress;
                        $customer_info->ic_customer_phone = $delcustomerphone;
                        $customer_info->save();
                    }
                }
            }
            else
            {
                // if phone only exist show it in receipt
                $customer_info = Customers::whereIcDefaultCustomer(1)->first();
                $customer_id = $customer_info->ic_id;
            }


        }

        // get default customer id
        $vendor_account_id = 0;

        $vendor_info = new Vendors();

        if ($customer_id == 0) {
            $customer_info = Customers::whereIcDefaultCustomer(1)->first();

            if ($customer_info == null) {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'Please Select A Customer Or Create a Default Custromer to Save all order there';

                return Response()->json($result_array);
            }
            $customer_id = $customer_info->ic_id;
        } else {
            $customer_info = Customers::find($customer_id);
        }


        $order_manager = new OrdersManager();

        $fisical_year =  $request->cookie('fisical_year')  !== null ? $request->cookie('fisical_year') : date("Y");

        $params_array = array(
            'company_id' => $company_id,
            'fisical_year' => $fisical_year
        );
        $so_order_code      = $order_manager->GeneratePOSOrderCode($params_array);
        $so_order_label     = "";

        $so_order_barcode = rand(100000000, 999999999);

        $creation_date      = date("Y-m-d");
        $full_date      = date("Y-m-d H:i:s");
        $creation_time      = date("H:i:s");
        $so_vat_id = 0;

        // create a new order
        $order_info = new Orders();

        if ($order_id != 0)
            $order_info = Orders::find($order_id);

        if ($order_id == 0) {
            $order_info->so_order_code       = $so_order_code;
            $order_info->so_order_barcode    = $so_order_barcode;
            $order_info->fk_user_id          = $user_id;
            $order_info->so_assign_to        = $delivery_id;
            $order_info->fk_warehouse_id     = $warehouse_id;
            $order_info->so_order_status     = 1;
            $order_info->so_vendor_id        = $vendor_id;
            $order_info->so_creation_date    = $creation_date;
            $order_info->so_product_type     = 1;
            $order_info->so_payment_type     = 1;
            $order_info->so_order_label      = $so_order_label;
            $order_info->so_order_note       = "";
            $order_info->so_order_date       = $full_date;
            $order_info->so_delivery_date    = $creation_date;
            $order_info->so_vat_id           = $so_vat_id;
            $order_info->so_delivery_customer_name           = $delcustomername;
            $order_info->so_delivery_customer_phone           = $delcustomerphone;
            $order_info->so_delivery_customer_address           = $delcustomeraddress;
            $order_info->so_pos_order        = 1;
        }

        $order_info->so_company_id        = $company_id;
        $order_info->so_sub_total        = $pos_sub_total;
        $order_info->so_total_discount   = $pos_discount;
        $order_info->so_total_cost       = $pos_total + $deliveryFee;
        $order_info->so_order_currency   = $company_currency;
        $order_info->so_order_customer   = $customer_id;
        $order_info->so_delivery_fees   = $deliveryFee;

        $order_info->save();

        $so_id = $order_info->so_id;

        $sub_total = 0;

        DB::beginTransaction();

        try {

            // delete old items if editing
            OrderProducts::whereFkOrderId($so_id)->delete();

            foreach ($order_items as $key => $item_order) {

                $discount_product = isset($item_order['product_discount']) ? (float)$item_order['product_discount'] : 0;
                $item_cost        = (float)($item_order['product_cost'] ?? 0);
                $item_price        = (float)($item_order['product_price'] ?? 0);
                $qty_requested    = (float)($item_order['product_quantity'] ?? 0);

                // subtotal (net line after discount)
                $sub_total += ($item_cost - ($item_cost * $discount_product / 100));

                // ✅ UNITS special case stays as you had it (no stock)
                if (($item_order['is_id'] ?? '') === 'UNITS') {

                    $orderitem = new OrderProducts();
                    $orderitem->fk_order_id         = $so_id;
                    $orderitem->fk_product_id       = -1;
                    $orderitem->so_stock_id         = -1;
                    $orderitem->so_product_cost     = $item_cost;
                    $orderitem->so_product_price    = $item_price;
                    $orderitem->so_product_quantity = $qty_requested;
                    $orderitem->so_unit_number      = $item_order['number_id'] ?? 0;
                    $orderitem->so_unit_label       = $item_order['product_name'] ?? '';
                    $orderitem->save();

                    // update phone line units (keep your logic)
                    $number_info = PhoneLines::find($item_order['number_id']);
                    if ($number_info) {
                        $units_amount = (float)($item_order['units_amount'] ?? 0) + 0.45;
                        $number_info->pl_total_units = (float)$number_info->pl_total_units - $units_amount;
                        $number_info->save();
                    }

                    continue;
                }

                // ✅ Normal stock product
                $product_id = (int)($item_order['p_id'] ?? 0);
                if ($product_id <= 0 || $qty_requested <= 0) continue;

                // 1️⃣ total available (includes negatives, but we only consume positives)
                $qty_available = (float) DB::table('inventory_stocks')
                    ->where('fk_product_id', $product_id)
                    ->where('fk_warehouse_id', $warehouse_id)
                    ->where('is_is_deleted', 0)
                    ->where('is_stock_status', 1)
                    ->lockForUpdate()
                    ->sum('is_quanity');

                $remaining = $qty_requested;

                // 2️⃣ FIFO consume positive batches
                if ($qty_available > 0) {

                    $stock_batches = DB::table('inventory_stocks')
                        ->where('fk_product_id', $product_id)
                        ->where('fk_warehouse_id', $warehouse_id)
                        ->where('is_is_deleted', 0)
                        ->where('is_quanity', '>', 0)
                        ->where('is_stock_status', 1)
                        ->orderBy('is_id', 'ASC')
                        ->lockForUpdate()
                        ->get();

                    foreach ($stock_batches as $batch) {
                        if ($remaining <= 0) break;

                        $batchQty = (float)$batch->is_quanity;

                        if ($batchQty >= $remaining) {
                            DB::table('inventory_stocks')
                                ->where('is_id', $batch->is_id)
                                ->update([
                                    'is_quanity'    => $batchQty - $remaining,
                                    'is_updated_at' => now(),
                                ]);
                            $remaining = 0;
                        } else {
                            DB::table('inventory_stocks')
                                ->where('is_id', $batch->is_id)
                                ->update([
                                    'is_quanity'    => 0,
                                    'is_updated_at' => now(),
                                ]);
                            $remaining -= $batchQty;
                        }
                    }
                }

                // 3️⃣ If still remaining → merge into existing negative row OR create one
                if ($remaining > 0) {

                    $negative_stock = DB::table('inventory_stocks')
                        ->where('fk_product_id', $product_id)
                        ->where('fk_warehouse_id', $warehouse_id)
                        ->where('is_is_deleted', 0)
                        ->where('is_stock_status', 1)
                        ->where('is_quanity', '<', 0)
                        ->lockForUpdate()
                        ->orderBy('is_id', 'ASC')
                        ->first();

                    if ($negative_stock) {
                        DB::table('inventory_stocks')
                            ->where('is_id', $negative_stock->is_id)
                            ->update([
                                'is_quanity'    => (float)$negative_stock->is_quanity - $remaining, // more negative
                                'is_updated_at' => now(),
                            ]);
                    } else {
                        DB::table('inventory_stocks')->insert([
                            'fk_product_id'     => $product_id,
                            'fk_warehouse_id'   => $warehouse_id,
                            'is_quanity'        => -$remaining,
                            'is_price_currency' => $company_currency,
                            'is_stock_currency' => $company_currency,
                            'is_stock_label'    => 'NEGATIVE STOCK AUTO-GENERATED',
                            'is_created_by'     => auth()->id() ?? 0,
                            'is_creation_date'  => now(),
                        ]);
                    }
                }

                // 4️⃣ Save order line
                $orderitem = new OrderProducts();
                $orderitem->fk_order_id         = $so_id;
                $orderitem->fk_product_id       = $product_id;
                $orderitem->so_stock_id         = $item_order['is_id'] ?? 0;
                $orderitem->so_product_cost     = $item_cost;
                $orderitem->so_discount         = $discount_product;
                $orderitem->so_product_price    = $item_price;
                $orderitem->so_product_quantity = $qty_requested;
                $orderitem->so_product_currency = $company_currency;
                $orderitem->save();
            }

            // ✅ Update order totals from computed subtotal
            $order_info = Orders::find($so_id);
            $order_info->so_sub_total      = $sub_total;
            $order_info->so_store_id        = $store_id;
            $order_info->so_total_discount = $pos_discount;
            $order_info->so_total_cost     = $pos_total; // keep what UI sent OR recompute if you want
            $order_info->save();


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'is_error' => 1,
                'error_message' => $e->getMessage(),
            ]);
        }


        // update order
        if ($order_info->so_sub_total == null) {
            $order_info = Orders::find($so_id);
            $order_info->so_sub_total = $sub_total;
            $order_info->so_total_discount   = $pos_discount;
            $order_info->so_total_cost       = $sub_total - (($pos_total * $pos_discount) / 100);
            $order_info->save();
        }


        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();
        $company_info = Companies::find($company_id);

        // save transaction and movement to the accounting table
        $payment_type_info      = PaymentTypes::find(2);
        $pt_payment_account     = $payment_type_info->pt_payment_account;

        $transaction_info = new Transactions();
        $transaction_info->at_company_id    = $company_id;
        $transaction_info->at_store_id    = $store_id;
        $transaction_info->at_transaction_date    = date("Y-m-d");
        $transaction_info->at_creation_date       = date("Y-m-d");
        $transaction_info->at_accounting_doc      = $so_order_code;
        $transaction_info->fk_acc_journal_id      = 3;
        $transaction_info->save();
        $at_id = $transaction_info->at_id;



        $movement_info = new TransactionMovements();
        $movement_info->fk_tran_id            = $at_id;
        $movement_info->tm_company_id            = $company_id;
        $movement_info->tm_store_id            = $store_id;
        $movement_info->tm_trans_code            = "SALES_ORDER";
        $movement_info->tm_ledger_account     = $customer_info->ic_account_number;
        $movement_info->tm_sub_ledger_account = $customer_info->ic_account_number;
        $movement_info->tm_ledger_label       = $so_order_code;
        $movement_info->tm_debit              = $pos_total + $deliveryFee;
        $movement_info->tm_credit             = 0;
        $movement_info->tm_creation_date      = date("Y-m-d");
        $movement_info->tm_transaction_date      = date("Y-m-d");
        $movement_info->tm_currency_id        = $company_currency;
        $movement_info->save();

        if($payment_type != "credit")
        {
            $movement_info                        = new TransactionMovements();
            $movement_info->fk_tran_id            = $at_id;
            $movement_info->tm_company_id            = $company_id;
            $movement_info->tm_store_id            = $store_id;
            $movement_info->tm_trans_code            = "SALES_ORDER";
            $movement_info->tm_ledger_account     = $pt_payment_account;
            $movement_info->tm_sub_ledger_account = $pt_payment_account;
            $movement_info->tm_ledger_label       = $so_order_code;
            $movement_info->tm_debit              = 0;
            $movement_info->tm_credit             = $pos_total + $deliveryFee;
            $movement_info->tm_creation_date      = date("Y-m-d");
            $movement_info->tm_transaction_date      = date("Y-m-d");
            $movement_info->tm_currency_id        = $company_currency;
            $movement_info->save();
        }


        $order_info = Orders::find($so_id);
        $order_info->so_trans_id      = $at_id;
        $order_info->save();

        $tax_info = VatAccounts::find(1);

        //$tax_total =  ( $tax_info->av_vat_rate / 100 ) * $pos_total;
        $total = $pos_total;
        // generate the POS Receipt
        $data = array(
            "company_info"      => $company_info,
            "lst_order_items"   => $lst_order_items,
            "order_info"        => $order_info,
            "user_info"         => $user_info,
            "pos_sub_total" => $pos_sub_total,
            "cost_total"        => $total,
            "pos_discount"         => $pos_discount,
            "creation_date"     => $creation_date,
            "so_order_code" => $so_order_code,
            "creation_time" => $creation_time,
            "delcustomername" => $order_info->so_delivery_customer_name,
            "delcustomerphone" => $order_info->so_delivery_customer_phone,
            "delcustomeraddress" => $order_info->so_delivery_customer_address,
            "deliveryFee" => $deliveryFee
        );

        if ($delivery_id != 0) {
            $customer_info = Customers::find($customer_id);
            $data['delivery_id'] = $delivery_id;
            $data['customer_info'] = $customer_info;
        }

        if ($big_invoice == 1)
            $pos_receipt = view('templates.posa5invoices', $data)->render();
        else
            $pos_receipt = view('templates.posinvoices', $data)->render();

        $result_array['receipt_link']           = url('/order/posreceipt/' . $so_id . "?company_id=" . $company_id . "&user_id=" . $user_id . "&cost_total=" . $total);
        $result_array['is_error']           = 0;
        $result_array['order_id']           = $so_id;
        $result_array['order_code']           = $so_order_code;
        $result_array['error_msg']          = "Order Saved";
        $result_array['pos_receipt']        = $pos_receipt;

        return Response()->json($result_array);
    }


    /**
     * Get Last order information
     * @param Request $request
     */
    public function GetLastOrderInfo(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $result_array       = array();

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $order_info = new Orders();

        $order_data = $order_info->orderby('so_id', 'DESC')->first();


        $so_id = $order_data->so_id;


        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();
        $order_items = array();
        foreach ($lst_order_items as $key => $item_info) {
            $order_items[] = array(
                'barecode' =>  $item_info->Products->p_barcode,
                'image_url' =>  "",
                'is_id' =>  $item_info->Products->p_id,
                'p_id' => $item_info->Products->p_id,
                'product_cost' => $item_info->so_product_price,
                'product_currency' =>  $item_info->so_product_currency,
                'product_discount' =>  $item_info->so_discount,
                'product_name' => $item_info->Products->p_product_name,
                'product_quantity' => $item_info->so_product_quantity,
                'sec_cur_product_cost' =>  $item_info->so_product_cost,
                'uid' => $item_info->Products->p_id
            );
        }

        $result_array['is_error'] = 0;
        $result_array['order_items'] = $order_items;
        $result_array['order_id'] = $so_id;
        $result_array['order_id'] = $so_id;
        $result_array['order_code'] = $order_data->so_order_code;
        $result_array['customer_id'] = $order_data->so_order_customer;
        $result_array['delivery_id'] = $order_data->so_assign_to;
        $result_array['delcustomername'] = "";
        $result_array['delcustomerphone'] = "";
        $result_array['delcustomeraddress'] = "";
        $result_array['createrUser'] = $order_data->fk_user_id;
        $result_array['pos_sub_total'] = $order_data->so_sub_total;
        $result_array['pos_discount'] = $order_data->pos_discount;
        $result_array['pos_total'] = $order_data->so_total_cost;
        $result_array['pos_delivery_fees'] = $order_data->so_delivery_fees;
        return Response()->json($result_array);
    }

    public function GetOrderInfoById(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $order_id            = $request->input('order_id');
        $result_array       = array();

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $order_data = Orders::find($order_id);


        $so_id = $order_data->so_id;


        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();
        $order_items = array();
        foreach ($lst_order_items as $key => $item_info) {
            $order_items[] = array(
                'barecode' =>  $item_info->Products->p_barcode,
                'image_url' =>  "",
                'is_id' =>  $item_info->Products->p_id,
                'p_id' => $item_info->Products->p_id,
                'product_cost' => $item_info->so_product_price,
                'product_currency' =>  $item_info->so_product_currency,
                'product_discount' =>  $item_info->so_discount,
                'product_name' => $item_info->Products->p_product_name,
                'product_quantity' => $item_info->so_product_quantity,
                'sec_cur_product_cost' =>  $item_info->so_product_cost,
                'uid' => $item_info->Products->p_id
            );
        }

        $result_array['is_error'] = 0;
        $result_array['order_items'] = $order_items;
        $result_array['order_id'] = $so_id;
        $result_array['company_currency'] = $order_data->so_order_currency;
        $result_array['customer_id'] = $order_data->so_order_customer;
        $result_array['delivery_id'] = $order_data->so_assign_to;
        $result_array['delcustomername'] = "";
        $result_array['delcustomerphone'] = "";
        $result_array['delcustomeraddress'] = "";
        $result_array['createrUser'] = $order_data->fk_user_id;
        $result_array['pos_sub_total'] = $order_data->so_sub_total;
        $result_array['pos_discount'] = $order_data->pos_discount;
        $result_array['pos_total'] = $order_data->so_total_cost;
        $result_array['delivery_fees'] = $order_data->so_delivery_fees;
        return Response()->json($result_array);
    }



    /**
     * Create Order Restaurant
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     *
     */
    public function CreateOrderRestaurant(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $order_id            = $request->input('order_id');
        $pos_order          = $request->input('pos_order');
        $payment_method            = $request->input('payment_method');
        $discount            = $request->input('discount');
        $total            = $request->input('total');
        $order_id            = $request->input('order_id');
        $sub_total            = $request->input('sub_total');
        $company_currency            = $request->input('company_currency');
        $customer_name            = $request->input('customer_name');
        $customer_mobile            = $request->input('customer_mobile');
        $assignto            = $request->input('assignto') != 0 ? $request->input('assignto') : $user_id;
        $delivery_fees            = $request->input('delivery_fees') != "" ? $request->input('delivery_fees') : 0;
        $extra_charges            = $request->input('extra_charges') != "" ? $request->input('extra_charges') : 0;
        $order_items = json_decode($pos_order);

        $user_info           = Users::find($user_id);
        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $total = $total + floatval($delivery_fees) + floatval($extra_charges);

        $order_manager = new OrdersManager();

        $company_id = $user_info->fk_company_id;

        $company_info   = Companies::find($company_id);

        $params_array = array(
            'company_id' => $user_info->fk_company_id
        );
        $so_order_code      = $order_manager->GeneratePOSOrderCode($params_array);
        $so_order_label     = "";

        $so_order_barcode = rand(100000000, 999999999);

        $creation_date    = date("Y-m-d H:i");
        $so_vat_id = 0;

        // create a new order
        if ($order_id == 0)
            $order_info = new Orders();
        else
            $order_info = Orders::find($order_id);

        if ($order_id == 0) {
            $order_info->so_order_code       = $so_order_code;
            $order_info->so_order_barcode    = $so_order_barcode;
            $order_info->fk_user_id          = $user_id;
            $order_info->so_assign_to        = $user_id;
            $order_info->fk_warehouse_id     = 0;
            $order_info->so_order_status     = 1;
            $order_info->so_order_customer   = 0;
            $order_info->so_vendor_id        = 0;
            $order_info->so_creation_date    = date("Y-m-d");
            $order_info->so_product_type     = 1;
            $order_info->so_payment_type     = 1;
            $order_info->so_order_label      = $so_order_label;
            $order_info->so_order_note       = "";
            $order_info->so_order_date       = $creation_date;
            $order_info->so_delivery_date    = $creation_date;
            $order_info->so_vat_id           = $so_vat_id;
            $order_info->so_pos_order        = 1;
        }

        $order_info->so_sub_total        = $sub_total;
        $order_info->so_total_discount   = $discount;
        $order_info->so_total_cost       = $total;
        $order_info->so_order_currency   = $company_currency;
        $order_info->so_order_customer   = 1;
        $order_info->so_payment_type   = $payment_method;
        $order_info->so_delivery_fees   = $delivery_fees;
        $order_info->so_extra_charges   = $extra_charges;
        $order_info->so_assign_to   = $assignto;

        $order_info->save();

        $so_id = $order_info->so_id;

        // save order rproducts
        foreach ($order_items as $key => $item_order) {
            if ($item_order != null) {
                //p_product_cost_price
                $product_id = $item_order->product_id;
                $product_info = Products::find($product_id);
                if ($product_info != null) {
                    $product_cost = $product_info->p_product_cost_price;
                } else {
                    $product_cost = 0;
                }

                $orderitem = new OrderProducts();
                $orderitem->fk_order_id          = $so_id;
                $orderitem->fk_product_id        = $item_order->product_id;
                $orderitem->so_stock_id          = -1;
                $orderitem->so_product_cost      = $product_cost;
                $orderitem->so_product_price     = $item_order->product_selling_price;
                $orderitem->so_product_quantity  = $item_order->quantity;
                $orderitem->save();
            }
        }

        $customer_exist = Customers::where("ic_customer_mobile", $customer_mobile)->count();

        $customer_info = new Customers();

        if ($customer_exist == 0) {
            $customer_info->ic_customer_name = $customer_name;
            $customer_info->ic_customer_mobile = $customer_mobile;
            $customer_info->save();
        } else {
            $customer_info = Customers::where("ic_customer_mobile", $customer_mobile)->get();

            $customer_info = $customer_info[0];
        }


        // generate the POS Receipt
        $data = array(
            "company_info"      => $company_info,
            "lst_order_items"   => $order_items,
            "order_info"        => $order_info,
            "user_info"         => $user_info,
            "customer_info"         => $customer_info,
            "cost_total"        => $total,
            "tax_total"         => 0
        );

        $pos_receipt = view('templates.posrestaurantinvoices', $data)->render();

        $result_array['is_error']           = 0;
        $result_array['error_msg']          = "Order has been completed";
        $result_array['pos_receipt']        = $pos_receipt;

        return Response()->json($result_array);
    }

    public function PrintOrder(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $order_id            = $request->input('orderId');
        $big_invoice         = $request->input('big_invoice');


        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $result_array        = array();

        $order_manager = new OrdersManager();

        $company_id = $user_info->fk_company_id;

        $company_info   = Companies::find($company_id);

        $params_array = array(
            'company_id' => $user_info->fk_company_id
        );
        $so_order_code      = $order_manager->GeneratePOSOrderCode($params_array);
        $so_order_label     = "";

        $so_order_barcode = rand(100000000, 999999999);
        $lst_order_items = OrderProducts::where('fk_order_id', $order_id)->get();

        $creation_date    = date("Y-m-d");
        $creation_time = date("H:i:s");
        $so_vat_id = 0;

        $order_info = Orders::find($order_id);


        $data = array(
            "company_info"      => $company_info,
            "lst_order_items"   => $lst_order_items,
            "order_info"        => $order_info,
            "user_info"         => $user_info,
            "pos_sub_total" => $order_info->so_total_cost,
            "cost_total"        => $order_info->so_total_cost,
            "pos_discount"         => $order_info->so_total_discount,
            "creation_date"     => $order_info->so_creation_date,
            "so_order_code" => $order_info->so_order_code,
            "deliveryFee" => $order_info->so_delivery_fees,
            "delcustomername" => $order_info->so_delivery_customer_name,
            "delcustomerphone" => $order_info->so_delivery_customer_phone,
            "creation_time" => $creation_time
        );

        $pos_receipt = view('templates.posprint_receipt', $data)->render();

        $result_array['is_error']           = 0;
        $result_array['display']           = $pos_receipt;

        return Response()->json($result_array);
    }


    public function DeleteOrder(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $order_id        = $request->input('order_id');


        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $order_info = Orders::find($order_id);
        $order_info->so_is_deleted = 1;
        $order_info->so_deleted_by = $user_id;
        $order_info->save();


        $result_array['is_error']           = 0;
        $result_array['error_msg']          = "Order has been deleted";

        return Response()->json($result_array);
    }



    /**
     * Get Order Information
     * @param Request $request
     */
    public function GetOrderInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $order_code        = $request->input('order_code');


        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $order_info = Orders::whereSoOrderCode($order_code)->get();

        if (count($order_info) == 0) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'Invalid Order Code Try Again !!';
        }
        $order_info = $order_info[0];

        // get order items

        $order_id = $order_info->so_id;

        $lst_order_items = OrderProducts::where('fk_order_id', $order_id)->get();
        $pos_order = array();
        foreach ($lst_order_items as $key => $itm_info) {
            $item_info = array(
                "product_id" =>  $itm_info->fk_product_id,
                "product_name" =>  $itm_info->Products->p_product_name,
                "product_selling_price" =>  $itm_info->so_product_cost,
                "quantity" =>  $itm_info->so_product_quantity,
                "currency_code" => ($order_info->Currency != null ?  $order_info->Currency->cc_currency_code : "")
            );

            $pos_order[$itm_info->fk_product_id] = json_encode($item_info);
        }


        $order_info = array(
            'order_id' => $order_info->so_id,
            'order_code' => $order_info->so_order_code,
            'payment_type' => $order_info->so_payment_type,
            'order_date' => $order_info->so_order_date,
            'sub_total' => $order_info->so_sub_total,
            'total_discount' => $order_info->so_total_discount,
            'total_cost' => $order_info->so_total_cost,
            'extra_charges' => $order_info->so_extra_charges,
            'delivery_fees' => $order_info->so_delivery_fees,
            'pos_order' => $pos_order
        );


        $result_array['is_error']           =  0;
        $result_array['error_msg']          =  "Operation Completed Successfully";
        $result_array['order_info']         =  $order_info;


        return Response()->json($result_array);
    }



    /**
     * get list of orders
     * @param Request $request
     */
    public function GetlistOrders(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $current_page        = $request->input('current_page');
        $date_from           = $request->input('from_date');
        $date_to             = $request->input('to_date');
        $date_range          = $request->input('date_range');
        $payment_type          = $request->input('payment_type');
        $search_key         = $request->input('search_key');


        $nbr_rows_per_pages    = 10;
        if ($current_page > 1)
            $skip = ($current_page - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }



        // get from to date based on daterange

        switch ($date_range) {
            case 1:
                {
                    $shiftDates = null;
                    $storeEmployee = StoreEmployees::where('se_employee_id', $user_id)->first();
                    if ($storeEmployee) {
                        $terminal = Terminals::where('pt_store_id', $storeEmployee->se_store_id)
                            ->where('pt_is_deleted', 0)
                            ->where('pt_is_active', 1)
                            ->first();
                        if ($terminal) {
                            $openShift = FnbPosShift::where('ps_terminal_id', $terminal->pt_id)
                                ->where('ps_status', 'OPEN')
                                ->first();
                            if ($openShift) {
                                $shiftDates = [$openShift->ps_opened_at, date("Y-m-d H:i:s")];
                            } else {
                                $lastShift = FnbPosShift::where('ps_terminal_id', $terminal->pt_id)
                                    ->where('ps_status', 'CLOSED')
                                    ->orderBy('ps_closed_at', 'DESC')
                                    ->first();
                                if ($lastShift) {
                                    $shiftDates = [$lastShift->ps_opened_at, $lastShift->ps_closed_at];
                                }
                            }
                        }
                    }
                    if ($shiftDates) {
                        $date_from = $shiftDates[0];
                        $date_to   = $shiftDates[1];
                    } else {
                        $date_from = date("Y-m-d 00:00:00");
                        $date_to   = date("Y-m-d 23:59:59");
                    }
                }
                break;
            case 2:
                {
                    $date_from = date("Y-m-d 00:00:00", strtotime('yesterday'));
                    $date_to = date("Y-m-d 23:59:59", strtotime('yesterday'));
                }
                break;
            case 3:
                {
                    $date_from = date("Y-m-d 00:00:00", strtotime('-7 day'));
                    $date_to   = date("Y-m-d 23:59:59", strtotime('yesterday'));
                }
                break;
            case 4:
                {
                    $date_from = date("Y-m-d 00:00:00", strtotime('-30 day'));
                    $date_to   = date("Y-m-d 23:59:59", strtotime('yesterday'));
                }
                break;
            case 5:
                {
                    if (empty($date_from) || empty($date_to)) {
                        $date_from = date("Y-m-d 00:00:00");
                        $date_to   = date("Y-m-d 23:59:59");
                    } else {
                        $date_from = date("Y-m-d 00:00:00", strtotime($date_from));
                        $date_to   = date("Y-m-d 23:59:59", strtotime($date_to));
                    }
                }
                break;
        }


        $orders = array();
        $where_cond = "Where so_is_deleted=0 AND so_order_date BETWEEN '$date_from' AND '$date_to'";
        $orders_cond = Orders::whereSoIsDeleted(0);
        $orders_cond = $orders_cond->whereBetween('so_order_date', [$date_from, $date_to]);

        if ($payment_type != 0 && $payment_type != "") {
            $orders_cond = $orders_cond->where('so_payment_type', $payment_type);
            $where_cond .= " AND so_payment_type = " . $payment_type;
        }

        if(strlen($search_key) > 0)
        {
            $orders_cond = $orders_cond->where('so_order_code','LIKE',"%" . $search_key . "%");
            $where_cond .= " AND so_order_code  LIKE '%" . $search_key . "%'";
        }


        $results = DB::select("SELECT SUM(so_total_cost) as total_amount FROM sales_orders " . $where_cond);


        $orders_count = $orders_cond->count();

        $total_pages = ceil($orders_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);
        $orders_cond = $orders_cond;
        $lst_orders = $orders_cond->skip($skip)->take($nbr_rows_per_pages)->orderby('so_creation_date', 'DESC')->get();

        $total_cost = 0;

        foreach ($lst_orders as $key => $order_info) {
            $orders[$order_info->so_id]['so_id']                        = $order_info->so_id;
            $orders[$order_info->so_id]['so_order_code']                        = $order_info->so_order_code;
            $orders[$order_info->so_id]['so_payment_type']                        = $order_info->so_payment_type;
            $orders[$order_info->so_id]['so_order_label']                        = $order_info->so_order_label;
            $orders[$order_info->so_id]['so_order_date']                        = $order_info->so_order_date;
            $orders[$order_info->so_id]['so_delivery_date']                        = $order_info->so_delivery_date;
            $orders[$order_info->so_id]['so_total_cost']                        = $order_info->so_total_cost;
            $orders[$order_info->so_id]['so_order_currency']                        = $order_info->so_order_currency;
            $orders[$order_info->so_id]['currency_code']                        = ($order_info->Currency != null) ? $order_info->Currency->cc_currency_code : "";

            $order_id = $order_info->so_id;

            $list_order_products = OrderProducts::whereFkOrderId($order_id)->get();
            $cost_price = 0;
            foreach ($list_order_products as $key => $order_product) {
                $cost_price = $cost_price + $order_product->so_product_cost;
                $total_cost = $total_cost + $order_product->so_product_cost;
            }

            $orders[$order_info->so_id]['cost_price']                        = $cost_price;
        }

        $result_array['is_error']       = 0;
        $result_array['orders']       = $orders;
        $result_array['total_amount'] = $results[0]->total_amount;
        $result_array['total_cost'] = $total_cost;
        $result_array['total_pages']       = $total_pages;


        return Response()->json($result_array);
    }


    public function ExportListOrdersToExcel(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $date_from           = $request->input('from_date');
        $date_to             = $request->input('to_date');
        $date_range          = $request->input('date_range');
        $payment_type          = $request->input('payment_type');


        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        switch ($date_range) {
            case 1:
                {
                    $date_from = date("Y-m-d");
                    $date_to = date("Y-m-d");
                }
                break;
            case 2:
                {
                    $date_from = date("Y-m-d", strtotime('yesterday'));
                    $date_to = date("Y-m-d", strtotime('yesterday'));
                }
                break;
            case 3:
                {
                    $date_from = date("Y-m-d", strtotime('-7 day'));
                    $date_to   = date("Y-m-d", strtotime('yesterday'));
                }
                break;
            case 4:
                {
                    $date_from = date("Y-m-d", strtotime('-30 day'));
                    $date_to   = date("Y-m-d", strtotime('yesterday'));
                }
                break;
            case 5:
                {
                    if (empty($date_from) || empty($date_to)) {
                        $date_from = date("Y-m-d");
                        $date_to   = date("Y-m-d");
                    } else {
                        $date_from = date("Y-m-d", strtotime($date_from));
                        $date_to   = date("Y-m-d", strtotime($date_to));
                    }
                }
                break;
        }


        $orders = array();
        $orders_cond = Orders::whereSoIsDeleted(0);
        if ($date_from != null && $date_to != null)
            $orders_cond = $orders_cond->whereBetween('so_order_date', [$date_from, $date_to]);

        if ($payment_type != 0 && $payment_type != "") {
            $orders_cond = $orders_cond->where('so_payment_type', $payment_type);
        }


        $orders_count   = $orders_cond->count();
        $lst_orders     = $orders_cond->get();


        $data = array();
        $data[] = ['Order Code', 'Order Date', 'Delivery Date', 'total Amount', 'total Cost', 'Extra Charge', 'Delivery Fees', 'Currency'];

        foreach ($lst_orders as $order_info) {

            $order_id = $order_info->so_id;

            $list_order_products = OrderProducts::whereFkOrderId($order_id)->get();
            $cost_price = 0;
            foreach ($list_order_products as $key => $order_product) {
                $cost_price = $cost_price + $order_product->so_product_cost;
            }

            $data[] =  [$order_info->so_order_code, $order_info->so_order_date, $order_info->so_delivery_date, $order_info->so_total_cost, $cost_price, $order_info->so_extra_charges, $order_info->so_delivery_fees, ($order_info->Currency != null ? $order_info->Currency->cc_currency_code : "")];
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertAll($data);

        $csv->output('data.csv');
    }


    /**
     * Create POS Order and pay downpayment and split the payments by pay multiple payments
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Response Json $result_array
     */
    public function SplitOrderPayment(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $order_id           = $request->input('order_id');
        $warehouse_id       = $request->input('warehouse_id');
        $company_currency   = $request->input('company_currency');
        $customer_id        = $request->input('customer_id');
        $order_items        = $request->input('order_items');
        $order_items        = json_decode($order_items, true);
        $pos_sub_total      = $request->input('pos_sub_total');
        $pos_discount       = $request->input('pos_discount');
        $pos_total          = $request->input('pos_total');
        $pos_payment_amount = $request->input('pos_payment_amount');
        $pos_remaining_amount   = $request->input('pos_remaining_amount');

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        // if customer id is 0 we  will link the order to the default customer id
        if ($customer_id == 0) {
            $customer_default = Customers::where('ic_customer_name', 'LIKE', '%POS%')->whereIcIsDeleted(0)->get();
            if (count($customer_default) > 0) {
                $customer_id = $customer_default[0]['ic_id'];
            } else {
                $customer_id = 1;
            }
        }



        $order_manager  = new OrdersManager();
        $company_id     = $user_info->fk_company_id;
        $company_info   = Companies::find($company_id);
        $params_array = array(
            'company_id' => $user_info->fk_company_id
        );
        $so_order_code      = $order_manager->GeneratePOSOrderCode($params_array);
        $so_order_label     = "";
        $so_order_barcode = rand(100000000, 999999999);
        $creation_date    = date("Y-m-d");
        $so_vat_id = 0;

        // create a new order
        $order_info = new Orders();

        if ($order_id != 0)
            $order_info = Orders::find($order_id);

        if ($order_id == 0) {
            $order_info->so_order_code       = $so_order_code;
            $order_info->so_order_barcode    = $so_order_barcode;
            $order_info->fk_user_id          = $user_id;
            $order_info->so_assign_to        = $user_id;
            $order_info->fk_warehouse_id     = $warehouse_id;
            $order_info->so_order_status     = 1;
            $order_info->so_order_customer   = $customer_id;
            $order_info->so_creation_date    = $creation_date;
            $order_info->so_product_type     = 1;
            $order_info->so_payment_type     = 1;
            $order_info->so_order_label      = $so_order_label;
            $order_info->so_order_note       = "";
            $order_info->so_order_date       = $creation_date;
            $order_info->so_delivery_date    = $creation_date;
            $order_info->so_vat_id           = $so_vat_id;
            $order_info->so_pos_order        = 1;
        }

        $order_info->so_sub_total        = $pos_sub_total;
        $order_info->so_total_discount   = $pos_discount;
        $order_info->so_total_cost       = $pos_total;
        $order_info->so_order_currency   = $company_currency;
        $order_info->so_order_customer   = $customer_id;

        $order_info->save();


        $so_id = $order_info->so_id;


        // save order rproducts
        foreach ($order_items as $key => $item_order) {
            $orderitem = new OrderProducts();
            $orderitem->fk_order_id          = $so_id;
            $orderitem->fk_product_id        = $item_order['p_id'];
            $orderitem->so_stock_id          = $item_order['is_id'];
            $orderitem->so_product_cost      = $item_order['product_cost'];
            $orderitem->so_product_price     = $item_order['product_cost'];
            $orderitem->so_product_quantity  = $item_order['product_quantity'];
            $orderitem->so_product_currency  = $company_currency;
            $orderitem->save();


            // change stock id if exist to sold
            DB::statement("UPDATE `inventory_stock_ids` SET si_stock_sold=1 WHERE si_stock_uid='" . $item_order['uid'] . "'");
        }

        $customer_info = Customers::find($customer_id);

        $company_id = $user_info->fk_company_id;

        $customer_account_id = 0;

        if ($customer_id == 0) {
            $customer_account_id = 41;
        } else {
            $customer_account_id = $customer_info->ic_account_number;
        }

        // save invoice information
        $AccountingManager = new AccountingManager();
        $params_array = array(
            'company_id' => $company_id
        );
        $invoice_code = $AccountingManager->GenerateInvoiceCode($params_array);

        $invoice_info = new Invoices();
        $invoice_info->bi_invoice_ref       = $invoice_code;
        $invoice_info->bi_invoice_code      = $invoice_code;
        $invoice_info->fk_account_id        = $customer_account_id;
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
        $invoice_info->bi_invoice_paid      = 0;
        $invoice_info->bi_number_payments   = 1;
        $invoice_info->save();

        $bi_id = $invoice_info->bi_id;

        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();
        foreach ($lst_order_items as $key => $oi_info) {
            $invoice_items = new InvoiceProducts();
            $stock_id           = $oi_info->so_stock_id;
            $stock_info         = Stocks::find($stock_id);
            $invoice_items->fk_invoice_id        = $bi_id;
            $invoice_items->ii_item_id           = $oi_info->fk_product_id;
            $invoice_items->ii_stock_id          = $stock_id;
            $invoice_items->ii_item_type         = $order_info->so_product_type;
            $invoice_items->ii_item_label        = $stock_info->products->p_product_name;
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

        // add payment receipt  for paied payment
        $payment_percentage =  (100 * $pos_payment_amount / $pos_total);
        $Paidinvoiceayment = new InvoicePayments();
        $Paidinvoiceayment->fk_invoice_id          = $bi_id;
        $Paidinvoiceayment->ip_payment_percentage  = $payment_percentage;
        $Paidinvoiceayment->ip_payment_label       = "";
        $Paidinvoiceayment->save();

        $remaining_percentage =  (100 * $pos_remaining_amount / $pos_total);
        $reminvoiceayment = new InvoicePayments();
        $reminvoiceayment->fk_invoice_id          = $bi_id;
        $reminvoiceayment->ip_payment_percentage  = $payment_percentage;
        $reminvoiceayment->ip_payment_label       = "";
        $reminvoiceayment->save();




        // save transaction and movement to the accounting table
        $payment_type_info      = PaymentTypes::find(2);
        $pt_payment_account     = $payment_type_info->pt_payment_account;

        $product_account = DefaultAccounts::whereDaAccountCode("ACCOUNT_BOUGHT_PRODUCT")->get();

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
        $movement_info->tm_credit             = $pos_payment_amount;
        $movement_info->tm_creation_date      = date("Y-m-d");
        $movement_info->tm_currency_id        = $invoice_info->bi_invoice_currency;
        $movement_info->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Payment has been completed";

        return Response()->json($result_array);
    }

    /**
     * Search Order and return information for the order and order items
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SearchOrderInfo(Request $request)
    {
        $order_barcode      = $request->input('order_barcode');
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $order_info = Orders::whereSoOrderCode($order_barcode)->first();
        if (!$order_info) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'Invalid Code Please Try again !!';

            return Response()->json($result_array);
        }


        $so_id = $order_info->so_id;
        $lst_order_items = OrderProducts::whereFkOrderId($so_id)->get();

        $items_order = array();
        $index = 0;
        foreach ($lst_order_items as $key => $item_info) {
            $items_order[$index]['p_id'] = $item_info->fk_product_id;
            $items_order[$index]['is_id'] = $item_info->so_stock_id;
            $items_order[$index]['product_name'] = $item_info->Products->p_product_name;
            $items_order[$index]['uid'] = $item_info->fk_product_id;
            $items_order[$index]['product_cost'] = $item_info->so_product_cost;
            $items_order[$index]['product_price'] = $item_info->so_product_price;
            $items_order[$index]['product_quantity'] = $item_info->so_product_quantity;
            $items_order[$index]['product_currency'] = $item_info->so_product_currency;

            $product_info = $item_info->Products;

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            if (strlen($product_info->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }
            $items_order[$index]['image_url'] = $img_src;

            $index++;
        }


        $result_array['is_error']               = 0;
        $result_array['items_order']            = $items_order;
        $result_array['customer_id']            = $order_info->so_order_customer;
        $result_array['order_id']               = $so_id;
        $result_array['sub_total']              = $order_info->so_sub_total;
        $result_array['total_discount']         = $order_info->so_total_discount;
        $result_array['total_cost']             = $order_info->so_total_cost;
        $result_array['delivery_fees']          = $order_info->so_delivery_fees;
        $result_array['order_currency']         = $order_info->so_order_currency;
        return Response()->json($result_array);
    }


    public function GetOrderInvoice(Request $request)
    {
        $order_id           = $request->input('order_id');
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');

        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }



        $company_id = $user_info->fk_company_id;

        $company_info   = Companies::find($company_id);

        $order_info = Orders::find($order_id);
        $customer_info = Customers::find($order_info->so_order_customer);
        $lst_order_items = OrderProducts::whereFkOrderId($order_id)->get();

        foreach ($lst_order_items as $key => $item_info) {
        }

        /**
         *             $orderitem = new OrderProducts();
                    $orderitem->fk_order_id          = $so_id;
                    $orderitem->fk_product_id        = $item_order->product_id;
                    $orderitem->so_stock_id          = -1;
                    $orderitem->so_product_cost      = $item_order->product_selling_price;
                    $orderitem->so_product_price     = $item_order->product_selling_price;
                    $orderitem->so_product_quantity  = $item_order->quantity;
                    $orderitem->save();
         */

        $order_items = array();


        // generate the POS Receipt
        $data = array(
            "company_info"      => $company_info,
            "lst_order_items"   => $order_items,
            "order_info"        => $order_info,
            "user_info"         => $user_info,
            "customer_info"         => $customer_info,
            "cost_total"        => $order_info->so_total_cost,
            "tax_total"         => 0
        );

        $pos_receipt = view('templates.posrestaurantinvoices', $data)->render();
    }

    /**
     * add product to order array
     * @param Request $request
     */
    public function AddProductToOrder(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $product_id         = $request->input('product_id');
        $product_uid        = $request->input('product_uid');
        $warehouse_id       = $request->input('warehouse_id');
        $company_currency   = $request->input('company_currency');
        $pos_quantity       = $request->input('pos_quantity');
        $sec_company_currency = $request->input('sec_company_currency');
        $pos_quantity = floatval($pos_quantity);
        if ($pos_quantity == 0)
            $pos_quantity = 1;
        $user_info          = Users::find($user_id);

        $c_hash             = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash             =  hash('sha256', $c_hash);
        $result_array       = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        if ($product_id != 0) {
            $product_info = Products::find($product_id);

            $stock_data['p_id']                         = $product_info->p_id;
            $stock_data['product_name']                 = $product_info->p_product_name;
            $stock_data['barecode']                     = $product_info->p_barcode;
            $stock_data['uid']                          = $product_info->p_barcode;
            $stock_data['product_cost']                 = $product_info->p_product_selling_price;
            $stock_data['product_currency']             = $product_info->p_product_currency;
            $stock_data['product_discount']             = 0;
            $stock_data['is_id']                        = $product_id;
            $stock_data['uid']                          = $product_id;
            $image_src_url              = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            $image_src_path             = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            if (strlen($product_info->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }
            $stock_data['image_url'] = $img_src;

            $price_item = $product_info->p_product_selling_price;

            $op_product_cost    = $price_item;

            //$stock_data['product_cost']              = $op_product_cost;
            $stock_data['sec_cur_product_cost']      = $op_product_cost;
            $stock_data['product_quantity']          = $pos_quantity;



            $total_cost_row = $op_product_cost * $pos_quantity;

            $result_array['is_error']           = 0;
            $result_array['row_data']           = $stock_data;
            $result_array['total_cost_row']     = $total_cost_row;

            return Response()->json($result_array);
        }


        $stock_ids = StockIds::whereSiStockUid($product_uid)->get();
        $stock_count = StockIds::whereSiStockUid($product_uid)->count();
        $stock_uid  = "";
        $stock_id   = 0;
        $stock_data = array();

        if ($stock_count > 0) {

            if ($stock_ids[0]->si_stock_sold == 0) {
                $stock_id   = $stock_ids[0]->fk_stock_id;
                $stock_uid  = $stock_ids[0]->si_stock_uid;
            } else {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'This item is Already Sold Please Get Information about it from GET INFO Section';

                return Response()->json($result_array);
            }


            // get stock info to get product data

            $stock_info = Stocks::find($stock_id);
            if ($pos_quantity > $stock_info->is_quanity) {
                $result_array['is_error']       = 1;
                $result_array['quantity']       = $stock_info->is_quanity;
                $result_array['error_message']  = 'Quantity Not enough For this Product !!';

                return Response()->json($result_array);
            }

            $product_id = $stock_info->fk_product_id;
            $product_info = Products::find($product_id);

            $stock_data['p_id']                         = $product_info->p_id;
            $stock_data['product_name']                 = $product_info->p_product_name;
            $stock_data['barecode']                     = $product_info->p_barcode;
            if ($stock_uid != null)
                $stock_data['uid']                          = $stock_uid;
            else

                $stock_data['uid']                          = $product_id;
            $stock_data['product_cost']                = $stock_info->is_selling_price;
            $stock_data['product_currency']             = $stock_info->is_price_currency;
            $stock_data['is_id']                        = $stock_id;
        } else {
            $product_info = Products::wherePBarcode($product_uid)->get();
            $product_count = Products::wherePBarcode($product_uid)->count();


            if ($product_count == 0) {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'This item is Not Exist ';

                return Response()->json($result_array);
            }

            $product_info = $product_info[0];

            $stock_data['p_id']                         = $product_info->p_id;
            $stock_data['product_name']                 = $product_info->p_product_name;
            $stock_data['barecode']                     = $product_info->p_barcode;

            $stock_data['uid']                          = $product_id;
            $stock_data['product_cost']                = $product_info->p_product_selling_price;
            $stock_data['product_currency']             = $product_info->p_product_currency;
            $stock_data['is_id']                     = $product_info->p_id;
        }

        $image_src_url              = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        $image_src_path             = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        if (strlen($product_info->p_product_profile_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }
        $stock_data['image_url'] = $img_src;

        $price_item         = $stock_data['product_cost'];
        $stock_currency     = $stock_data['product_currency'];
        $exchange_rate      = 0;
        $op_product_cost    = 0;

        // if currrency id are differant
        if ($stock_currency != $company_currency) {
            $today_date = date("Y-m-d");
            $currency_exchange = CurrencyExchangeRates::whereErFromCurrency($stock_currency)->whereErToCurrency($company_currency)->where('er_date_exchange', '=', $today_date)->get();
            $op_product_cost = 0;
            if (count($currency_exchange) == 0) {
                $sc_currency        = Currency::find($stock_currency);
                $cc_currency        = Currency::find($company_currency);
                $op_product_cost    = convertCurrency($price_item, $sc_currency->cc_currency_code, $cc_currency->cc_currency_code);
            } else {
                $exchange_rate      = $currency_exchange[0]['er_exchange_rate'];
                $op_product_cost    = $price_item * $exchange_rate;
            }
        } else {
            $op_product_cost = $price_item;
        }


        // get the second currency rate
        $currency_exchange = CurrencyExchangeRates::whereErFromCurrency($stock_currency)->whereErToCurrency($sec_company_currency)->orderBy('er_id', 'desc')->get();
        $sec_cur_product_cost = 0;
        if (count($currency_exchange) == 0) {
            $sc_currency        = Currency::find($stock_currency);
            $cc_currency        = Currency::find($sec_company_currency);
            $sec_cur_product_cost = convertCurrency($price_item, $sc_currency->cc_currency_code, $cc_currency->cc_currency_code);
        } else {
            $exchange_rate      = $currency_exchange[0]['er_exchange_rate'];
            $sec_cur_product_cost = $price_item * $exchange_rate;
        }

        $stock_data['product_cost']              = $op_product_cost;
        $stock_data['sec_cur_product_cost']      = $sec_cur_product_cost;
        $stock_data['product_quantity']          = $pos_quantity;


        $total_cost_row = $op_product_cost * $pos_quantity;

        $result_array['is_error']           = 0;
        $result_array['row_data']           = $stock_data;
        $result_array['total_cost_row']     = $total_cost_row;
        return Response()->json($result_array);
    }

    public function GetOrdersBetweenOpenCloseCash(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $user_info    = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";

        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $shift = FnbPosShift::where('ps_cashier_id', $user_id)
            ->where('ps_status', 'CLOSED')
            ->orderBy('ps_closed_at', 'DESC')
            ->first();

        if (!$shift) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'No closed shift found';
            return Response()->json($result_array);
        }

        $open_cash  = $shift->ps_opened_at;
        $close_cash = $shift->ps_closed_at;
        $lst_orders = Orders::whereBetween('so_order_date', [
            $open_cash,
            $close_cash
        ])->get();

        $result_array['is_error']    = 0;
        $result_array['open_cash']  = $open_cash;
        $result_array['close_cash'] = $close_cash;
        $result_array['orders']     = $lst_orders;

        return Response()->json($result_array);
    }


    public function ExportSalesOrders(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');

        $user_info    = Users::find($user_id);
        $result_array = array();

        $c_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error']      = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $shift = SalesPosShift::where('sps_cashier_id', $user_id)
            ->where('sps_status', 'CLOSED')
            ->orderByDesc('sps_closed_at')
            ->first();

        $orders = Orders::whereBetween('so_order_date', [
            $shift->sps_opened_at,
            $shift->sps_closed_at
        ])
            ->select([
                'so_id',
                'so_order_status',
                'so_payment_type',
                'so_product_type',
                'so_order_date'
            ])
            ->orderBy('so_order_date', 'ASC')
            ->get();


        $headings = [
            'Order ID',
            'Order Status',
            'Payment Type',
            'Product Type',
            'Order DateTime'
        ];

        return Excel::download(
            new OrdersExport($orders, $headings),
            'sales_orders_shift_' . $shift->sps_id . '_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
