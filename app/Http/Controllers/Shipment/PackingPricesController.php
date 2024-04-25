<?php
/***********************************************************
PackingPricesController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
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
use App\models\Inventory\ProductCategories;
use App\models\Shipment\PackingPrices;
use App\models\System\Units;
use App\models\System\Currency;



class PackingPricesController extends Controller
{

    /**
     * Page to control Packing Prices Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        
        $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->get();
        
        $data = array(
            "lst_product_categories" => $lst_product_categories
        );
        return Response()->view('shipment.packing',$data);
    }
    
    
    /**
     * Display list of Packing Prices saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
          $page_number                  = $request->input('page_number');
          $general_search               = $request->input('general_search');
          $product_category             = $request->input('product_category');
          $nbr_rows_per_pages           = Config::get('appconfig.max_rows_per_page');
          
          if($page_number > 1)
              $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
          else
              $skip = 0;
          
          $packing_prices_cond          = PackingPrices::whereCpIsDeleted(0);
          if(strlen($general_search) > 0)
              $packing_prices_cond     = $packing_prices_cond->where('cp_range_label','LIKE','%' . $general_search . '%');
          if($product_category > 0)
              $packing_prices_cond             = $packing_prices_cond->whereFkCategoryId( $product_category);
           $count_records = $packing_prices_cond->count();   
           $lst_category_packing                 = $packing_prices_cond->skip($skip)->take($nbr_rows_per_pages)->get();
         
          
          
          
          $total_pages = ceil( $count_records/$nbr_rows_per_pages );
          $total_pages = intval($total_pages);
          
          
          $result_array =array();
  
          $data = array(
              "lst_category_packing" => $lst_category_packing,
          );
          $result_array['is_error']         = 0;
          $result_array['total_pages']      = $total_pages;
          $result_array['display']          = view("shipment.listpacking",$data)->render();
          
          return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Packing Price
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->get();
        $lst_units = Units::whereSuUnityType('weight')->get();
        $lst_currencies = Currency::all();
        $data = array(
            "lst_product_categories" => $lst_product_categories,
            "lst_units" => $lst_units,
            "lst_currencies" => $lst_currencies
        );
        return view('shipment.addpacking',$data);
    }
    
    
    /**
     * Save Order Order Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SavePackingPricesInfo(Request $request)
    {
        $cp_id                      = $request->input('cp_id');
        $fk_category_id             = $request->input('fk_category_id');
        $cp_range_label             = $request->input('cp_range_label');
        $cp_weight_from             = $request->input('cp_weight_from');
        $cp_weight_to               = $request->input('cp_weight_to');
        $cp_price_range             = $request->input('cp_price_range');
        $cp_price_currency          = $request->input('cp_price_currency');
        $cp_weight_unit             = $request->input('cp_weight_unit');
        
        $result_array = array();
 
        
        $packing_info = new PackingPrices();
        if( $cp_id != null )
        {
            $packing_info= PackingPrices::find($cp_id);
        }
         
        $packing_info->fk_category_id               = $fk_category_id;
        $packing_info->cp_range_label               = $cp_range_label;
        $packing_info->cp_weight_from               = $cp_weight_from;
        $packing_info->cp_weight_to                 = $cp_weight_to;
        $packing_info->cp_weight_unit               = $cp_weight_unit;
        $packing_info->cp_price_range               = $cp_price_range;
        $packing_info->cp_price_currency            = $cp_price_currency;
        $packing_info->cp_weight_unit               = $cp_weight_unit;
        
        $packing_info->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Packing Prices Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Order Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $cp_id )
    {
        $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->get();
        $packing_info        = PackingPrices::find($cp_id);
        $lst_units = Units::whereSuUnityType('weight')->get();
        $lst_currencies = Currency::all();
        
        $data = array(
            "lst_product_categories" => $lst_product_categories,
            "packing_info" => $packing_info,
            "lst_units" => $lst_units,
            "lst_currencies" => $lst_currencies
        );
        return view('shipment.editpacking',$data);
    }
    
    
    /**
     * Delete Packing information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeletePackingPricesInfo(Request $request)
    {
        
        $cp_id= $request->input('cp_id');
         
        $packing_info = PackingPrices::find( $cp_id);
        $packing_info->cp_is_deleted          = 1;
        $packing_info->cp_deleted_by          = Session('user_id');
        $packing_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}