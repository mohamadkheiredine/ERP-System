<?php

/***********************************************************
ProductsController.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 3, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

 ***********************************************************/


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;

class ProductsController extends Controller
{
    /**
     * get list of product categories based on selected category id
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetListCategories(Request $request)
    {
        $user_id             = $request->input('user_id');
        $category_id         = $request->input('category_id');
        $g_hash              = $request->input('g_hash');
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

        $lst_categories = ProductCategories::wherePcIsDeleted(0)->where('pc_show_on_pos', 1);

        if ($category_id != 0) {
            $lst_categories = $lst_categories->whereFkPcId($category_id);
        }


        //$lst_categories = $lst_categories->wherePcUseSerialNumber(0)->orderBy('pc_category','DESC')->get();
        $lst_categories = $lst_categories->orderBy('pc_category', 'DESC')->get();

        $categories = array();

        foreach ($lst_categories as $index => $category_info) {
            $categories[$category_info->pc_id]['pc_id']               = $category_info->pc_id;
            $categories[$category_info->pc_id]['reference']           = $category_info->pc_cat_ref;
            $categories[$category_info->pc_id]['title']               = $category_info->pc_category;
            $categories[$category_info->pc_id]['description']         = $category_info->pc_description;
            $categories[$category_info->pc_id]['use_serial_number']   = $category_info->pc_use_serial_number;
            $categories[$category_info->pc_id]['show_on_pos']         = $category_info->pc_show_on_pos;




            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $category_info->pc_avatar_base_src . $category_info->pc_avatar_file_name . "." . $category_info->pc_avatar_extension;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $category_info->pc_avatar_base_src . $category_info->pc_avatar_file_name . "." . $category_info->pc_avatar_extension;

            if (strlen($category_info->pc_avatar_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }

            $categories[$category_info->pc_id]['avatar'] = $img_src;
        }

        $result_array['is_error']       = 0;
        $result_array['categories']       = $categories;


        return Response()->json($result_array);
    }



    /**
     * get category information
     * @param Request $request
     */
    public function GetCategoryInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $category_id         = $request->input('category_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();



        $category_data = ProductCategories::find($category_id);


        $category_info_array = array();

        $category_info_array['pc_id']               = $category_data->pc_id;
        $category_info_array['title']               = $category_data->pc_category;
        $category_info_array['description']         = $category_data->pc_description;
        $category_info_array['show_on_pos']         = $category_data->pc_show_on_pos;

        $result_array['is_error']       = 0;
        $result_array['category_info_array']       = $category_info_array;


        return Response()->json($result_array);
    }

    public function ExportListProductsToExcel(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $category_id              = $request->input('category_id');
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


        $product_cond = Products::wherePProductIsDeleted(0);

        if ($category_id > 0) {
            $product_cond = $product_cond->whereFkPcId($category_id);
        }


        $lst_products = $product_cond->get();

        $data = array();
        $data[] = ['barcode', 'Product Title', 'Product Category', 'Quantity', 'Selling Price', 'Cost Price', 'Currency'];

        foreach ($lst_products as $product_info) {
            $data[] =  ["#" . $product_info->p_barcode . "#", $product_info->p_product_name, $product_info->Category ? $product_info->Category->pc_category : "-", $product_info->p_product_quantity, $product_info->p_product_selling_price, $product_info->p_product_cost_price, $product_info->Currency->cc_currency_code];
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertAll($data);

        $csv->output('products.csv');
    }


    /**
     * Save Category Information in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveCategoryInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
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


        // save category  information
        $pc_category    = $request->input("pc_category");
        $fk_pc_id       = $request->input("fk_pc_id") == NULL ? 0 :  $request->input("fk_pc_id");
        $pc_id          = $request->input("pc_id");
        $pc_show_on_pos = $request->input("pc_show_on_pos");

        if ($pc_id != null)
            $category_info = ProductCategories::find($pc_id);
        else
            $category_info = new ProductCategories();

        $category_info->fk_pc_id = $fk_pc_id;
        $category_info->pc_category = $pc_category;
        $category_info->pc_show_on_pos = $pc_show_on_pos;
        $category_info->save();

        $result_array['is_error']        = 0;
        $result_array['error_msg']       = "Operation Complete Successfully";


        return Response()->json($result_array);
    }



    /**
     * Get Lst of all products based on parameters send to the function
     * Parameters maybe sent:
     * category_id : product category
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetListProducts(Request $request)
    {
        $user_id                        = $request->input('user_id');
        $category_id                    = $request->input('category_id');
        $searchquery                    = $request->input('searchquery');
        $g_hash                         = $request->input('g_hash');
        $current_page                   = $request->input('current_page');
        $has_pagination                 = $request->has('has_pagination') ? $request->input('has_pagination') : 1;


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


        $products = array();

        $products_cond = Products::wherePProductIsDeleted(0);
        if ($category_id != 0) {
            $products_cond = $products_cond->whereFkPcId($category_id);
        }

        if (strlen($searchquery) > 0) {
            $products_cond = $products_cond->where('p_product_name', 'LIKE', '%' . $searchquery . '%');
            $products_cond = $products_cond->orWhere('p_barcode', 'LIKE', '%' . $searchquery . '%');
        }

        $products_count = $products_cond->count();

        $total_pages = ceil($products_count / $nbr_rows_per_pages);
        $total_pages = intval($total_pages);

        if ($has_pagination == 1)
            $lst_products = $products_cond->skip($skip)->take($nbr_rows_per_pages)->get();
        else
            $lst_products = $products_cond->get();

        foreach ($lst_products as $key => $product_info) {
            $products[$product_info->p_id]['p_id']                        = $product_info->p_id;
            $products[$product_info->p_id]['reference']                   = $product_info->p_product_ref;
            $products[$product_info->p_id]['p_barcode']                   = $product_info->p_barcode;
            $products[$product_info->p_id]['p_barcode_img']               = $product_info->p_barcode_img;
            $products[$product_info->p_id]['p_product_name']              = $product_info->p_product_name;
            $products[$product_info->p_id]['p_product_selling_price']     = $product_info->p_product_selling_price;
            $products[$product_info->p_id]['p_product_cost_price']        = $product_info->p_product_cost_price;
            $products[$product_info->p_id]['p_product_tax_rate']          = $product_info->p_product_tax_rate;
            $products[$product_info->p_id]['currency_code']               = $product_info->Currency->cc_currency_code;
            $products[$product_info->p_id]['currency']                    = $product_info->p_product_currency;
            $products[$product_info->p_id]['stock_alert']                 = $product_info->p_product_stock_alert;

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            if (strlen($product_info->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }

            $products[$product_info->p_id]['product_avatar']   = $img_src;
        }

        $result_array['is_error']           = 0;
        $result_array['products']           = $products;
        $result_array['total_pages']        = $total_pages;


        return Response()->json($result_array);
    }


    /**
     * Delete Category Info Saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteCategoryInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $category_id         = $request->input('category_id');
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

        $category_res = ProductCategories::find($category_id)->delete();

        $result_array['is_error']       = 0;
        $result_array['error_msg']       = "Delete Product Category Completed Successfully";


        return Response()->json($result_array);
    }



    public function DeleteProductInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $product_id         = $request->input('product_id');
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

        $product_res = Products::find($product_id)->delete();

        $result_array['is_error']       = 0;
        $result_array['error_msg']       = "Delete Product Completed Successfully";


        return Response()->json($result_array);
    }


    /**
     * get product info for the {product_id} and return all information of the product
     *
     *  @author Moe Mantach
     *  @access public
     *  @param Request $request
     *  @return array $product_array
     *  $product_array['reference']
     *  $product_array['p_barcode']
     *  $product_array['p_barcode_img']
     *  $product_array['p_product_name']
     *  $product_array['p_product_selling_price']
     *  $product_array['p_product_tax_rate']
     *  $product_array['currency_code']
     *  $product_array['currency']
     */
    public function GetProductInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $product_id         = $request->input('product_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $product_array       = array();

        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $product_info = Products::find($product_id);

        $product_array['reference']                   = $product_info->p_product_ref;
        $product_array['p_barcode']                   = $product_info->p_barcode;
        $product_array['p_barcode_img']               = $product_info->p_barcode_img;
        $product_array['p_product_name']              = $product_info->p_product_name;
        $product_array['category_id']                 = $product_info->fk_pc_id;
        $product_array['category_name']               = $product_info->Category->pc_category;
        $product_array['p_product_selling_price']     = $product_info->p_product_selling_price;
        $product_array['p_product_cost_price']     = $product_info->p_product_cost_price;


        $product_array['p_product_tax_rate']          = $product_info->p_product_tax_rate;
        $product_array['currency_code']               = $product_info->Currency->cc_currency_code;
        $product_array['currency']                    = $product_info->p_product_currency;

        $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        if (strlen($product_info->p_product_profile_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }

        $product_array['product_avatar']   = $img_src;


        $result_array['is_error']       = 0;
        $result_array['product_info']   = $product_array;

        unset($product_array);
        $product_array = null;

        return Response()->json($result_array);
    }

    /**
     * get stock information for every product registered in the
     * software database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetProductsStock(Request $request)
    {
        $user_id                = $request->input('user_id');
        $category_id            = $request->input('category_id');
        $warehouse_id           = $request->input('warehouse_id');
        $product_barcode        = $request->input('product_barcode');
        $product_name           = $request->input('product_name');
        $g_hash                 = $request->input('g_hash');
        $user_info              = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $stock_data          = array();
        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $lst_stocks = Stocks::whereIsIsDeleted(0)->whereFkWarehouseId($warehouse_id);
        if ($product_barcode != '')
            $lst_stocks = $lst_stocks->where('is_stock_uid', 'LIKE', "%$product_barcode%");
        if ($product_name != '')
            $lst_stocks = $lst_stocks->where('is_stock_label', 'LIKE', "%$product_name%");


        $lst_stocks = $lst_stocks->get();

        // get list of currencies
        $lst_currencies     = Currency::all();
        $currencies_array   = CreateDatabaseArrayByIndex($lst_currencies, 'cc_id');

        $total_stock_price = 0;
        $stock_currency = "";

        foreach ($lst_stocks as $key => $stock_info) {

            $product_info = $stock_info->products;

            if ($product_info->fk_pc_id !=  $category_id && $category_id > 0) {
                continue;
            }

            $barcode_obj = new DNS1D();
            $uid_bar_code_png = $barcode_obj->getBarcodePNG($stock_info->is_stock_uid, "C39+", 150, 50);

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            if (strlen($product_info->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }

            $stock_currency = $currencies_array[$stock_info->is_stock_currency]['cc_currency_code'];

            $stock_data[$stock_info->is_stock_uid]['id']                        = $product_info->p_id;
            $stock_data[$stock_info->is_stock_uid]['uid']                       = $stock_info->is_stock_uid;
            $stock_data[$stock_info->is_stock_uid]['product_reference']         = $product_info->p_product_ref;
            $stock_data[$stock_info->is_stock_uid]['product_name']              = $product_info->p_product_name;
            $stock_data[$stock_info->is_stock_uid]['product_image']             = $img_src;
            $stock_data[$stock_info->is_stock_uid]['stock_barcode_img']             = $uid_bar_code_png;
            $stock_data[$stock_info->is_stock_uid]['stock_quantity']            = $stock_info->is_quanity;
            $stock_data[$stock_info->is_stock_uid]['price_item']                = $stock_info->is_price_item;
            $stock_data[$stock_info->is_stock_uid]['price_stock']               = $stock_info->is_price_stock;
            $stock_data[$stock_info->is_stock_uid]['product_currency']          = $stock_currency;
            $stock_data[$stock_info->is_stock_uid]['stock_currency']            = $stock_currency;
            $stock_data[$stock_info->is_stock_uid]['stock_exchange_rate']       = $stock_info->is_stock_exchange_rate;

            $total_stock_price = $total_stock_price +  $stock_info->is_price_stock;
        }


        $result_array['is_error']               = 0;
        $result_array['stock_data']             = $stock_data;
        $result_array['total_stock_price']      = $total_stock_price;
        $result_array['stock_currency']         = $stock_currency;

        $stock_data = null;
        unset($stock_data);

        return Response()->json($result_array);
    }

    /**\
     * get from UID in the stock the product information of it
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SearchProductByUID(Request $request)
    {
        $product_uid         = $request->input('product_uid');
        $warehouse_id        = $request->input('warehouse_id');
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $stock_data          = array();
        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $stock_info = Stocks::whereIsStockUid($product_uid)->whereFkWarehouseId($warehouse_id)->first();

        $count_stock =  Stocks::whereIsStockUid($product_uid)->whereFkWarehouseId($warehouse_id)->count();

        if ($count_stock == 0) {
            $product_info = Products::wherePProductIsDeleted(0)
                ->where("p_barcode", $product_uid)
                ->first();

            if (!$product_info) {
                return response()->json([
                    'is_error' => 1,
                    'error_message' => 'Product Not Exist in Our Stock',
                ]);
            }
        } else {
            $product_info = $stock_info->products;
            $barcode_obj = new DNS1D();
            $uid_bar_code_png = $barcode_obj->getBarcodePNG($stock_info->is_stock_uid, "C39+", 150, 50);
            $stock_data['uid_bar_code_png']             = $uid_bar_code_png;
            $stock_data['uid_bar_code_png']             = $stock_info->is_selling_price;
        }




        $stock_data['product_id']                   = $product_info->p_id;
        $stock_data['product_name']                 = $product_info->p_product_name;
        $stock_data['barecode']                     = $product_info->p_barcode;
        $stock_data['selling_price']                = $product_info->p_product_selling_price;
        $stock_data['product_currency']             = $product_info->p_product_currency;


        $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
        if (strlen($product_info->p_product_profile_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }


        $result_array['is_error']       = 0;
        $result_array['stock_data']   = $stock_data;

        $stock_data = null;
        unset($stock_data);

        return Response()->json($result_array);
    }

    /**
     * Save Product record and a stock record in the database and notifie the admin user
     * of ERP that he need to complete all informasstion
     * @param Request $request
     */
    public function SaveProductInfo(Request $request)
    {

        $warehouse_id               = $request->input('warehouse_id');
        $company_currency           = $request->input('company_currency');
        $g_hash                     = $request->input('g_hash');
        $user_id                    = $request->input('user_id');
        $p_bar_code                 = $request->input('p_bar_code');
        $p_product_name             = $request->input('p_product_name');
        $fk_pc_id                   = $request->input('fk_pc_id');
        $p_product_price            = $request->input('p_product_price');
        $p_product_cost             = $request->input('p_product_cost');
        $p_product_quantity         = $request->input('p_product_quantity');
        $p_product_color            = $request->input('p_product_color');
        $product_sales_account      = $request->input('product_sales_account');
        $product_purchase_account   = $request->input('product_purchase_account');
        $product_id                 = $request->input('product_id');


        // save Product information
        if ($product_id == null)
            $product_data = new Products();
        else
            $product_data = Products::find($product_id);

        $product_data->fk_pc_id                     = $fk_pc_id;
        $product_data->p_barcode                    = $p_bar_code;
        $product_data->p_barcode_img                = NULL;
        $product_data->p_product_ref                = $p_bar_code;
        $product_data->p_product_name               = $p_product_name;
        $product_data->p_product_description        = $p_product_name;
        $product_data->p_product_stock_alert        = 1;
        $product_data->p_product_color              = $p_product_color;
        $product_data->p_product_selling_price      = $p_product_price;
        $product_data->p_product_min_selling_price  = $p_product_cost;
        $product_data->p_product_cost_price         = $p_product_cost;
        $product_data->p_product_currency           = $company_currency;
        $product_data->p_sale_accounting_code       = $product_sales_account;
        $product_data->p_purchase_accounting_code   = $product_purchase_account;
        $product_data->save();
        $p_id = $product_data->p_id;
        $creation_date = date('Y-m-d');

        $category_info = ProductCategories::find($fk_pc_id);

        $pc_use_serial_number = $category_info->pc_use_serial_number;
        if ($pc_use_serial_number == 0) {
            $stock = new Stocks();
            $stock->fk_warehouse_id                 = $warehouse_id;
            $stock->fk_product_id                   = $p_id;
            $stock->is_stock_label                  = "Stock Entry For " . $p_product_name . " On " . $creation_date;
            $stock->is_stock_lot_person_in_charge   = $user_id;
            $stock->is_created_by                   = $user_id;
            $stock->is_quanity                      = $p_product_quantity;
            $stock->is_creation_date                = $creation_date;
            $stock->is_price_stock                  = $p_product_price * $p_product_quantity;
            $stock->is_selling_price                = $p_product_price;
            $stock->is_wholesale_price              = $p_product_price;
            $stock->is_vendor_price                 = $p_product_price;
            $stock->is_price_item                   = $p_product_price;
            $stock->is_price_currency               = $company_currency;
            $stock->is_stock_currency               = $company_currency;
            $stock->is_stock_exchange_rate          = 1;
            $stock->is_stock_uid    = $p_bar_code;
            $is_id = $stock->save();

            // save accounting records
            //             $transaction = new Transactions();
            //             $transaction->at_transaction_date   = $creation_date;
            //             $transaction->at_creation_date      = $creation_date;
            //             $transaction->fk_acc_journal_id     = 3;
            //             $transaction->at_accounting_doc     = "Accounting Record Stock Entry For " . $p_product_name . " On " . $creation_date;
            //             $transaction->at_currency_id        = $company_currency;
            //             $transaction->save();
            //             $at_id = $transaction->at_id;


            //             $movement = new TransactionMovements();
            //             $movement->fk_tran_id = $at_id;
            //             $movement->tm_ledger_account    = $product_sales_account;
            //             $movement->tm_sub_ledger_account= $product_purchase_account;
            //             $movement->tm_ledger_label= "Accounting Record Stock Entry For " . $p_product_name . " On " . $creation_date;
            //             $movement->tm_debit = $p_product_price;
            //             $movement->tm_credit= 0;
            //             $movement->tm_creation_date = $creation_date;
            //             $movement->tm_currency_id   = $company_currency;
            //             $movement->save();
            //             $mov_id = $movement->tm_id;
            //
            //             // save data into the stock info
            //             $stock->is_trans_id = $at_id;
            //             $stock->is_mov_id   = $mov_id;
            $stock->save();
        }


        $result_array['is_error']       = 0;
        $result_array['error_message']   = "Product Has Been Added";


        return Response()->json($result_array);
    }

    /**
     * based on sending barcode sequance we generate barcode image
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateBarCode(Request $request)
    {
        $product_barcode     = $request->input('product_barcode');
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
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

        $barcode_obj = new DNS1D();
        $uid_bar_code_png = $barcode_obj->getBarcodePNG($product_barcode, "C39+", 150, 50);
        $result_array['bar_code_img']               = $uid_bar_code_png;
        $result_array['is_error']                   = 0;


        return Response()->json($result_array);
    }


    /**
     * Search Product by barcode of stock to get information and save it in the record of POS
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response
     */
    public function SearchProductById(Request $request)
    {
        $product_barcode     = $request->input('pos_barcode');
        $user_id             = $request->input('user_id');
        $warehouse_id        = $request->input('warehouse_id');
        $pos_quantity        = $request->input('pos_quantity');
        $company_currency    = $request->input('company_currency');
        $sec_company_currency = $request->input('sec_company_currency');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $row_array           = array();
        $stock_id = 0;


        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $product_data = Products::wherePProductIsDeleted(0)->where('p_barcode', 'LIKE', '%' . $product_barcode . '%')->get();
        $wproduct_data = Products::wherePProductIsDeleted(0)->where('p_barcode', 'LIKE', '20%')->get();

        if (count($product_data) > 0) {
            $result_array['is_error'] = 0;
            $row_array['p_id'] = $product_data[0]->p_id;
            $result_array['quantity'] = 1;
            $row_array['is_id'] = 0;
            $row_array['uid'] = $product_data[0]->p_barcode;
            $row_array['product_name'] = $product_data[0]->p_product_name;

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_data[0]->p_product_profile_base_src . $product_data[0]->p_product_profile_file_name . "." . $product_data[0]->p_product_profile_extention;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_data[0]->p_product_profile_base_src . $product_data[0]->p_product_profile_file_name . "." . $product_data[0]->p_product_profile_extention;
            if (strlen($product_data[0]->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }
            $row_array['image_url'] = $img_src;
            $row_array['product_cost'] = $product_data[0]->p_product_selling_price;

            $row_array['product_discount'] = 0;
            $row_array['product_quantity'] = 1;
            $result_array['row_array'] = $row_array;
            $total_cost_row =  $product_data[0]->p_product_selling_price * 1;

            $result_array['total_cost_row'] = $total_cost_row;
            return Response()->json($result_array);
        } else {
            $barcode = substr($product_barcode, 0, 7);
            $price = substr($product_barcode, 7, 2);
            $comma = substr($product_barcode, 9, 3);
            $weight = round(floatval($price . "." . $comma), 2);
            $product_barcode_data = Products::wherePProductIsDeleted(0)->where('p_barcode', 'LIKE', '%' . $barcode . '%')->get();
            if (count($product_barcode_data) > 0) {
                $price_killo = floatval($product_barcode_data[0]->p_product_selling_price);
                $qyt_price = floatval($price_killo) * $weight;

                $result_array['is_error'] = 0;
                $row_array['p_id'] = $product_barcode_data[0]->p_id;
                $result_array['quantity'] = $weight;
                $row_array['is_id'] = 0;
                $row_array['uid'] = $barcode;
                $row_array['product_name'] = $product_barcode_data[0]->p_product_name . " x " . $weight . " Kg";

                $row_array['image_url'] = url('images/NoImageAvailable.jpg');
                $row_array['product_cost'] = $price_killo;

                $row_array['product_discount'] = 0;
                $row_array['product_quantity'] = $weight;
                $result_array['row_array'] = $row_array;
                $total_cost_row =  $qyt_price;
                //$total_cost_row =  $price_killo * $weight;

                $result_array['total_cost_row'] = $total_cost_row;
                return Response()->json($result_array);
            }
        }

        // first check we check if the barcode exist in the stock_ids table
        $stock_ids = StockIds::whereSiStockUid($product_barcode)->get();


        $stock_uid = "";
        if (count($stock_ids) > 0) {

            if ($stock_ids[0]->si_stock_sold == 0) {
                $stock_id   = $stock_ids[0]->fk_stock_id;
                $stock_uid  = $stock_ids[0]->si_stock_uid;
            } else {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'This item is Already Sold Please Get Information about it from GET INFO Section';

                return Response()->json($result_array);
            }
        } else {
            $stock_info     = Stocks::whereIsStockUid($product_barcode)->whereFkWarehouseId($warehouse_id)->get();

            if (count($stock_info) == 0) {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'We dont have any stock now from this item !!';

                return Response()->json($result_array);
            }

            if (count($stock_info) > 1) {
                for ($i = 0; $i < count($stock_info); $i++) {
                    if ($stock_id > 0)
                        continue;
                    $stock_info_tmp = $stock_info[$i];
                    if ($pos_quantity > $stock_info_tmp->is_quanity) {
                        $result_array['is_error']       = 1;
                        $result_array['quantity']       = $stock_info_tmp->is_quanity;
                        $result_array['error_message']  = 'Quantity Not enough For this Product !!';

                        return Response()->json($result_array);
                    } else if ($pos_quantity <= $stock_info_tmp->is_quanity) {
                        $stock_id = $stock_info_tmp->is_id;
                        $stock_uid  = $stock_info_tmp->is_stock_uid;
                    }
                }
            } else if (count($stock_info) == 1) {
                $stock_info = $stock_info[0];
                if ($pos_quantity > $stock_info->is_quanity) {
                    $result_array['is_error']       = 1;
                    $result_array['quantity']       = $stock_info->is_quanity;
                    $result_array['error_message']  = 'Quantity Not enough For this Product !!';

                    return Response()->json($result_array);
                }

                $stock_id = $stock_info->is_id;
                $stock_uid  = $stock_info->is_stock_uid;
            } else {
                $result_array['is_error']       = 1;
                $result_array['error_message']  = 'We dont have any stock now from this item !!';

                return Response()->json($result_array);
            }
        }


        $stock_info = Stocks::find($stock_id);



        $row_array['p_id'] = $stock_info->products->p_id;
        $row_array['is_id'] = $stock_info->is_id;
        $row_array['uid'] = $stock_uid;
        $row_array['product_name'] = $stock_info->products->p_product_name;

        $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $stock_info->products->p_product_profile_base_src . $stock_info->products->p_product_profile_file_name . "." . $stock_info->products->p_product_profile_extention;
        $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $stock_info->products->p_product_profile_base_src . $stock_info->products->p_product_profile_file_name . "." . $stock_info->products->p_product_profile_extention;
        if (strlen($stock_info->products->p_product_profile_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }
        $row_array['image_url'] = $img_src;

        $price_item         = $stock_info->is_price_item;

        $stock_currency     = $stock_info->is_stock_currency;
        $exchange_rate      = 0;
        $op_product_cost    = 0;
        // if currrency id are differant
        if ($stock_currency != $company_currency) {
            $today_date = date("Y-m-d");
            $currency_exchange = CurrencyExchangeRates::whereErFromCurrency($stock_currency)->whereErToCurrency($company_currency)->get();
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



        $row_array['product_cost']          = $op_product_cost;
        //  $row_array['sec_cur_product_cost']  = $sec_cur_product_cost;
        $row_array['product_quantity']      = $pos_quantity;


        $total_cost_row = $op_product_cost * $pos_quantity;

        $result_array['is_error']        = 0;
        $result_array['row_array']       = $row_array;
        $result_array['total_cost_row']  = $total_cost_row;

        return Response()->json($result_array);
    }


    /**
     * get list of products and put it in autocomplete
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ListSearchProducts(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $products_array      = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $lst_products = Products::wherePProductIsDeleted(0)->get();
        foreach ($lst_products as $key => $product_info) {
            $products_array[] = $product_info->p_product_name;
        }

        $result_array['is_error']           = 0;
        $result_array['products_array']     = $products_array;

        return Response()->json($result_array);
    }


    public function AddProductToOrder(Request $request)
    {
        $user_id            = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $selectedproduct     = $request->input('selectedproduct');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $product_data = Products::wherePProductName($selectedproduct)->get();

        if (count($product_data) == 0) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "Product Does not exist";
            return Response()->json($result_array);
        }
        $product_data = $product_data[0];
        $result_array['is_error'] = 0;
        $row_array['p_id'] = $product_data->p_id;
        $result_array['quantity'] = 1;
        $row_array['is_id'] = $product_data->p_id;
        $row_array['uid'] = $product_data->p_id;
        $row_array['product_name'] = $product_data->p_product_name;

        $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_data->p_product_profile_base_src . $product_data->p_product_profile_file_name . "." . $product_data->p_product_profile_extention;
        $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_data->p_product_profile_base_src . $product_data->p_product_profile_file_name . "." . $product_data->p_product_profile_extention;
        if (strlen($product_data->p_product_profile_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }
        $row_array['image_url'] = $img_src;
        $row_array['product_cost'] = $product_data->p_product_selling_price;
        $row_array['cost_price'] = $product_data->p_product_cost_price;
        $row_array['product_quantity'] = 1;
        $row_array['product_discount'] = 0;
        $result_array['row_array'] = $row_array;
        $total_cost_row =  $product_data->p_product_selling_price * 1;

        $result_array['total_cost_row'] = $total_cost_row;
        return Response()->json($result_array);
    }


    /**
     * get list product categories based on selected category
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return array $result_array
     * $result_array['category_array'] array $category_array
     * $result_array['items_array'] array $items_array
     */
    public function GetProductCategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $user_id             = $request->input('user_id');
        $warehouse_id        = $request->input('warehouse_id');
        $display_type        = $request->input('display_type');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $row_array           = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $lst_categories = ProductCategories::wherePcIsDeleted(0);
        if (is_numeric($category_id) && $category_id != 0)
            $lst_categories = $lst_categories->whereFkPcId($category_id);
        if ($display_type  != 'list')
            $lst_categories = $lst_categories->wherePcShowOnPos(1);
        $lst_categories = $lst_categories->get();
        $category_array = array();
        $items_array    = array();

        $index = 0;
        foreach ($lst_categories as $key => $category_info) {
            $category_array[$index]['id'] = $category_info->pc_id;
            $category_array[$index]['category'] = $category_info->pc_category;

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $category_info->pc_avatar_base_src . $category_info->pc_avatar_file_name . "." . $category_info->pc_avatar_extension;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $category_info->pc_avatar_base_src . $category_info->pc_avatar_file_name . "." . $category_info->pc_avatar_extension;

            if (strlen($category_info->pc_avatar_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }


            $category_array[$index]['image'] = $img_src;
            $index++;
        }

        $data = array(
            'category_array' => $category_array
        );
        $category_view = view('pos.lstcategories', $data)->render();


        $lst_products = Products::wherePProductIsDeleted(0)->whereFkPcId($category_id)->get();
        $index = 0;
        foreach ($lst_products as $key => $product_info) {
            $items_array[$index]['id']              = $product_info->p_id;
            $items_array[$index]['product_name']    = $product_info->p_product_name;

            $image_src_url  = url('/') . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            $image_src_path = public_path() . "/" . Config::get('constants.PRODUCTS_PATH') . $product_info->p_product_profile_base_src . $product_info->p_product_profile_file_name . "." . $product_info->p_product_profile_extention;
            if (strlen($product_info->p_product_profile_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }

            $items_array[$index]['product_image']    = $img_src;
            $items_array[$index]['product_price']    = $product_info->p_product_selling_price;
            $index++;
        }

        $result_array = array();

        $result_array['is_error']           = 0;
        $result_array['category_array']     = $category_array;
        $result_array['items_array']        = $items_array;
        $result_array['category_view']        = $category_view;

        return Response()->json($result_array);
    }

    /**
     * get product information and all stock related information
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function GetProductStockinfo(Request $request)
    {
        $warehouse_id   = $request->input('warehouse_id');
        $product_barcode = $request->input('product_barcode');
        $response_array = array();

        $stock_info = Stocks::where('is_stock_uid', '=', $product_barcode)->get();
        if (count($stock_info) > 0)
            $stock_info = $stock_info[0];
        $product_id = 0;
        $supplier_id = 0;
        $order_id = 0;
        $order_date = "";
        $order_barcode = "";

        $order_ids = StockIds::whereSiStockUid($product_barcode)->get();

        if (count($order_ids) > 0) {
            $stock_id = $order_ids[0]->si_stock_id;
            $stock_info = Stocks::find($stock_id);
        }

        if (isset($stock_info->is_id)) {
            $product_id             = $stock_info->fk_product_id;
            $supplier_id            = $stock_info->is_supplier_id;

            // check if the product is sold we search info about order
            $order_product = OrderProducts::whereSoStockSerialNumber($product_barcode)->get();

            if (count($order_product) > 0) {
                $order_id = $order_product[0]->fk_order_id;
                $order_info = Orders::find($order_id);
                $order_date = $order_info->so_creation_date;
                $order_barcode = $order_info->so_order_code;
            }
        }



        $stock_insertion_date   = $stock_info->is_creation_date;
        $product_info = Products::find($product_id);
        $supplier_info = Suppliers::find($supplier_id);

        $data = array(
            'order_date' => $order_date,
            'order_barcode' => $order_barcode,
            'product_info' => $product_info,
            'stock_info' => $stock_info,
            'supplier_info' => $supplier_info,
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('stocks.stockinfo', $data)->render();
        return Response()->json($response_array);
    }

    public function GetListRawMaterials(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $raw_material = Products::wherePProductIsDeleted(0)->get();
        $materials_array = array();

        foreach ($raw_material as $index => $raw_material_info) {
            $materials_array[$index]['p_id'] = $raw_material_info->p_id;
            $materials_array[$index]['p_product_name'] = $raw_material_info->p_product_name;
            $materials_array[$index]['p_product_description'] = $raw_material_info->p_product_description;
        }
        $result_array['is_error'] = 0;
        $result_array['raw_materials'] = $materials_array;

        return Response()->json($result_array);
    }

    public function ValidateStock(Request $request)
    {

        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $store_id           = $request->input('store_id');
        $product_id         = $request->input('product_id');
        $quantity_requested = $request->input('quantity_requested');

        $storeWarehouse = StoreWarehouses::where('sw_store_id', $store_id)
            ->where('sw_is_deleted', 0)
            ->first(['sw_warehouse_id']);


        $warehouse_id = $storeWarehouse->sw_warehouse_id;

        $total_quantity = Stocks::where('fk_product_id', $product_id)
            ->where('fk_warehouse_id', $warehouse_id)
            ->where('is_is_deleted', 0)
            ->sum('is_quanity');

        if ($total_quantity < $quantity_requested) {

            return Response()->json([
                'is_error'        => 1,
                'validated'       => 0,
                'available_stock' => $total_quantity,
                'message'         => 'Not enough stock in warehouse',
            ]);
        }

        return Response()->json([
            'is_error'        => 0,
            'validated'       => 1,
            'available_stock' => $total_quantity,
            'message'         => 'Stock available',
        ]);
    }
}
