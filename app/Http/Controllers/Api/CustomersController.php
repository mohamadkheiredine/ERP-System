<?php
/***********************************************************
CustomersController.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 8, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
API Requests for Customers
 ***********************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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


class CustomersController extends Controller
{


    /**
     * get list of customers saved in the database
     *
     * @author Moe Mantach
     * @access public
     *
     * @return json result_array
     */
    public function GetListCustomers(Request $request)
    {

        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $customer_search     = $request->input('searchquery');
        $current_page = $request->input('current_page');
        $customer_type = $request->input('customer_type');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256',$c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if( $c_hash != $g_hash )
        {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $nbr_rows_per_pages    = 10;
        if($current_page > 1)
            $skip = ( $current_page - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $customers_cond    = Customers::whereIcIsDeleted(0);
        if(strlen($customer_search) > 0)
            $customers_cond    = $customers_cond->where('ic_customer_name','LIKE','%' . $customer_search. '%');


        if ($customer_type && $customer_type !== "All") {
            $customers_cond = $customers_cond->where('ic_customer_type', str_replace("-", "_", $customer_type));
        }


        $customers_count = $customers_cond->count();


        $total_pages = ceil( $customers_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_customers_obj = $customers_cond->skip($skip)->take($nbr_rows_per_pages)->get();

        $result_array = array();
        $customers_array = array();
        $AccountingManager = new AccountingManager();


        foreach ($lst_customers_obj as $index => $customer_info)
        {
            $customers_array[$index]['ic_id']                   = $customer_info->ic_id;
            $customers_array[$index]['ic_customer_code']        = $customer_info->ic_customer_code;
            $customers_array[$index]['ic_customer_name']        = $customer_info->ic_customer_name;
            $customers_array[$index]['ic_customer_address']     = $customer_info->ic_customer_address;
            $customers_array[$index]['ic_customer_email']       = $customer_info->ic_customer_email;
            $customers_array[$index]['ic_customer_website']     = $customer_info->ic_customer_website;
            $customers_array[$index]['ic_customer_phone']       = $customer_info->ic_customer_phone;
            $customers_array[$index]['ic_customer_mobile']      = $customer_info->ic_customer_mobile;
            $customers_array[$index]['ic_account_number']       = $customer_info->ic_account_number;;
            $customers_array[$index]['ic_customer_type'] = $customer_info->ic_customer_type;
            $customers_array[$index]['ic_is_active'] = $customer_info->ic_is_active;
            $customers_array[$index]['ic_loyality_point'] = $customer_info->ic_loyality_point;






            $customer_account_id = $customer_info->ic_account_number;

            // getlist of movement related to the current Account Statment
            //$lst_movements          = TransactionMovements::where("tm_ledger_account","=",$customer_account_id)->orWhere("tm_sub_ledger_account","=",$customer_account_id)->get();
            //$total_customer_data    = $AccountingManager->GetTotalAccountBalance($lst_movements);
            //$customers_array[$index]['total_customer_data']       = $total_customer_data;

            $image_src_url  = url('/') . "/" . Config::get('constants.CUSTOMERS_PATH') . $customer_info->ic_image_base_src . $customer_info->ic_image_file_name . "." . $customer_info->ic_image_extension;
            $image_src_path = public_path() . "/" . Config::get('constants.CUSTOMERS_PATH') . $customer_info->ic_image_base_src . $customer_info->ic_image_file_name . "." . $customer_info->ic_image_extension;
            if (strlen($customer_info->ic_image_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }

            $customers_array[$index]['customer_profile']    = $img_src;
        }

        $result_array['is_error']               = 0;
        $result_array['customers_array']        = $customers_array;
        $result_array['total_pages']       = $total_pages;

        return Response()->json($result_array);
    }



    public function GetListCustomerslight(Request $request)
    {

        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $customer_search     = $request->input('searchquery');
        $current_page = $request->input('current_page');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $nbr_rows_per_pages    = 10;
        if ($current_page > 1)
            $skip = ($current_page - 1) * $nbr_rows_per_pages;
        else
            $skip = 0;


        $customers_cond    = Customers::whereIcIsDeleted(0);

        $customers_count = $customers_cond->count();

        $lst_customers_obj = $customers_cond->get();

        $result_array = array();
        $customers_array = array();
        $AccountingManager = new AccountingManager();


        foreach ($lst_customers_obj as $index => $customer_info) {
            if ($customer_info->ic_customer_name != null) {
                $customers_array[] = array(
                    'id' =>  $customer_info->ic_id,
                    'label' =>  $customer_info->ic_customer_name,
                );
            }
        }

        $result_array['is_error']               = 0;
        $result_array['customers_array']        = $customers_array;

        return Response()->json($result_array);
    }

    public function DeleteCustomerInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $customer_id         = $request->input('customer_id');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();


        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $customer_res = Customers::find($customer_id)->delete();

        $result_array['is_error']       = 0;
        $result_array['error_msg']       = "Delete Customer Completed Successfully";


        return Response()->json($result_array);
    }

    /**
     * get customer info of a customer_id and send it in the json response
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return JSON $result_array
     */
    public function GetCustomerInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $customer_id         = $request->input('customer_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $customer_array      = array();

        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $customer_info = Customers::find($customer_id);

        $customer_array['ic_id']                    = $customer_info->ic_id;
        $customer_array['ic_customer_code']         = $customer_info->ic_customer_code;
        $customer_array['ic_customer_name']         = $customer_info->ic_customer_name;
        $customer_array['ic_customer_address']      = $customer_info->ic_customer_address;
        $customer_array['ic_customer_email']        = $customer_info->ic_customer_email;
        $customer_array['ic_customer_website']      = $customer_info->ic_customer_website;
        $customer_array['ic_customer_phone']        = $customer_info->ic_customer_phone;
        $customer_array['ic_customer_mobile']       = $customer_info->ic_customer_mobile;
        $customer_array['ic_birth_date']       = $customer_info->ic_birth_date;
        $customer_array['ic_hobbies']       = $customer_info->ic_hobbies;
        $customer_array['ic_customer_type'] = $customer_info->ic_customer_type;
        $customer_array['ic_is_active'] = $customer_info->ic_is_active;
        $customer_array['ic_loyality_point'] = $customer_info->ic_loyality_point;




        $image_src_url  = url('/') . "/" . Config::get('constants.CUSTOMERS_PATH') . $customer_info->ic_image_base_src . $customer_info->ic_image_file_name . "." . $customer_info->ic_image_extension;
        $image_src_path = public_path() . "/" . Config::get('constants.CUSTOMERS_PATH') . $customer_info->ic_image_base_src . $customer_info->ic_image_file_name . "." . $customer_info->ic_image_extension;
        if (strlen($customer_info->ic_image_base_src) > 0) {
            $img_src = $image_src_url;
        } else {
            $img_src = url('images/NoImageAvailable.jpg');
        }

        $customer_array['customer_profile']    = $img_src;

        $result_array['is_error']       = 0;
        $result_array['customer_info']  = $customer_array;

        unset($customer_array);
        $customer_array = null;

        return Response()->json($result_array);
    }


    /**
     * Search Customer Information By Mobile
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function SearchCustomer(Request $request)
    {
        $sc_customer_mobile = $request->input('sc_customer_mobile');
        $customer_info = Customers::where('ic_customer_mobile', 'LIKE', '%' . $sc_customer_mobile . '%')->get();

        $result_array = array();
        $customer_name = "";

        if (count($customer_info) == 0) {
            $result_array['is_error']       = 1;
            $result_array['error_message'] = 'Customer Not Found';
            return Response()->json($result_array);
        }



        $result_array['is_error'] = 0;
        $result_array['customer']['name'] = $customer_info[0]->ic_customer_name;
        $result_array['customer']['id'] = $customer_info[0]->ic_id;
        $result_array['customer']['address'] = $customer_info[0]->ic_customer_address;
        $result_array['customer']['ic_customer_phone'] = $customer_info[0]->ic_customer_phone;
        $result_array['customer']['ic_customer_email'] = $customer_info[0]->ic_customer_email;
        return Response()->json($result_array);
    }


    public function SearchCustomerByName(Request $request)
    {
        $sc_customer_name = $request->input('sc_customer_name');
        $customer_info = Customers::where('ic_customer_name', 'LIKE', '%' . $sc_customer_name . '%')->get();

        $result_array = array();
        $customer_data = array();


        if (count($customer_info) == 0) {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "Customer Does not Exist";
            return Response()->json($result_array);
        }

        $customer_data = array(
            'customer_id' => $customer_info[0]->ic_id,
            'customer_name' => $customer_info[0]->ic_customer_name,
            'customer_mobile' => $customer_info[0]->ic_customer_mobile,
            'customer_address' => $customer_info[0]->ic_customer_address,
        );

        $result_array['is_error'] = 0;
        $result_array['customer_data'] = json_encode($customer_data);
        return Response()->json($result_array);
    }

    /**
     * Save Customer Info Saved In the Database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveCustomerInfo(Request $request)
    {
        $user_id                = $request->input('user_id');
        $customer_id            = $request->has('customer_id') ? $request->input('customer_id') : 0;
        $ic_customer_name       = $request->input('ic_customer_name');
        $ic_customer_address    = $request->input('ic_customer_address');
        $ic_customer_email      = $request->input('ic_customer_email');
        $ic_customer_website    = $request->input('ic_customer_website');
        $ic_customer_phone      = $request->input('ic_customer_phone');
        $ic_customer_mobile     = $request->input('ic_customer_mobile');
        $ic_hobbies             = $request->input('ic_hobbies');
        $ic_birth_date             = $request->input('ic_birth_date');
        // $ic_favorite_foods             = $request->input('ic_favorite_foods');
        // $ic_work_title             = $request->input('ic_work_title');
        // $ic_sports             = $request->input('ic_sports');
        $ic_is_active = $request->input('ic_is_active');
        $ic_customer_type = $request->input('ic_customer_type');
        $ic_loyality_point = $request->input('ic_loyality_point');



        $g_hash                 = $request->input('g_hash');
        $user_info              = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $customer_array      = array();

        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $customer_manager = new CustomersManager();
        $params = array(
            'company_id' => $user_info->fk_company_id
        );
        $ic_customer_code = $customer_manager->GenerateCustomerCode($params);

        if ($customer_id != 0) {
            $customer_info = Customers::find($customer_id);
        } else {
            $customer_info = new Customers();
        }



        $customer_info->ic_customer_code    = $ic_customer_code;
        $customer_info->ic_customer_name    = $ic_customer_name;
        $customer_info->ic_customer_address = $ic_customer_address;
        $customer_info->ic_customer_email   = $ic_customer_email;
        $customer_info->ic_customer_website = $ic_customer_website;
        $customer_info->ic_customer_phone = $ic_customer_phone;
        $customer_info->ic_customer_mobile = $ic_customer_mobile;
        $customer_info->ic_hobbies = $ic_hobbies;
        $customer_info->ic_is_active = $ic_is_active;
        // $customer_info->ic_favorite_foods = $ic_favorite_foods;
        // $customer_info->ic_work_title = $ic_work_title;
        // $customer_info->ic_sports = $ic_sports;
        $customer_info->ic_customer_type = $ic_customer_type;
        $customer_info->ic_loyality_point = $ic_loyality_point;


        $customer_info->ic_birth_date = $ic_birth_date;


        if (isset($_FILES['ic_avatar_pic'])) {
            $image_data =  $customer_manager->UploadCustomersAvatar($customer_id);
            $customer_info->ic_image_base_src      = $image_data['data']['ic_image_base_src'];
            $customer_info->ic_image_file_name     = $image_data['data']['ic_file_name'];
            $customer_info->ic_image_extension     = $image_data['data']['ic_file_extension'];
        }

        // if this add new customer we create a new account and save it as account info
        if ($customer_id == 0) {
            $account_info   = ChartAccounts::where("aa_account_ref", "=", "4111")->get();
            $account_info = $account_info[0];

            $count   = ChartAccounts::where("aa_account_ref", "LIKE", "4111%")->count();

            $new_count      = $count + 1;
            $aa_account_ref = $account_info->aa_account . (string)sprintf('%05d', $new_count);

            $AccAccounting = new ChartAccounts();
            $AccAccounting->aa_parent_account   = $account_info->aa_id;
            $AccAccounting->aa_account_ref      = $aa_account_ref;
            $AccAccounting->aa_account          = $aa_account_ref;
            $AccAccounting->aa_sub_account      = $account_info->aa_id;
            $AccAccounting->aa_account_label    = $ic_customer_name;
            $AccAccounting->fk_country_id       = 0;
            $AccAccounting->save();

            $aa_id = $AccAccounting->aa_id;
            $customer_info->ic_account_number = $aa_id;
        }

        $customer_info->save();


        $result_array['is_error'] = 0;
        $result_array['customer_id'] = $customer_info->ic_id;
        $result_array['customer_name'] = $ic_customer_name;
        return Response()->json($result_array);
    }



    /**
     * Delete customer from the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteCustomer(Request $request)
    {
        $customer_id         = $request->input('customer_id');
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();

        // validate hash sequence for loggedin user
        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $customer_info = Customers::find($customer_id);
        $customer_info->ic_is_deleted = 1;
        $customer_info->ic_deleted_by = $user_id;
        $customer_info->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";

        return Response()->json($result_array);
    }

    public function FindCustomer(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');

        $customer = Customers::where('ic_customer_name', $name)
            ->orWhere('ic_customer_phone', $phone)
            ->where('ic_is_deleted', 0)
            ->first();

        if ($customer) {
            return response()->json([
                'is_error' => 0,
                'exists' => true,
                'customer_id' => $customer->ic_id
            ]);
        }

        return response()->json([
            'is_error' => 0,
            'exists' => false,
            'customer_id' => 0
        ]);
    }
}
