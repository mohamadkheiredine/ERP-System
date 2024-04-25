<?php
/***********************************************************
ProductCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

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
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;



class ProductCategoriesController extends Controller
{

    /**
     * Page to control product categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('products.categories.categories',$data);
    }
    
    
    /**
     * Display list of product categories
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;
        
         
            
        $product_categories_count = ProductCategories::wherePcIsDeleted(0);
        
        if(strlen($search_query) > 0)
            $product_categories_count = $product_categories_count->where('pc_category' , 'LIKE' , '%' . $search_query . '%');
        
        $product_categories_count = $product_categories_count->count();
        
        
        $total_pages = ceil( $product_categories_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $product_categories = ProductCategories::wherePcIsDeleted(0);
        
        if(strlen($search_query) > 0)
            $product_categories= $product_categories->where('pc_category' , 'LIKE' , '%' . $search_query . '%');
        
        $product_categories = $product_categories->skip($skip)->take($nbr_rows_per_pages)->orderBy('fk_pc_id', 'ASC')->get();
            
        $product_categories_array   = array();
        $lst_product_categories     = ProductCategories::wherePcIsDeleted(0)->get();
        foreach ( $lst_product_categories as $key => $pc_info ) 
        {
            $product_categories_array[ $pc_info->pc_id ] =  $pc_info->pc_category;
        } 
        $data = array(
            "product_categories" => $product_categories,
            "product_categories_array" => $product_categories_array
        );
        
        $result_array = array();
        
        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("products.categories.displaylist",$data)->render();
        
        return Response()->json($result_array);
    }
    
    /**
     * Page to display list of products inside pc_id category
     * 
     * @author Moe Mantach
     * @access public
     * @param Integer $pc_id
     */
    public function ListProducts($pc_id)
    {
        
        $data = array(
            "pc_id" => $pc_id
        );
        return Response()->view('products.categories.itemscategory',$data);
    }
    
    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->get();
        
        $data = array(
            "lst_product_categories" => $lst_product_categories,
        );
        return view('products.categories.addcategory',$data);
    }
    
    
    /**
     * Save Product Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveProductCategoryInfo(Request $request)
    {
        $fk_pc_id                   = $request->input('fk_pc_id');
        $pc_category                = $request->input('pc_category');
        $pc_cat_ref                 = strtolower($pc_category);
        $pc_cat_ref                 = str_replace(" ", "", $pc_cat_ref);
        $pc_cat_ref                 = substr($pc_cat_ref, 0,3);
        $pc_description             = $request->input('pc_description');
        $pc_use_serial_number       = $request->input('pc_use_serial_number');
        $pc_maintenance_category    = $request->input('pc_maintenance_category');
        $pc_id                      = $request->input( "pc_id");
        $result_array = array();
        $ProductCategoriesManager  = new ProductCategoriesManager();
        $pc_avatar_base_src     = "";
        $pc_avatar_file_name    = "";
        $pc_avatar_extension    = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $ProductCategoriesManager->UploadAvatarCategory(null);
            
            $pc_avatar_base_src      = $image_data['data']['pc_avatar_base_src'];
            $pc_avatar_file_name     = $image_data['data']['pc_avatar_file_name'];
            $pc_avatar_extension     = $image_data['data']['pc_avatar_extentions'];
            
        }
        
        $ProductCategory = new ProductCategories();
        if($pc_id != null)
        {
            $ProductCategory = ProductCategories::find($pc_id);
        }
         
        $ProductCategory->fk_pc_id                  = $fk_pc_id;
        $ProductCategory->pc_category               = $pc_category;
        $ProductCategory->pc_description            = $pc_description;
        $ProductCategory->pc_cat_ref                = $pc_cat_ref;
        $ProductCategory->pc_use_serial_number      = $pc_use_serial_number;
        $ProductCategory->pc_maintenance_category   = $pc_maintenance_category;
        
        if(strlen($pc_avatar_base_src) > 0)
        {
            $ProductCategory->pc_avatar_base_src      = $pc_avatar_base_src;
            $ProductCategory->pc_avatar_file_name     = $pc_avatar_file_name;
            $ProductCategory->pc_avatar_extension     = $pc_avatar_extension;
        }
        
        $ProductCategory->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Product Category Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Product Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pc_id
     */
    public function EditForm( $pc_id )
    {
        $product_categories        = ProductCategories::find($pc_id); 
        
        $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->whereNotIn("pc_id",array($pc_id))->get();
        
        $data = array(
            "product_categories" => $product_categories,
            "lst_product_categories" => $lst_product_categories
        );
        return view('products.categories.editcategory',$data);
    }
    
    
    /**
     * Delete product category information
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteProductCategoryInfo(Request $request)
    {
        
        $pc_id= $request->input('pc_id');
         
        $product_category = ProductCategories::find( $pc_id);
        $product_category->pc_is_deleted          = 1;
        $product_category->pc_deleted_by          = Session('user_id');
        $product_category->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list items in the selecvted pc_id category
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListItems(Request $request)
    {
        $pc_id= $request->input('pc_id');
        $page_number           = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
            
            
            
        $products_count = Products::wherePProductIsDeleted(0)->whereFkPcId($pc_id);
         
        if(strlen($search_query) > 0)
            $products_count= $products_count->where('p_product_name' , 'LIKE' , '%' . $search_query . '%');
            
            $products_count= $products_count->count();
        
        
            $total_pages = ceil( $products_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);
        
        $lst_products = Products::wherePProductIsDeleted(0)->whereFkPcId($pc_id);
        
      
        if(strlen($search_query) > 0)
            $lst_products=  $lst_products->where('p_product_name' , 'LIKE' , '%' . $search_query . '%');
            
            $lst_products= $lst_products->skip($skip)->take($nbr_rows_per_pages)->orderBy('p_product_name', 'ASC')->get();
            
            $data = array( 
                "lst_products" => $lst_products
            );
            
            $result_array = array();
            
            $result_array['total_pages'] = $total_pages;
            $result_array['display'] = view("products.categories.displaylistitems",$data)->render();
            
            return Response()->json($result_array);
    }

}