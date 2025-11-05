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


namespace App\Http\Controllers\Fnb\Category;

use App\Http\Controllers\Controller;
use App\library\FnbCategoriesManager;
use Illuminate\Http\Request;
use Config;
use App\library\ProductCategoriesManager;
use App\models\FnB\MenuCategories;

class FnbCategoryController extends Controller
{

    public function index()
    {
        $data = array();
        return Response()->view('fnb.categories.fnb-categories', $data);
    }

    public function DisplayList(Request $request)
    {
        $page_number        = $request->input('page_number', 1);
        $search_query       = $request->input('search_query', '');
        $nbr_rows_per_pages = Config::get('appconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        // Base query
        $query = MenuCategories::where('mc_is_deleted', 0);

        if (!empty($search_query)) {
            $query->where('mc_category_name', 'LIKE', '%' . $search_query . '%');
        }

        $categories_count = $query->count();
        $total_pages = (int) ceil($categories_count / $nbr_rows_per_pages);
        $categories = $query->skip($skip)->take($nbr_rows_per_pages)->get();

        $data = [
            'lst_categories' => $categories,
        ];

        $result_array = [
            'total_pages' => $total_pages,
            'display' => view('fnb.categories.listcategories', $data)->render(),
        ];

        return response()->json($result_array);
    }

    public function AddForm()
    {

        $lst_categories = MenuCategories::whereMcIsDeleted(0)->get();

        $data = array(
            "lst_categories" => $lst_categories,
        );
        return view('fnb.categories.addform', $data);
    }


    public function SaveCategoryInfo(Request $request)
    {
        $mc_category_name                = $request->input('mc_category_name');
        $pc_cat_ref                 = strtolower($mc_category_name);
        $pc_cat_ref                 = str_replace(" ", "", $pc_cat_ref);
        $pc_cat_ref                 = substr($pc_cat_ref, 0,3);
        $mc_category_description             = $request->input('mc_category_description');

        $mc_id                      = $request->input( "mc_id");
        $result_array = array();
        $ProductCategoriesManager  = new FnbCategoriesManager();
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

        $MenuCategory = new MenuCategories();
        if($mc_id != null)
        {
            $MenuCategory = MenuCategories::find($mc_id);
        }

        $MenuCategory->mc_name_category               = $mc_category_name;
        $MenuCategory->mc_category_description            = $mc_category_description;
        $MenuCategory->mc_cat_ref                = $pc_cat_ref;

        if(strlen($pc_avatar_base_src) > 0)
        {
            $MenuCategory->pc_avatar_base_src      = $pc_avatar_base_src;
            $MenuCategory->pc_avatar_file_name     = $pc_avatar_file_name;
            $MenuCategory->pc_avatar_extension     = $pc_avatar_extension;
        }

        $MenuCategory->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Product Category Information Has been saved';

        return Response()->json($result_array);
    }

    // public function EditForm($pc_id)
    // {
    //     $product_categories        = ProductCategories::find($pc_id);

    //     $lst_product_categories = ProductCategories::wherePcIsDeleted(0)->whereNotIn("pc_id", array($pc_id))->get();

    //     $data = array(
    //         "product_categories" => $product_categories,
    //         "lst_product_categories" => $lst_product_categories
    //     );
    //     return view('products.categories.editcategory', $data);
    // }

    // public function DeleteProductCategoryInfo(Request $request)
    // {

    //     $pc_id = $request->input('pc_id');

    //     $product_category = ProductCategories::find($pc_id);
    //     $product_category->pc_is_deleted          = 1;
    //     $product_category->pc_deleted_by          = Session('user_id');
    //     $product_category->save();


    //     $result_array['is_error']   = 0;
    //     $result_array['error_msg']  = "Operation Complete Successfully";

    //     return Response()->json($result_array);
    // }


    // /**
    //  * Display list items in the selecvted pc_id category
    //  *
    //  * @author Moe Mantach
    //  * @access public
    //  * @param Request $request
    //  */
    // public function DisplayListItems(Request $request)
    // {
    //     $pc_id= $request->input('pc_id');
    //     $page_number           = $request->input('page_number');
    //     $search_query           = $request->input('search_query');
    //     $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
    //     if($page_number > 1)
    //         $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
    //     else
    //         $skip = 0;



    //     $products_count = Products::wherePProductIsDeleted(0)->whereFkPcId($pc_id);

    //     if(strlen($search_query) > 0)
    //         $products_count= $products_count->where('p_product_name' , 'LIKE' , '%' . $search_query . '%');

    //         $products_count= $products_count->count();


    //         $total_pages = ceil( $products_count /$nbr_rows_per_pages );
    //     $total_pages = intval($total_pages);

    //     $lst_products = Products::wherePProductIsDeleted(0)->whereFkPcId($pc_id);


    //     if(strlen($search_query) > 0)
    //         $lst_products=  $lst_products->where('p_product_name' , 'LIKE' , '%' . $search_query . '%');

    //         $lst_products= $lst_products->skip($skip)->take($nbr_rows_per_pages)->orderBy('p_product_name', 'ASC')->get();

    //         $data = array(
    //             "lst_products" => $lst_products
    //         );

    //         $result_array = array();

    //         $result_array['total_pages'] = $total_pages;
    //         $result_array['display'] = view("products.categories.displaylistitems",$data)->render();

    //         return Response()->json($result_array);
    // }

}
