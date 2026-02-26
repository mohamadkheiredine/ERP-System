<?php
/***********************************************************
ServicesController.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Dec 29, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Users\Users;
use App\models\CRM\CRMServiceCategories;

class ServicesController extends Controller
{
    /**
     * @author Mohammed kheiredine
     */
    public function GetListServiceCategories(Request $request)
    {
        $g_hash  = $request->input('g_hash');
        $user_id = $request->input('user_id');

        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'hash sequence is not valid !!';
            return Response()->json($result_array);
        }

        $lst_categories = CRMServiceCategories::whereScIsDeleted(0)->get();

        $categories_array = [];
        foreach ($lst_categories as $index => $category_info) {
            $categories_array[$index]['sc_id'] = $category_info->sc_id;
            $categories_array[$index]['sc_company_id'] = $category_info->sc_company_id;
            $categories_array[$index]['fk_category_id'] = $category_info->fk_category_id;
            $categories_array[$index]['sc_category_ref'] = $category_info->sc_category_ref;
            $categories_array[$index]['sc_category_name'] = $category_info->sc_category_name;
            $categories_array[$index]['sc_category_description'] = $category_info->sc_category_description ?? '';
            $categories_array[$index]['sc_profile_base_src'] = $category_info->sc_profile_base_src ?? '';
            $categories_array[$index]['sc_profile_file_name'] = $category_info->sc_profile_file_name ?? '';
            $categories_array[$index]['sc_profile_extension']= $category_info->sc_profile_extension ?? '';
        }

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = '';
        $result_array['lst_categories'] = $categories_array;

        return Response()->json($result_array);
    }
}
