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
        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

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
        $mc_id                   = $request->input('mc_id');
        $mc_category_name        = $request->input('mc_category_name');
        $mc_category_description = $request->input('mc_category_description');
        $mc_is_active            = $request->has('mc_is_active') ? 1 : 0;

        // Prepare reference code
        $mc_category_code = strtolower($mc_category_name);
        $mc_category_code = str_replace(" ", "", $mc_category_code);
        $mc_category_code = substr($mc_category_code, 0, 3);

        $result_array = [];
        $FnbManager = new FnbCategoriesManager();

        // Initialize image fields
        $mc_profile_base_src  = '';
        $mc_profile_file_name = '';
        $mc_profile_extension = '';

        // Handle image upload
        if (count($_FILES) > 0) {
            $image_data = $FnbManager->UploadAvatarCategory($mc_id);

            // Only assign if upload succeeded
            if (isset($image_data['is_error']) && $image_data['is_error'] == 0 && isset($image_data['data'])) {
                $mc_profile_base_src  = $image_data['data']['mc_profile_base_src'] ?? '';
                $mc_profile_file_name = $image_data['data']['mc_profile_file_name'] ?? '';
                $mc_profile_extension = $image_data['data']['mc_profile_extension'] ?? '';
            }
        }

        // Create or update record
        $MenuCategory = $mc_id ? MenuCategories::find($mc_id) : new MenuCategories();

        $MenuCategory->mc_category_name        = $mc_category_name;
        $MenuCategory->mc_category_description = $mc_category_description;
        $MenuCategory->mc_category_code        = $mc_category_code;
        $MenuCategory->mc_is_active            = $mc_is_active;

        // Update image fields only if upload succeeded
        if (strlen($mc_profile_base_src) > 0) {
            $MenuCategory->mc_profile_base_src  = $mc_profile_base_src;
            $MenuCategory->mc_profile_file_name = $mc_profile_file_name;
            $MenuCategory->mc_profile_extension = $mc_profile_extension;
        }

        $MenuCategory->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Menu Category Information has been saved successfully.';

        return response()->json($result_array);
    }



    public function EditForm($mc_id)
    {
        $fnb_categories        = MenuCategories::find($mc_id);

        $lst_fnb_categories = MenuCategories::whereMcIsDeleted(0)->get();

        $data = array(
            "fnb_categories" => $fnb_categories,
            "lst_fnb_categories" => $lst_fnb_categories
        );
        return view('fnb.categories.editform', $data);
    }

    public function DeleteCategoryInfo(Request $request)
    {

        $mc_id = $request->input('mc_id');

        $fnb_category = MenuCategories::find($mc_id);
        $fnb_category->mc_is_deleted          = 1;
        $fnb_category->mc_deleted_by          = Session('user_id');
        $fnb_category->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }


    // /**
    //  * Display list items in the selecvted mc_id category
    //  *
    //  * @author Moe Mantach
    //  * @access public
    //  * @param Request $request
    //  */
    // public function DisplayListItems(Request $request)
    // {
    //     $mc_id= $request->input('mc_id');
    //     $page_number           = $request->input('page_number');
    //     $search_query           = $request->input('search_query');
    //     $nbr_rows_per_pages    = Config::get('apmconfig.max_rows_per_page');
    //     if($page_number > 1)
    //         $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
    //     else
    //         $skip = 0;



    //     $products_count = Products::wherePProductIsDeleted(0)->whereFkmcId($mc_id);

    //     if(strlen($search_query) > 0)
    //         $products_count= $products_count->where('p_product_name' , 'LIKE' , '%' . $search_query . '%');

    //         $products_count= $products_count->count();


    //         $total_pages = ceil( $products_count /$nbr_rows_per_pages );
    //     $total_pages = intval($total_pages);

    //     $lst_products = Products::wherePProductIsDeleted(0)->whereFkmcId($mc_id);


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
