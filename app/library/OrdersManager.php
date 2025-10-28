<?php
/***********************************************************
OrdersManager.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/






namespace App\library;

use Validator;
use Input;
use Symfony\Component\Console\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMAccounts;
use App\models\System\Companies;
use App\models\Sales\Orders;
use App\models\System\Currency;
use App\models\Inventory\Stocks;


class OrdersManager
{

    /**
     * Generate order code to save in the order record
     * @return string
     */
    public function GenerateOrdereCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_orders = Orders::whereYear('so_creation_date' , $year)->count();

        $index = $count_orders + 1;


        $invoice_code = "ORD" . sprintf('%04d', $index);

        return $invoice_code;

    }

    /**
     * Generate POS Order Code
     *
     * @author Moe Mantach
     * @param array $params_array
     * @return string pos_code
     */
    public function GeneratePOSOrderCode($params_array = array() )
    {
        $company_id     = $params_array['company_id'];
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_orders = Orders::whereYear('so_creation_date' , $year)->count();

        $index = $count_orders + 1;


        $order_code = sprintf('%04d', $index);

        return $order_code;

    }


    /**
     * Generate array to display in klist of order products based on info saved
     * in the order record and products already exist
     *
     * @author Moe Mantach
     * @access public
     * @param object $order_info
     * @param object $lst_products
     */
    public function GenerateOrderProducts( $order_info , $lst_products )
    {

        $cc_currency_code = $order_info->Currency->cc_currency_code;

        $order_array = array();

        $order_currency = $order_info->so_order_currency;

        $lst_currency = Currency::all();
        $currency_array = CreateDatabaseArrayByIndex($lst_currency , 'cc_id');

        $index = 0;
        foreach ( $lst_products as $key => $product_info ) {

            $stock_id    = $product_info->so_stock_id;
            $product_id  = $product_info->fk_product_id;
            if($product_id > 0)
            {
                $order_array[$index]['product_id']               = $product_info->Products->p_id;
                $order_array[$index]['product_name']             = $product_info->Products->p_product_name;
                $order_array[$index]['barcode_img']              = $product_info->Products->p_barcode_img;
                $order_array[$index]['barcode']                  = $product_info->Products->p_barcode;
                $order_array[$index]['stock_id']                 = $stock_id;
            }
            else
            {
                $order_array[$index]['product_id']               = $product_info->ii_item_id;
                $order_array[$index]['product_name']             = $product_info->ii_item_label;
                $order_array[$index]['barcode_img']              = "";
                $order_array[$index]['barcode']                  = "";
            }

            $order_array[$index]['stock_uid']                = ( is_object( $product_info->stock ) ) ?  $product_info->stock->is_stock_uid : "";
            $order_array[$index]['quantity']                 = $product_info->so_product_quantity;
            $order_array[$index]['price_item']               = $product_info->so_product_cost;
            $order_array[$index]['price_stock']              = $product_info->so_product_cost * $product_info->so_product_quantity;
            $order_array[$index]['stock_currency']           = $product_info->so_product_currency;
            $order_array[$index]['stock_currency_code']      =  $product_info->Currency->cc_currency_code;


            $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_info->Products->p_product_profile_base_src.$product_info->Products->p_product_profile_file_name.".".$product_info->Products->p_product_profile_extention;
            $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_info->Products->p_product_profile_base_src.$product_info->Products->p_product_profile_file_name.".".$product_info->Products->p_product_profile_extention;
            if(strlen($product_info->Products->p_product_profile_base_src) > 0 ){
                $img_src = $image_src_url;
            }else{
                $img_src = url('images/NoImageAvailable.jpg');
            }
            $order_array[$index]['image_url']           = $img_src;

            $index++;
        }
        return $order_array;
    }

}
