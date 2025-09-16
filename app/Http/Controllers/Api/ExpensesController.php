<?php
/***********************************************************
 * ExpensesController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/12/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\Expenses\ExpensesCategories;
use Validator;
use Input;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CustomersManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Inventory\Vendors;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\DefaultAccounts;
use App\models\Accounting\TransactionMovements;
use App\library\AccountingManager;


class ExpensesController extends Controller
{


    public function GetListExpenseCategories(Request $request)
    {

        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $customer_search = $request->input('searchquery');
        $current_page = $request->input('current_page');
        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $lst_categories = ExpensesCategories::whereEcIsDeleted(0)->get();
        $categories_array = array();
        foreach ($lst_categories as $category) {
            $categories_array[] = array(
                'id' => $category->ec_id,
                'category_label' => $category->ec_name,
            );
        }


        $result_array['categories_array'] = $categories_array;

        return Response()->json($result_array);

    }


    /**
     * Submit new Expense
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SubmitNewexpense(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash = $request->input('g_hash');
        $user_info = Users::find($user_id);

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);
        $result_array = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error'] = 1;
            $result_array['error_message'] = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        return Response()->json($result_array);
    }

}
