<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\MenuCategories;

class FnbCategoriesController extends Controller
{
    public function ListItemCategories(Request $request)
    {
        $category_id = $request->input('category_id');
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

        $categories_cond = MenuCategories::whereMcIsDeleted(0);
        if (!empty($category_id)) {
            $categories_cond = $categories_cond->whereCategoryId($category_id);
        }
        $lst_categories = $categories_cond->get();

        $categories_array = array();
        foreach ($lst_categories as $index => $category_info) {
            $categories_array[$index]['mc_id'] = $category_info->mc_id;
            $categories_array[$index]['mc_category_name'] = $category_info->mc_category_name;
            $categories_array[$index]['mc_category_description'] = $category_info->mc_category_description;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Fetch Data Completed';
        $result_array['lst_item_categories'] = $categories_array;
        return Response()->json($result_array);
    }

    public function GetListMenuCategories(Request $request)
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

        $lst_menu_categories = MenuCategories::whereMcIsDeleted(0)->get();

        $categories_array = array();
        foreach ($lst_menu_categories as $index => $category_info) {
            $categories_array[$index]['mc_id'] = $category_info->mc_id;
            $categories_array[$index]['mc_category_name'] = $category_info->mc_category_name;
            $categories_array[$index]['mc_category_description'] = $category_info->mc_category_description;
            $categories_array[$index]['mc_created_at'] = $category_info->mc_created_at;
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'Fetch Data Completed';
        $result_array['lst_menu_categories'] = $categories_array;
        return Response()->json($result_array);
    }

    public function SaveMenuCategory(Request $request)
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

        $mc_id = $request->input('mc_id');
        $mc_category_name = $request->input('mc_category_name');
        $mc_category_description = $request->input('mc_category_description');
        $mc_is_active = $request->has('mc_is_active') ? 1 : 0;
        $mc_category_code = $request->input('mc_category_code');
        $mc_created_by = $request->input('user_id');
        $mc_updated_by = $request->input('user_id');

        $result_array = array();

        if ($mc_id != null) {
            $category_info = MenuCategories::find($mc_id);
            if (!$category_info) {
                return Response()->json([
                    'is_error' => 1,
                    'error_msg' => 'Category not found'
                ]);
            }
            $category_info->mc_updated_by = $mc_updated_by;
        } else {
            $category_info = new MenuCategories();
            $category_info->mc_created_by = $mc_created_by;
            $category_info->mc_updated_by = $mc_updated_by;
        }

        $category_info->mc_category_name = $mc_category_name;
        $category_info->mc_category_description = $mc_category_description;
        $category_info->mc_category_code = $mc_category_code;
        $category_info->mc_is_active = $mc_is_active;

        $category_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Menu Category Information Has been saved';
        $result_array['mc_id']     = $category_info->mc_id;

        return Response()->json($result_array);
    }
}
