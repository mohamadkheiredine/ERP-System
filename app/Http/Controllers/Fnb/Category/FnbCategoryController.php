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
use App\models\FnB\KitchenStations;
use App\models\FnB\MenuCategories;
use App\models\FnB\ProductItems;
use App\models\Sales\Terminals;
use App\models\System\Companies;
use App\models\Accounting\VatAccounts;
use Milon\Barcode\DNS1D;


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

    public function ListProducts(Request $request)
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_kitchens = KitchenStations::whereKsIsDeleted(0)->get();
        $data = array(
            "lst_companies" => $lst_companies,
            "lst_kitchens" => $lst_kitchens
        );
        return Response()->view('fnb.categories.itemscategory', $data);
    }



    public function DisplayListItems(Request $request)
    {
        $category_id   = $request->input('category_id');
        $page_number   = $request->input('page_number');
        $search_query  = $request->input('search_query');
        $branch_id     = $request->input('branch_id');
        $kitchen_id    = $request->input('kitchen_id');

        $nbr_rows_per_pages = Config::get('apmconfig.max_rows_per_page', 10);

        $skip = ($page_number > 1)
            ? ($page_number - 1) * $nbr_rows_per_pages
            : 0;

        $query = ProductItems::where('fi_is_deleted', 0)
            ->where('fi_category_id', $category_id);

        if ($category_id > 0) {
            $query->where('fi_category_id', $category_id);
        }

        if ($branch_id > 0) {
            $query->where('fi_branch_id', $branch_id);
        }

        if ($kitchen_id > 0) {
            $query->where('fi_kitchen_id', $kitchen_id);
        }

        if (!empty($search_query)) {
            $query->where('fi_item_name', 'LIKE', '%' . $search_query . '%');
        }

        // Pagination count
        $total_items  = $query->count();
        $total_pages  = max(1, ceil($total_items / $nbr_rows_per_pages));

        $lst_products = $query->orderBy('fi_item_name', 'ASC')
            ->skip($skip)
            ->take($nbr_rows_per_pages)
            ->get();

        $data = [
            "lst_products" => $lst_products,
        ];

        $result_array = [
            'total_pages' => $total_pages,
            'display'     => view("fnb.categories.displaylistitems", $data)->render(),
        ];

        return response()->json($result_array);
    }

}
