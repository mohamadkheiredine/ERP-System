<?php
/***********************************************************
CacheController.php
Product :
Version : 1.0
Release : 1
Date Created : May 7, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/






namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input;
use Session;
use Config;
use Redirect;
use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\models\System\Appconfig;
use App\models\Restaurants\Restaurant;
use App\models\Restaurants\RestaurantCategories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\WareHouseVehicules;
use App\models\Logistics\Vehicules;
use App\models\CRM\CRMServices;
use App\models\Inventory\WareHouseZones;
use App\models\System\Units;
use App\models\Inventory\Products; 
use Illuminate\Support\Facades\Storage;



class CacheController extends Controller
{
    
    public function GenerateCache( $key , Request $request)
   {
       $result_array = array();
       
       
       switch($key)
       {
           case "products":
               {
                  
                   $lst_products = Products::wherePProductIsDeleted(0)->get();
                    
                   $products = array();
                   $index = 0;
                   foreach ( $lst_products as $key => $product_info ) 
                   {
                       $products[$index]['id']                  = $product_info->p_id;
                       $products[$index]['product_name']        = $product_info->p_product_name;
                       $products[$index]['min_selling_price']   = $product_info->p_product_min_selling_price;
                       $products[$index]['selling_price']       = $product_info->p_product_selling_price;
                       $products[$index]['currency']            = $product_info->p_product_currency;
                       $products[$index]['barcode']             = $product_info->p_barcode;
                       $products[$index]['product_use_serial']  = $product_info->Category->pc_use_serial_number;
                       $index++;
                   }
                   
                   $json_data = array();
                   
                   $json_data['message'] = "";
                   $json_data['value'] = $products;
                   
                   // save cache info
                   $path  = public_path() . "/cache/products/";
                   
                   if(!is_dir($path)){
                       //Directory does not exist, so lets create it.
                       @mkdir($path, 0755, true);
                   }
                    
                   
                   $cache_path = $path . "products.json";
                   $cache_url = url('cache/products/products.json');
                   
                   
                   
                   $fp = fopen($cache_path, "w+");
                   fwrite($fp, json_encode($json_data));
                   fclose($fp);
                   
                   
                   $result_array['products'] = $products;
                   $result_array['cache_url'] = $cache_url;
                   
               }
           break;
       }
       
       
       return Response()->json($result_array);
       
   }
}