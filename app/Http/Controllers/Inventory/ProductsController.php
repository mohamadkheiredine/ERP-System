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
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\WareHouses;
use App\models\Inventory\ProductCategories;
use Milon\Barcode\DNS1D;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\Library\ProductManager;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use Swap\Swap;
use App\models\System\Units;
use App\models\Inventory\WareHouseZones;
use App\models\Inventory\WareHouseFloors;
use League\Csv\Writer;
use App\models\SRM\Suppliers;



class ProductsController extends Controller
{

   /**
    * Display page of Products Management
    *
    * @author Moe Mantach
    * @access public
    */
      public function index()
      {
          
          $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->get();
          $lst_currencies = Currency::all();
          
          $data = array(
              "lst_product_categories" => $lst_product_categories,
              "lst_currencies" => $lst_currencies,
          );
          return Response()->view('products.products',$data);
      }


      /**
       * Display Add New Product Page Based
       *
       * @author Moe Mantach
       * @access public
       */
      public function AddNewProduct()
      {
          
          $lst_product_categories_array = ProductCategories::wherePcIsDeleted(0)->orderBy('fk_pc_id', 'desc')->get();
          $rand_barcode                 = rand(10000000,99999999999);
          $bar_code_png                 = DNS1D::getBarcodePNG($rand_barcode , "C39+",150 , 50 );
          $lst_lot                      = ProductLots::whereLLotIsDeleted(0)->get();
          $lst_accounts                 = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
          $lst_taxes                    = VatAccounts::whereAvIsDeleted(0)->get();
          $lst_currencies               = Currency::all();
          $lst_suppliers               = Suppliers::whereSsIsDeleted(0)->get();
          
          $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
          
          $data = array(
              "lst_product_categories_array" => $lst_product_categories_array,
              "rand_barcode" => $rand_barcode,
              "lst_taxes" => $lst_taxes,
              "lst_warehouses" => $lst_warehouses,
              "lst_accounts" => $lst_accounts,
              "lst_currencies" => $lst_currencies,
              "lst_lot" => $lst_lot,
              "lst_suppliers" => $lst_suppliers,
              "bar_code_png" => $bar_code_png
          );
          return Response()->view('products.addnewproduct',$data);
      }
      
      
      /**
       * Page of Edit Product
       * 
       * @author Moe Mantach
       * @access public
       * @param integer $pp_id
       * @return unknown
       */
      public function EditProduct($pp_id)
      {
          $lst_product_categories_array = ProductCategories::wherePcIsDeleted(0)->get();
          $product_info                 = Products::find($pp_id);
          $lst_lot                      = ProductLots::whereLLotIsDeleted(0)->get();
          $lst_accounts                 = ChartAccounts::whereAaIsDeleted(0)->orderBy('aa_account', 'asc')->orderBy('aa_sub_account', 'asc')->get();
          $lst_taxes                    = VatAccounts::whereAvIsDeleted(0)->get();
          $system_currencies            = Currency::all();
          $currency_array               = CreateDatabaseArrayByIndex($system_currencies, "cc_id");
          $rand_barcode                 = $product_info->p_barcode;
          $bar_code_png                 = DNS1D::getBarcodePNG($rand_barcode , "C39+",150 , 50 );
          $lst_currencies               = Currency::all();
          $lst_suppliers               = Suppliers::whereSsIsDeleted(0)->get();
          $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
          
          $data = array(
              "lst_product_categories_array" => $lst_product_categories_array,
              "product_info" => $product_info,
              "lst_accounts" => $lst_accounts,
              "currency_array" => $currency_array,
              "lst_taxes" => $lst_taxes,
              "lst_currencies" => $lst_currencies,
              "rand_barcode" => $rand_barcode,
              "bar_code_png" => $bar_code_png,
              "lst_suppliers" => $lst_suppliers,
              "lst_warehouses" => $lst_warehouses,
              "lst_lot" => $lst_lot
          );
          return Response()->view('products.editproduct',$data);
          
      }
      
      
      /**
       * Generate Barecode for product
       * @param Request $request
       */
      public function GenerateBarCode( Request $request )
      {
          $p_barcode    = $request->input('p_barcode');
          $bar_code_png = DNS1D::getBarcodePNG($p_barcode, "C39+",150 , 50 );
          $result_array = array();
          
          $result_array['bar_code_png'] = $bar_code_png;
          $result_array['p_barcode']    = $p_barcode;
          
          return Response()->json($result_array);
          
      }
      
      
      /**
       * Duplicate product and save as new record to the database
       * 
       * @author Moe Mantach
       * @access public
       * 
       * @param Request $request
       * $request->input('p_ids') array list of selected ids 
       */
      public function Duplicateproducts( Request $request )
      {
        $p_ids = $request->input('p_ids');
        $result_array = array();
        
        foreach ($p_ids as $key => $p_id) 
        {
            $product_info   = Products::find($p_id);
            $product        = $product_info->replicate();
            $product->p_product_name = "Copy of " . $product->p_product_name;
            $product->save();
            
            unset($product_info);
            unset($product);
        }
        
        $result_array['is_error'] = 0;
        
        return Response()->json($result_array);
      }
      
      
      /**
       * get zones by selected warehouse
       * 
       * @author Moe Mantach
       * @access public
       * @param Request $request
       */
      public function GetZonesDropdown(Request $request)
      {
          $result_array = array();
          $warehouse_id = $request->input("warehouse_id");
          
          $lst_zones = WareHouseZones::whereWzIsDeleted(0)->whereFkWarehouseId($warehouse_id)->get();
          
          $data = array(
              'lst_zones' => $lst_zones
          );
          
          $result_array['is_error'] = 0;
          $result_array['dropdown'] = view('warehouses.zonesdropdown',$data)->render();
          
          
          return Response()->json($result_array);
      }
      
      
      /**
       * get Floors by selected zone
       * 
       * @author Moe Mantach
       * @access public
       * @param Request $request
       */
      public function GetFloorsDropdown(Request $request)
      {
          $result_array = array();
          $zone_id = $request->input("zone_id");
          
          $lst_floors = WareHouseFloors::whereWfIsDeleted(0)->whereFkZoneId($zone_id)->get();
          
          $data = array(
              'lst_floors' => $lst_floors
          );
          
          $result_array['is_error'] = 0;
          $result_array['dropdown'] = view('warehouses.floorsdropdown',$data)->render();
          
          
          return Response()->json($result_array);
      }
      
      
      
      /**
       * get list of all products from a view
       * 
       * @author Moe Mantach
       * @access public
       * @param Request $request
       */
      public function DisplayList(Request $request)
      {
          
          $page_number                  = $request->input('page_number');
          $general_search               = $request->input('general_search');
          $product_category             = $request->input('product_category');
          $product_currency             = $request->input('product_currency');
          $nbr_rows_per_pages           = Config::get('appconfig.max_rows_per_page');
          
          if($page_number > 1)
              $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
          else
              $skip = 0;
          
          $lst_products                 = Products::wherePProductIsDeleted(0);
          if(strlen($general_search) > 0)
              $lst_products             = $lst_products->where('p_product_name','LIKE','%' . $general_search . '%')->orWhere('p_barcode','LIKE','%' . $general_search . '%');
          if($product_category > 0)
              $lst_products             = $lst_products->whereFkPcId( $product_category);
          if($product_currency> 0)
              $lst_products             = $lst_products->wherePProductCurrency( $product_currency);
              $count_products               = $lst_products->count();
              $lst_products                 = $lst_products->skip($skip)->take($nbr_rows_per_pages)->get();
          
          $lst_product_categories_array = ProductCategories::wherePcIsDeleted(0)->get();
         
          $system_currencies            = Currency::all();
          $currency_array               = CreateDatabaseArrayByIndex($system_currencies, "cc_id");
          
          
          
          $total_pages = ceil( $count_products/$nbr_rows_per_pages );
          $total_pages = intval($total_pages);
          
          
          $result_array =array();
  
          $data = array(
              "lst_products" => $lst_products,
              "currency_array" => $currency_array,
              "lst_product_categories_array" => $lst_product_categories_array,
          );
          $result_array['is_error']         = 0;
          $result_array['total_pages']      = $total_pages;
          $result_array['display']          = view("products.displaylist",$data)->render();
          
          return Response()->json($result_array);
          
      }
      
      /**
       * Display Metric section related to the product based on  
       * @param Request $request
       */
      public function DisplayMetricSection(Request $request)
      {
          $p_product_unit_type  = $request->input("p_product_unit_type");
          $p_id                 = $request->input("p_id");
          $result_array         = array();
          
          $product_info = new Products();
          
          if($p_id != null)
            $product_info = Products::find($p_id);
          
          $lst_units        = Units::whereSuUnityType($p_product_unit_type)->get();
          $lst_units_weight = Units::whereSuUnityType("weight")->get();
          $lst_units_size   = Units::whereSuUnityType("size")->get();
          $lst_units_surface= Units::whereSuUnityType("surface")->get();
          
          $data = array(
              "product_info" => $product_info,
              "p_product_unit_type" => $p_product_unit_type,
              "lst_units_surface" => $lst_units_surface,
              "lst_units_weight" => $lst_units_weight,
              "lst_units_size" => $lst_units_size,
          );
          $result_array['display'] =view("products.productmetrics" , $data)->render();
          
          
          
          return Response()->json($result_array);
      }
      
      /**
       * Save Product Information
       * 
       * @author Moe Mantach
       * @access public
       * @param Request $request
       * @return unknown
       */
      public function SaveProductInfo(Request $request)
      {
          $p_id                         = $request->input('p_id');
          $fk_pc_id                     = $request->input('fk_pc_id');
          $p_barcode                    = $request->input('p_bar_code');
          $p_barcode_img                = $request->input('p_barcode_img');
          $p_product_ref                = $request->input('p_product_ref');
          $p_product_name               = $request->input('p_product_name');
          $p_product_description        = $request->input('p_product_description');
          $p_product_stock_alert        = $request->input('p_product_stock_alert');
          $p_product_weight             = $request->input('p_product_weight');
          $p_product_weight_unit        = $request->input('p_product_weight_unit');
          $p_product_length             = $request->input('p_product_length');
          $p_product_length_unit        = $request->input('p_product_length_unit');
          $p_product_width              = $request->input('p_product_width');
          $p_product_width_unit         = $request->input('p_product_width_unit');
          $p_product_height             = $request->input('p_product_height');
          $p_product_height_unit        = $request->input('p_product_height_unit');
          $p_product_area               = $request->input('p_product_area');
          $p_product_area_unit          = $request->input('p_product_area_unit');
          $p_product_selling_price      = $request->input('p_product_selling_price'); 
          $p_product_min_selling_price  = $request->input('p_product_min_selling_price'); 
          $p_product_tax_rate           = $request->input('p_product_tax_rate');
          $p_product_currency           = $request->input('p_product_currency');
          $p_sale_accounting_code       = $request->input('p_sale_accounting_code');
          $p_sale_export_accounting_code= $request->input('p_sale_export_accounting_code');
          $p_purchase_accounting_code   = $request->input('p_purchase_accounting_code');
          $p_product_type               = $request->input('p_product_type');
          $p_product_unit_type          = $request->input('p_product_unit_type');
          $p_product_color              = $request->input('p_product_color');
          $p_product_currency           = $request->input("p_product_currency");
          $p_product_expiry_date        = $request->input("p_product_expiry_date");
          $p_product_production_date    = $request->input("p_product_production_date");
          
          $fk_warehouse_id              = $request->input("fk_warehouse_id");
          $fk_zone_id                   = $request->input("fk_zone_id");
          $fk_floor_id                  = $request->input("fk_floor_id");
          
          $ProductInfo  = new Products();
          $ProductManager_obj = new ProductManager();
          if($p_id > 0)
          {
              $ProductInfo      = Products::find($p_id);
              $saved_currency   = $ProductInfo->p_product_currency;
              if( $saved_currency != $p_product_currency )
              {
                 /** $currency_info        = Currency::find($saved_currency);
                  $com_currency_info    = Currency::find($company_currency);
                  
                  
                  $selling_price        = convertCurrency($p_product_selling_price, $currency_info->cc_currency_code, $com_currency_info->cc_currency_code);
                  $min_selling_price    = convertCurrency($p_product_min_selling_price, $currency_info->cc_currency_code, $com_currency_info->cc_currency_code);
                  
                  $p_product_selling_price      = $selling_price;
                  $p_product_min_selling_price  = $min_selling_price;*/
                  //$p_product_currency           = $company_currency;
              }
          }
          else
          {
          
              
          }
          
          $ProductInfo->fk_pc_id                       = $fk_pc_id;
          $ProductInfo->p_barcode                     = $p_barcode;
          $ProductInfo->p_barcode_img                 = $p_barcode_img;
          $ProductInfo->p_product_ref                 = $p_product_ref;
          $ProductInfo->p_product_name                = $p_product_name;
          $ProductInfo->p_product_description         = $p_product_description;
          $ProductInfo->p_product_stock_alert         = $p_product_stock_alert;
          $ProductInfo->p_product_weight              = $p_product_weight;
          $ProductInfo->p_product_weight_unit         = $p_product_weight_unit;
          $ProductInfo->p_product_length              = $p_product_length;
          $ProductInfo->p_product_length_unit         = $p_product_length_unit;
          $ProductInfo->p_product_width               = $p_product_width;
          $ProductInfo->p_product_width_unit          = $p_product_width_unit;
          $ProductInfo->p_product_height              = $p_product_height;
          $ProductInfo->p_product_height_unit         = $p_product_height_unit;
          $ProductInfo->p_product_area                = $p_product_area;
          $ProductInfo->p_product_area_unit           = $p_product_area_unit;
          $ProductInfo->p_product_selling_price       = $p_product_selling_price;
          $ProductInfo->p_product_min_selling_price   = $p_product_min_selling_price;
          $ProductInfo->p_product_tax_rate            = $p_product_tax_rate;
          $ProductInfo->p_sale_accounting_code        = $p_sale_accounting_code;
          $ProductInfo->p_sale_export_accounting_code = $p_sale_export_accounting_code;
          $ProductInfo->p_purchase_accounting_code    = $p_purchase_accounting_code;
          $ProductInfo->p_product_type                = $p_product_type;
          $ProductInfo->p_product_unit_type           = $p_product_unit_type;
          $ProductInfo->p_product_color               = $p_product_color;
          $ProductInfo->p_product_currency            = $p_product_currency;
          $ProductInfo->p_product_expiry_date         = $p_product_expiry_date;
          $ProductInfo->p_product_production_date     = $p_product_production_date;
          $ProductInfo->fk_warehouse_id               = $fk_warehouse_id;
          $ProductInfo->fk_zone_id                    = $fk_zone_id;
          $ProductInfo->fk_floor_id                   = $fk_floor_id;
          $ProductInfo->save();
          
          $p_id = $ProductInfo->p_id;
          
          if( $p_id!== null && count($_FILES) > 0 )
          {
              $image_data =  $ProductManager_obj->UploadProductAvatar($p_id);
              
              $ProductInfo->p_product_profile_base_src  = $image_data['data']['p_avatar_base_src'];
              $ProductInfo->p_product_profile_file_name = $image_data['data']['p_avatar_file_name'];
              $ProductInfo->p_product_profile_extention = $image_data['data']['p_avatar_extentions'];
              $ProductInfo->save();
              
          }
          
          
          
          
          $result_array = array();
          
          $result_array['is_error'] = 0;
          $result_array['error_msg'] = "Operation Complete Successfully";
          return Response()->json($result_array);
      }
      
      
      /**
       * Download CSV Template
       * 
       * @author Moe Mantach
       * @access public
       * @param Request $request
       */
      public function DownloadTemplate(Request $request)
      {
         
          $data = array();
          $data[] = ['Product Ref','category','warehouse','zoon','floor', 'Product Name','Product Description','width','height','length','Selling Price','Currency'];
          
  
          
          $csv = Writer::createFromFileObject(new \SplTempFileObject());
          
          $csv->insertAll($data);
          
          $csv->output('products-template.csv');
      }
      
      
      /**
       * Delete Product by changing bflag of is deleted
       * @param Request $request
       * @return unknown
       */
      public function DeleteProductInfo(Request $request)
      {
          $p_id = $request->input('p_id');
          
          //check if already have a stock you cannot delete the item
          $ProductStock = Stocks::whereIsIsDeleted(0)->whereFkProductId($p_id)->get();
          if(count($ProductStock) > 0)
          {
              $result_array['is_error'] = 1;
              $result_array['error_msg'] = "Product Already Has a stock , you cannot delete the product";
              return Response()->json($result_array);
          }
          
          
          
          $ProductInfo = Products::find($p_id);
          $ProductInfo->p_product_is_deleted = 1;
          $ProductInfo->p_product_deleted_by = session('user_id');
          $ProductInfo->save();
          
          
          $result_array = array();
          
          $result_array['is_error'] = 0;
          $result_array['error_msg'] = "Operation Complete Successfully";
          return Response()->json($result_array);
      }
      
      
      /**
       * Display list of stocks in all warehouses for selected product
       * 
       * @author Moe mantach
       * @access public
       * @param Request $request
       */
      public function DisplayListStocks(Request $request)
      {
          $p_id = $request->input("p_id");
          
          $productStocks = Stocks::whereFkProductId($p_id)->whereIsIsDeleted(0)->get();
          
          $result_array  = array();
           
          
          $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
          $warehouses_array =CreateDatabaseArrayByIndex($lst_warehouses, "w_id");
          
          $data = array(
              "productStocks" => $productStocks,
              "warehouses_array" => $warehouses_array,
          );
          $result_array['is_error'] = 0;
          $result_array['display'] = view("products.displayliststocks",$data)->render();
          
          return Response()->json($result_array);
      }
      
      
      /**
       * Display list of stock movements done for the current product
       * @param Request $request
       * @return unknown
       */
      public function DisplayListStockMovements(Request $request)
      {
          $p_id = $request->input("p_id");
          
          $productStockMovements = StockMovements::whereFkProductId($p_id)->whereSmIsDeleted(0)->get();
 
          $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
          $warehouses_array =CreateDatabaseArrayByIndex($lst_warehouses, "w_id");
          
          $result_array  = array();
          $data = array(
              "productStockMovements" => $productStockMovements,
              "warehouses_array" => $warehouses_array,
          );
          $result_array['is_error'] = 0;
          $result_array['display'] = view("products.displayliststockmovements",$data)->render();
          
          return Response()->json($result_array);
      }

}