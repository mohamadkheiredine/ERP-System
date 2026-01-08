<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\FnB\MenuCategories;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FnbCategoriesController extends Controller
{
    public function ListItemCategories(Request $request)
    {
        $category_id = $request->input('category_id');
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $search = $request->input('search');

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
            $categories_cond->where('mc_id', $category_id);
        }

        // ✅ ADDED SEARCH (does not break structure)
        if (!empty($search)) {
            $categories_cond->where(
                'mc_category_name',
                'LIKE',
                '%' . $search . '%'
            );
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
        $search  = $request->input('search');
        $mc_is_active = $request->input('mc_is_active');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $query = MenuCategories::where('mc_is_deleted', 0);

        if (!empty($search)) {
            $query->where('mc_category_name', 'LIKE', "%{$search}%");
        }

        if ($mc_is_active !== null && $mc_is_active !== '') {
            $query->where('mc_is_active', $mc_is_active);
        }

        $lst_menu_categories = $query
            ->orderBy('mc_display_order')
            ->orderBy('mc_category_name')
            ->get();

        $categories_array = [];
        foreach ($lst_menu_categories as $index => $category_info) {
            $categories_array[$index]['mc_id'] = $category_info->mc_id;
            $categories_array[$index]['mc_category_name'] = $category_info->mc_category_name;
            $categories_array[$index]['mc_category_description'] = $category_info->mc_category_description;
            $categories_array[$index]['mc_category_code'] = $category_info->mc_category_code;

            $categories_array[$index]['mc_profile_base_src'] = $category_info->mc_profile_base_src;
            $categories_array[$index]['mc_profile_file_name'] = $category_info->mc_profile_file_name;
            $categories_array[$index]['mc_profile_extension'] = $category_info->mc_profile_extension;

            $categories_array[$index]['mc_is_active'] = $category_info->mc_is_active;
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
        $mc_is_active = $request->input('mc_is_active') ? 1 : 0;
        $mc_category_code = $request->input('mc_category_code');
        $mc_created_by = $request->input('user_id');
        $mc_updated_by = $request->input('user_id');

        $result_array = array();

        if ($mc_id != null) {
            $category_info = MenuCategories::find($mc_id);
            if (!$category_info) {
                return response()->json(['is_error' => 1, 'error_msg' => 'Category not found']);
            }
            $category_info->mc_updated_by = $request->input('user_id');
        } else {
            $category_info = new MenuCategories();
            $category_info->mc_created_by = $request->input('user_id');
            $category_info->mc_updated_by = $request->input('user_id');
        }

        // save normal fields
        $category_info->mc_category_name = $request->input('mc_category_name');
        $category_info->mc_category_description = $request->input('mc_category_description');
        $category_info->mc_category_code = $request->input('mc_category_code');
        $category_info->mc_is_active = $request->input('mc_is_active') ? 1 : 0;

        // ✅ HANDLE FILE UPLOAD (like your old system)
        if ($request->hasFile('mc_avatar_pic')) {
            $file = $request->file('mc_avatar_pic');

            $base_dir = date('Y/m/d/');                 // same as old code
            $root_folder = 'uploads/menu/';             // matches your DB sample
            $directory = public_path($root_folder . $base_dir);

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0777, true);
            }

            $ext = $file->getClientOriginalExtension();
            $name = md5(now()) . "_" . date("YmdHis") . "_" . rand(0, 8888888);

            $file->move($directory, $name . "." . $ext);

            $category_info->mc_profile_base_src = $root_folder . $base_dir; // ex: uploads/menu/2025/11/06/
            $category_info->mc_profile_file_name = $name;                   // without extension (like old system)
            $category_info->mc_profile_extension = $ext;                    // jpg
        }

        $category_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Menu Category Information Has been saved';
        $result_array['mc_id']     = $category_info->mc_id;

        return Response()->json($result_array);
    }

    public function DeleteMenuCategory(Request $request)
    {
        $g_hash   = $request->input('g_hash');
        $user_id = $request->input('user_id');
        $mc_id   = $request->input('mc_id');


        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $category = MenuCategories::find($mc_id);
        if (!$category) {
            return response()->json([
                'is_error' => 1,
                'error_msg' => 'Category not found'
            ]);
        }

        $category->mc_is_deleted = 1;
        $category->mc_deleted_by = $user_id;
        $category->save();

        return response()->json([
            'is_error' => 0,
            'error_msg' => 'Category deleted successfully'
        ]);
    }
}
