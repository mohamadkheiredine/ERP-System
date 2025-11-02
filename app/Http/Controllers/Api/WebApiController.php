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
use App\library\CustomersManager;
use App\models\Inventory\Customers;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
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
    public function CreateWebCustomer(Request $request)
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



        $query ="SELECT p.p_id AS product_id,p.p_product_ref AS product_reference,p.p_product_name AS product_name,p.p_barcode AS barcode,SUM(ist.is_quanity) AS total_quantity,COUNT(ist.is_id) AS stock_records_count,sc.cc_id as currency_id FROM inventory_products p LEFT JOIN inventory_stocks ist ON p.p_id = ist.fk_product_id LEFT JOIN sys_currency sc ON ist.is_price_currency = sc.cc_id WHERE p.p_product_is_deleted = 0 AND ist.is_is_deleted = 0 AND ist.fk_warehouse_id IN(" . implode(',',$warehouses_array) . ") AND ist.is_quanity > 0 GROUP BY p.p_id, p.p_product_ref, p.p_product_name, p.p_barcode,ist.is_price_currency ORDER BY  p.p_product_name;";


        $lst_product_data = DB::select($query);


        if(count($lst_product_data) == 0)
        {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = "No Stock Available for this warehouses";
            return Response()->json($result_array);
        }


            foreach ($lst_product_data as $index => $product_data)
            {
                $stock_array[] = array(
                   'product_id' =>  $product_data->product_id,
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

}
