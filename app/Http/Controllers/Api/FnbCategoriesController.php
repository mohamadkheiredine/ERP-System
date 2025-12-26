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
}
