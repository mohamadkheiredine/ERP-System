<?php

/***********************************************************
UsersController.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

 ***********************************************************/


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\models\Sales\StoreEmployees;
use App\models\Sales\Stores;
use App\models\Sales\StoreWarehouses;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Config;
use File;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\System\JobTitles;
use App\models\System\JobRoles;
use App\models\System\Departments;
use App\models\System\Roles;
use App\Library\UsersManager;
use App\models\Sales\PosAllowedCurrencies;
use App\models\System\Companies;
use App\models\System\Languages;
use App\models\System\Currency;
use App\models\System\CurrencyExchangeRates;
use App\models\Users\UserTeam;
use App\models\Users\TeamMembers;
use App\models\Users\UserTypes;
use App\models\Users\UserAllowedCompanies;
use App\models\Inventory\WareHouses;
use App\models\Timesheet\EmploymentType;
use App\models\Billing\PaymentTypes;
use App\models\PayRolls\PayrollsPaymentMethods;
use App\models\Accounting\ChartAccounts;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{

    /**
     * Login to the POS Systemm and return with information for user loggedin
     *
     * @author Moe Mantach
     * @param Request $request
     */
    public function LoginPOS(Request $request)
    {
        $user_name   = $request->input('user_name');
        $password    = $request->input('password');
        $ua_remember = $request->input('ua_remember');
        $result_array = array();
        if (Auth::attempt(array('u_username' => $user_name, 'password' => $password))) {
            $user_info          = Auth::user();

            //            $tokenResponse = Http::asForm()->post(url('/oauth/token'), [
            //                'grant_type' => 'password',
            //                'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            //                'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            //                'username' => $user_name,
            //                'password' => $password,
            //                'scope' => '*',
            //            ]);
            //
            //            if (!$tokenResponse->ok()) {
            //                return response()->json([
            //                    'is_error' => 1,
            //                    'error_message' => 'Token issue failed',
            //                    'details' => $tokenResponse->json(),
            //                ], 401);
            //            }

            //$tokenData = $tokenResponse->json();



            $g_hash             = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
            $g_hash             =  hash('sha256', $g_hash);



            $profile_path     = public_path() . '/' . Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            $profile_url = url('/') . '/' . Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            if (!is_file($profile_path)) {
                $profile_url = url('images/NoImageAvailable.jpg');
            }

            $company_id = $user_info->fk_company_id;

            $result_array['is_error']                   = 0;
            $result_array['g_hash']                     = $g_hash;
            $result_array['user_id']                    = $user_info->id;
            $result_array['user_profile_url']           = $profile_url;
            $result_array['user_fullname']              = $user_info->u_fullname;
            $result_array['user_email']                 = $user_info->u_email;
            $result_array['user_name']                  = $user_info->u_username;
            $result_array['user_type']                  = $user_info->u_user_type;
            $result_array['u_department_id']            = $user_info->u_department_id;
            $result_array['company_id']                 = $company_id;
            //            $result_array['token_type'] = $tokenData['token_type'];
            //            $result_array['expires_in'] = $tokenData['expires_in'];
            //            $result_array['access_token'] = $tokenData['access_token'];

            if ($company_id > 0) {

                $company_info = Companies::find($company_id);
                $company_logo_src_url  = url('/') . "/" . Config::get('constants.COMPANY_PATH') . $company_info->cd_logo_base_src . $company_info->cd_logo_file_name . "." . $company_info->cd_logo_file_extension;

                if (strlen($company_info->cd_logo_base_src) > 0) {
                    $company_logo = $company_logo_src_url;
                } else {
                    $company_logo = url('images/NoImageAvailable.jpg');
                }

                $currency_id    = $company_info->cd_company_currency;
                $currency_info  = Currency::find($currency_id);

                $sec_currency_id    = $company_info->cd_secondary_currency;
                $sec_currency_info  = Currency::find($sec_currency_id);

                $store_employees = StoreEmployees::whereSeEmployeeId($user_info->id)->get();

                if (count($store_employees) == 0) {
                    $result_array = array();

                    $result_array['is_error']                   = 1;
                    $result_array['error_message']                    = 'not linked to Any Store';
                    return Response()->json($result_array);
                }

                $store_id = $store_employees[0]->se_store_id;

                $store_info = Stores::find($store_id);

                $store_warehouses = StoreWarehouses::where('sw_store_id', $store_id)->get();

                if (count($store_warehouses) == 0) {
                    $result_array = array();

                    $result_array['is_error']                   = 1;
                    $result_array['error_message']                    = 'not Warehouse Assign For this Store';
                    return Response()->json($result_array);
                }

                $allowed_currencies = PosAllowedCurrencies::where('ac_store_id', $store_id)
                    ->join('currency', 'currency.cc_id', '=', 'pos_allowed_currencies.ac_currency_id')
                    ->select(
                        'currency.cc_id',
                        'currency.cc_currency_code',
                        'currency.cc_currency_name',
                        'pos_allowed_currencies.ac_rate_to_original'
                    )
                    ->get();

                $result_array['company_id']                     = $company_id;
                $result_array['company_country']                = $company_info->cd_company_country;
                $result_array['currency_symbol']                = $currency_info->cc_currency_code;
                $result_array['company_currency']               = $currency_id;
                $result_array['sec_currency_symbol']            = $sec_currency_info->cc_currency_code;
                $result_array['sec_currency_id']                = $sec_currency_id;
                $result_array['company_logo']                   = $company_logo;
                $result_array['warehouse_id']                   = $store_warehouses[0]->sw_warehouse_id;
                $result_array['store_id']                       = $store_id;
                $result_array['allowed_currencies'] = $allowed_currencies;

                // calculate exchange rate of primary and seconday
                $exchange_rate = CurrencyExchangeRates::whereErFromCurrency($currency_id)->whereErToCurrency($sec_currency_id)->orderBy('er_date_exchange', 'DESC')->get();
                if (count($exchange_rate) > 0)
                    $result_array['exchange_rate']             = $exchange_rate[0]['er_exchange_rate'];
                else
                    $result_array['exchange_rate']             = 1;
            }
        } else {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'Invalid Username and Password';
        }



        return response()->json($result_array);
    }

    public function LoginPOSByPin(Request $request)
    {
        $pin = $request->input('pin');
        $result_array = [];

        if (strlen($pin) !== 5 || !ctype_digit($pin)) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'Invalid PIN format'
            ]);
        }

        $user_info = Users::where('u_is_active', 1)
            ->where('u_attendance_code', $pin)
            ->first();

        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'Invalid PIN'
            ]);
        }

        $g_hash = "POS567"
            . $user_info->u_username
            . $user_info->u_fullname
            . $user_info->u_email
            . "POS567";

        $g_hash = hash('sha256', $g_hash);

        $profile_path = public_path() . '/' . Config::get('constants.USERS_PATH')
            . $user_info->u_avatar_base_src
            . $user_info->u_avatar_filename . '.'
            . $user_info->u_avatar_extentions;

        $profile_url = url('/') . '/' . Config::get('constants.USERS_PATH')
            . $user_info->u_avatar_base_src
            . $user_info->u_avatar_filename . '.'
            . $user_info->u_avatar_extentions;

        if (!is_file($profile_path)) {
            $profile_url = url('images/NoImageAvailable.jpg');
        }

        $company_id = $user_info->fk_company_id;


        $result_array['is_error']          = 0;
        $result_array['g_hash']            = $g_hash;
        $result_array['user_id']           = $user_info->id;
        $result_array['user_profile_url']  = $profile_url;
        $result_array['user_fullname']     = $user_info->u_fullname;
        $result_array['user_email']        = $user_info->u_email;
        $result_array['user_name']         = $user_info->u_username;
        $result_array['user_type']         = $user_info->u_user_type;
        $result_array['u_department_id']   = $user_info->u_department_id;
        $result_array['company_id']        = $company_id;

        if ($company_id > 0) {

            $company_info = Companies::find($company_id);

            $company_logo_src_url = url('/') . "/"
                . Config::get('constants.COMPANY_PATH')
                . $company_info->cd_logo_base_src
                . $company_info->cd_logo_file_name . "."
                . $company_info->cd_logo_file_extension;

            if (strlen($company_info->cd_logo_base_src) > 0) {
                $company_logo = $company_logo_src_url;
            } else {
                $company_logo = url('images/NoImageAvailable.jpg');
            }

            $currency_id       = $company_info->cd_company_currency;
            $currency_info     = Currency::find($currency_id);

            $sec_currency_id   = $company_info->cd_secondary_currency;
            $sec_currency_info = Currency::find($sec_currency_id);

            $store_employees = StoreEmployees::whereSeEmployeeId($user_info->id)->get();

            if (count($store_employees) == 0) {
                return response()->json([
                    'is_error' => 1,
                    'error_message' => 'not linked to Any Store'
                ]);
            }

            $store_id = $store_employees[0]->se_store_id;

            $store_warehouses = StoreWarehouses::where('sw_store_id', $store_id)->get();

            if (count($store_warehouses) == 0) {
                return response()->json([
                    'is_error' => 1,
                    'error_message' => 'not Warehouse Assign For this Store'
                ]);
            }


            $allowed_currencies = PosAllowedCurrencies::where('ac_store_id', $store_id)
                ->join('currency', 'currency.cc_id', '=', 'pos_allowed_currencies.ac_currency_id')
                ->select(
                    'currency.cc_id',
                    'currency.cc_currency_code',
                    'currency.cc_currency_name',
                    'pos_allowed_currencies.ac_rate_to_original'
                )
                ->get();


            $result_array['company_id']          = $company_id;
            $result_array['company_country']     = $company_info->cd_company_country;
            $result_array['currency_symbol']     = $currency_info->cc_currency_code;
            $result_array['company_currency']    = $currency_id;
            $result_array['sec_currency_symbol'] = $sec_currency_info->cc_currency_code;
            $result_array['sec_currency_id']     = $sec_currency_id;
            $result_array['company_logo']        = $company_logo;
            $result_array['warehouse_id']        = $store_warehouses[0]->sw_warehouse_id;
            $result_array['store_id']            = $store_id;
            $result_array['allowed_currencies']  = $allowed_currencies;


            $exchange_rate = CurrencyExchangeRates::whereErFromCurrency($currency_id)
                ->whereErToCurrency($sec_currency_id)
                ->orderBy('er_date_exchange', 'DESC')
                ->get();

            if (count($exchange_rate) > 0)
                $result_array['exchange_rate'] = $exchange_rate[0]['er_exchange_rate'];
            else
                $result_array['exchange_rate'] = 1;
        }

        return response()->json($result_array);
    }



    /**
     * Logout user from the system and reset the autontication
     * validate the hash sequence loggedin
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function LogoutPOS(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id             = $request->input('user_id');
        $user_info          = Users::find($user_id);

        $c_hash             = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash             =  hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
        } else {
            $request->user()->token()->revoke();
            Auth::logout($user_id);
            $result_array['is_error']       = 0;
        }


        return Response()->json($result_array);
    }


    /**
     * Get User Profile info saved in the database to dispay it
     * in my profie page
     *
     * information returned by the user profile
     * u_username : username
     * u_fullname : fullname
     * u_email : email
     * u_phone : phone
     * u_mobile : mobile
     * u_website : website
     * u_gender : gender of the user
     * u_date_birth : date of birth of the user
     * u_avatar : avatar profile of the user
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GetUserInfo(Request $request)
    {
        $g_hash             = $request->input('g_hash');
        $user_id            = $request->input('user_id');
        $user_info          = Users::find($user_id);

        $c_hash             = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash             =  hash('sha256', $c_hash);




        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
        } else {

            $image_src_url  = url('/') . "/" . Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            $image_src_path = public_path() . "/" . Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            if (strlen($user_info->u_avatar_base_src) > 0) {
                $img_src = $image_src_url;
            } else {
                $img_src = url('images/NoImageAvailable.jpg');
            }


            $result_array['is_error']           = 0;
            $result_array['u_username']         = $user_info->u_username;
            $result_array['u_fullname']         = $user_info->u_fullname;
            $result_array['u_email']            = $user_info->u_email;
            $result_array['u_phone']            = $user_info->u_phone;
            $result_array['u_mobile']           = $user_info->u_mobile;
            $result_array['u_website']          = $user_info->u_website;
            $result_array['u_gender']           = $user_info->u_gender;
            $result_array['u_date_birth']       = $user_info->u_date_birth;
            $result_array['user_id']            = $user_info->id;
            $result_array['u_avatar']           = $img_src;
        }


        return Response()->json($result_array);
    }


    /**
     * set my profile info and save it in the database
     *
     * u_username : username
     * u_fullname : fullname
     * u_email : email
     * u_phone : phone
     * u_mobile : mobile
     * u_website : website
     * u_gender : gender of the user
     * u_date_birth : date of birth of the user
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SetmyprofileInfo(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $u_username          = $request->input('u_username');
        $u_fullname          = $request->input('u_fullname');
        $u_email             = $request->input('u_email');
        $u_phone             = $request->input('u_phone');
        $u_mobile            = $request->input('u_mobile');
        $u_website           = $request->input('u_website');
        $u_gender            = $request->input('u_gender');
        $u_date_birth        = $request->input('u_date_birth');
        $user_info          = Users::find($user_id);

        $c_hash             = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash             =  hash('sha256', $c_hash);




        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';
        } else {
            $user_info->u_username   = $u_username;
            $user_info->u_fullname   = $u_fullname;
            $user_info->u_email      = $u_email;
            $user_info->u_phone      = $u_phone;
            $user_info->u_mobile     = $u_mobile;
            $user_info->u_website    = $u_website;
            $user_info->u_gender     = $u_gender;
            $user_info->u_date_birth = date("Y-m-d", strtotime($u_date_birth));
            $user_info->save();

            $result_array['is_error']       = 0;
            $result_array['error_message']  = 'Operation Completed Successfully';
        }


        return Response()->json($result_array);
    }


    /**
     * get list of members inside a specific team
     * @param Request $request
     */
    public function GetTeamMembers(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $team_name              = $request->input('team_name');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);




        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $user_team = UserTeam::where('ut_team', 'LIKE', '%' . $team_name .  '%')->get();
        $team_id = 0;
        $users_array = array();
        if (count($user_team) > 0) {
            $team_id = $user_team[0]->ut_id;
            $lst_users = $user_team[0]->TeamMembers;

            foreach ($lst_users as $key => $user_info) {

                $users_array[] = array(
                    'id' => $user_info->Users->id,
                    'fullname' => $user_info->Users->u_fullname,
                );
            }
        }

        $result_array['is_error'] = 0;
        $result_array['users_array'] = $users_array;


        return Response()->json($result_array);
    }


    /**
     * Change profile password based on sent user_id and return if changing success or not
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ChangeprofilePassword(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
        $old_password        = $request->input('old_password');
        $new_password        = $request->input('new_password');
        $user_info           = Users::find($user_id);

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);




        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }


        $hash_old_password   = hash('sha256', $old_password);

        if ($hash_old_password != $user_info->password) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'Old Password is Incorrect, re-enter the correct password';

            return Response()->json($result_array);
        }

        $user_info->password = $new_password;
        $user_info->save();


        $result_array['is_error']       = 0;
        $result_array['error_message']  = 'Operation Completed Successfully';

        return Response()->json($result_array);
    }


    /**
     * get list of users
     * @param Request $request
     */
    public function GetListUsers(Request $request)
    {
        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');

        $user_info           = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $users_array         = array();

        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $page_number  = max(1, (int) $request->input('page_number', 1));
        $search_query = $request->input('search_query', '');
        $per_page     = 10;
        $skip         = ($page_number - 1) * $per_page;

        $query = Users::whereUIsDeleted(0);
        if (!empty($search_query)) {
            $query->where(function ($q) use ($search_query) {
                $q->where('u_username', 'LIKE', '%' . $search_query . '%')
                  ->orWhere('u_fullname', 'LIKE', '%' . $search_query . '%')
                  ->orWhere('u_email',    'LIKE', '%' . $search_query . '%');
            });
        }

        $total_records = $query->count();
        $total_pages   = max(1, (int) ceil($total_records / $per_page));

        $total_active   = Users::whereUIsDeleted(0)->whereUIsActive(1)->count();
        $total_inactive = Users::whereUIsDeleted(0)->whereUIsActive(0)->count();

        $lst_users = $query->skip($skip)->take($per_page)->get();

        foreach ($lst_users as $usr) {
            $avatar_url = '';
            if ($usr->u_avatar_base_src && $usr->u_avatar_filename && $usr->u_avatar_extentions) {
                $avatar_url = url('/') . '/' . Config::get('constants.USERS_PATH') . $usr->u_avatar_base_src . $usr->u_avatar_filename . '.' . $usr->u_avatar_extentions;
            }
            $users_array[] = array(
                'id'         => $usr->id,
                'username'   => $usr->u_username,
                'fullname'   => $usr->u_fullname,
                'email'      => $usr->u_email,
                'phone'      => $usr->u_phone,
                'mobile'     => $usr->u_mobile,
                'is_active'  => $usr->u_is_active,
                'gender'     => $usr->u_gender,
                'avatar_url' => $avatar_url,
            );
        }

        $result_array['is_error']        = 0;
        $result_array['error_message']   = 'Operation Completed Successfully';
        $result_array['lst_users']       = $users_array;
        $result_array['total_pages']     = $total_pages;
        $result_array['total_records']   = $total_records;
        $result_array['current_page']    = $page_number;
        $result_array['total_active']    = $total_active;
        $result_array['total_inactive']  = $total_inactive;
        return Response()->json($result_array);
    }

    public function GetUsersFormData(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $edit_user_id = $request->input('edit_user_id');

        $lst_roles           = Roles::whereRoleIsDeleted(0)->get(['role_id', 'role_name']);
        $lst_user_types      = UserTypes::all(['ut_id', 'ut_user_type']);
        $lst_companies       = Companies::whereCdIsDeleted(0)->get(['cd_id', 'cd_company_name']);
        $lst_langs           = Languages::all(['lm_id', 'lm_lang_name']);
        $lst_job_titles      = JobTitles::whereJtIsDeleted(0)->get(['jt_id', 'jt_job_title']);
        $lst_job_roles       = JobRoles::whereJrIsDeleted(0)->get(['jr_id', 'jr_job_role']);
        $lst_departments     = Departments::whereSdIsDeleted(0)->get(['sd_id', 'sd_department_title']);
        $lst_warehouses      = WareHouses::whereWIsDeleted(0)->get(['w_id', 'w_warehouse_name']);
        $lst_employment_type = EmploymentType::whereEtIsDeleted(0)->get(['et_id', 'et_type']);
        $lst_payment_types   = PaymentTypes::wherePtIsDeleted(0)->get(['pt_id', 'pt_payment_type']);

        $rand = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);

        $result = [
            'is_error'            => 0,
            'lst_roles'           => $lst_roles,
            'lst_user_types'      => $lst_user_types,
            'lst_companies'       => $lst_companies,
            'lst_langs'           => $lst_langs,
            'lst_job_titles'      => $lst_job_titles,
            'lst_job_roles'       => $lst_job_roles,
            'lst_departments'     => $lst_departments,
            'lst_warehouses'      => $lst_warehouses,
            'lst_employment_type' => $lst_employment_type,
            'lst_payment_types'   => $lst_payment_types,
            'rand'                => $rand,
            'allowed_companies'   => [],
            'payroll_payment_method' => null,
            'edit_user_info'      => null,
        ];

        if ($edit_user_id) {
            $edit_user = Users::find($edit_user_id);
            if ($edit_user) {
                $result['edit_user_info'] = [
                    'u_attendance_code'       => $edit_user->u_attendance_code,
                    'u_user_type'             => $edit_user->u_user_type,
                    'fk_role_id'              => $edit_user->fk_role_id,
                    'u_lang_id'               => $edit_user->u_lang_id,
                    'u_address'               => $edit_user->u_address,
                    'u_residential_area'      => $edit_user->u_residential_area,
                    'u_date_birth'            => $edit_user->u_date_birth,
                    'u_fax'                   => $edit_user->u_fax,
                    'u_website'               => $edit_user->u_website,
                    'u_marital_status'        => $edit_user->u_marital_status,
                    'u_number_of_dependencies'=> $edit_user->u_number_of_dependencies,
                    'fk_company_id'           => $edit_user->fk_company_id,
                    'u_department_id'         => $edit_user->u_department_id,
                    'fk_warehouse_id'         => $edit_user->fk_warehouse_id,
                    'u_job_role_id'           => $edit_user->u_job_role_id,
                    'u_job_title_id'          => $edit_user->u_job_title_id,
                    'u_employee_type'         => $edit_user->u_employee_type,
                    'u_employment_date'       => $edit_user->u_employment_date,
                    'u_daily_working_hours'   => $edit_user->u_daily_working_hours,
                    'u_user_sallary'          => $edit_user->u_user_sallary,
                    'u_sales_commission'      => $edit_user->u_sales_commission,
                    'u_hourly_rate'           => $edit_user->u_hourly_rate,
                    'u_number_holidays'       => $edit_user->u_number_holidays,
                    'u_has_insurance'         => $edit_user->u_has_insurance,
                    'u_cnss_number'           => $edit_user->u_cnss_number,
                ];
                $result['allowed_companies'] = UserAllowedCompanies::whereAcUserId($edit_user_id)
                    ->pluck('ac_company_id')
                    ->toArray();
                $result['payroll_payment_method'] = PayrollsPaymentMethods::where('pm_employee_id', $edit_user_id)->first();
            }
        }

        return Response()->json($result);
    }

    /* ─────────────────────────────────────────────────────────────────────
     * Separate list endpoints — each list cached independently on the POS
     * ───────────────────────────────────────────────────────────────────── */

    public function GetListRoles(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = Roles::whereRoleIsDeleted(0)->get(['role_id', 'role_name']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListUserTypes(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = UserTypes::all(['ut_id', 'ut_user_type']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListCompanies(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = Companies::whereCdIsDeleted(0)->get(['cd_id', 'cd_company_name']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListLangs(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = Languages::all(['lm_id', 'lm_lang_name']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListJobTitles(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = JobTitles::whereJtIsDeleted(0)->get(['jt_id', 'jt_job_title']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListJobRoles(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = JobRoles::whereJrIsDeleted(0)->get(['jr_id', 'jr_job_role']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListDepartments(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = Departments::whereSdIsDeleted(0)->get(['sd_id', 'sd_department_title']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListWarehouses(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = WareHouses::whereWIsDeleted(0)->get(['w_id', 'w_warehouse_name']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListEmploymentTypes(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = EmploymentType::whereEtIsDeleted(0)->get(['et_id', 'et_type']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetListPaymentTypes(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $items = PaymentTypes::wherePtIsDeleted(0)->get(['pt_id', 'pt_payment_type']);

        return Response()->json(['is_error' => 0, 'items' => $items]);
    }

    public function GetUserEditData(Request $request)
    {
        $user_id      = $request->input('user_id');
        $g_hash       = $request->input('g_hash');
        $edit_user_id = $request->input('edit_user_id');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_message' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_message' => 'hash sequence is not valid !!']);
        }

        $edit_user = Users::find($edit_user_id);

        if (!$edit_user) {
            return Response()->json(['is_error' => 1, 'error_message' => 'User not found']);
        }

        $edit_user_info = [
            'u_attendance_code'        => $edit_user->u_attendance_code,
            'u_user_type'              => $edit_user->u_user_type,
            'fk_role_id'               => $edit_user->fk_role_id,
            'u_lang_id'                => $edit_user->u_lang_id,
            'u_address'                => $edit_user->u_address,
            'u_residential_area'       => $edit_user->u_residential_area,
            'u_date_birth'             => $edit_user->u_date_birth,
            'u_fax'                    => $edit_user->u_fax,
            'u_website'                => $edit_user->u_website,
            'u_marital_status'         => $edit_user->u_marital_status,
            'u_number_of_dependencies' => $edit_user->u_number_of_dependencies,
            'fk_company_id'            => $edit_user->fk_company_id,
            'u_department_id'          => $edit_user->u_department_id,
            'fk_warehouse_id'          => $edit_user->fk_warehouse_id,
            'u_job_role_id'            => $edit_user->u_job_role_id,
            'u_job_title_id'           => $edit_user->u_job_title_id,
            'u_employee_type'          => $edit_user->u_employee_type,
            'u_employment_date'        => $edit_user->u_employment_date,
            'u_daily_working_hours'    => $edit_user->u_daily_working_hours,
            'u_user_sallary'           => $edit_user->u_user_sallary,
            'u_sales_commission'       => $edit_user->u_sales_commission,
            'u_hourly_rate'            => $edit_user->u_hourly_rate,
            'u_number_holidays'        => $edit_user->u_number_holidays,
            'u_has_insurance'          => $edit_user->u_has_insurance,
            'u_cnss_number'            => $edit_user->u_cnss_number,
        ];

        $allowed_companies      = UserAllowedCompanies::whereAcUserId($edit_user_id)->pluck('ac_company_id')->toArray();
        $payroll_payment_method = PayrollsPaymentMethods::where('pm_employee_id', $edit_user_id)->first();

        return Response()->json([
            'is_error'               => 0,
            'edit_user_info'         => $edit_user_info,
            'allowed_companies'      => $allowed_companies,
            'payroll_payment_method' => $payroll_payment_method,
        ]);
    }

    public function SaveUser(Request $request)
    {
        $user_id = $request->input('user_id');
        $g_hash  = $request->input('g_hash');

        $user_info = Users::find($user_id);

        if (!$user_info) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'Unauthorized']);
        }

        $c_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash = hash('sha256', $c_hash);

        if ($c_hash != $g_hash) {
            return Response()->json(['is_error' => 1, 'error_msg' => 'hash sequence is not valid !!']);
        }

        $id = $request->input('id');

        // User Info
        $u_fullname        = $request->input('u_fullname');
        $u_username        = $request->input('u_username');
        $password          = $request->input('password');
        $u_attendance_code = $request->input('u_attendance_code');
        $u_user_type       = $request->input('u_user_type');
        $fk_role_id        = $request->input('fk_role_id');
        $u_lang_id         = $request->input('u_lang_id');
        $u_address         = $request->input('u_address');
        $u_is_active       = (int) $request->input('u_is_active', 0);
        $allowed_companies = $request->input('allowed_companies');

        // Personal Info
        $u_gender                 = $request->input('u_gender');
        $u_residential_area       = $request->input('u_residential_area');
        $u_email                  = $request->input('u_email');
        $u_date_birth             = $request->input('u_date_birth');
        $u_mobile                 = $request->input('u_mobile');
        $u_phone                  = $request->input('u_phone');
        $u_fax                    = $request->input('u_fax');
        $u_website                = $request->input('u_website');
        $u_marital_status         = $request->input('u_marital_status');
        $u_number_of_dependencies = $request->input('u_number_of_dependencies');

        // Employment Info
        $fk_company_id         = $request->input('fk_company_id');
        $u_department_id       = $request->input('u_department_id');
        $fk_warehouse_id       = $request->input('fk_warehouse_id', 0);
        $u_job_role_id         = $request->input('u_job_role_id');
        $u_job_title_id        = $request->input('u_job_title_id');
        $u_employee_type       = $request->input('u_employee_type');
        $u_employment_date     = $request->input('u_employment_date');
        $u_daily_working_hours = $request->input('u_daily_working_hours');
        $u_user_sallary        = $request->input('u_user_sallary');
        $u_sales_commission    = $request->input('u_sales_commission');
        $u_hourly_rate         = $request->input('u_hourly_rate');
        $u_number_holidays     = $request->input('u_number_holidays', 0);
        $u_has_insurance       = (int) $request->input('u_has_insurance', 0);
        $u_cnss_number         = $request->input('u_cnss_number');
        $pm_id                 = $request->input('pm_id');
        $pm_payment_method     = $request->input('pm_payment_method');

        // Username uniqueness check for new users
        if ($id == null) {
            $count_users = Users::whereUIsDeleted(0)->where('u_username', $u_username)->count();
            if ($count_users > 0) {
                return Response()->json(['is_error' => 1, 'error_msg' => 'Username already exists!']);
            }
        }

        if ($id != null) {
            $Users = Users::find($id);
        } else {
            $Users = new Users();
            $Users->u_is_deleted = 0;
        }

        // ChartAccounts creation
        $account_info = ChartAccounts::where("aa_account_ref", "=", "421")->first();
        if ($account_info) {
            if ($id != null) {
                if ($Users->u_account_id == 0) {
                    $count          = ChartAccounts::where("aa_account_ref", "LIKE", "421%")->count();
                    $aa_account_ref = $account_info->aa_account . sprintf('%03d', $count + 1);
                    $acc = new ChartAccounts();
                    $acc->aa_parent_account = $account_info->aa_id;
                    $acc->aa_account_ref    = $aa_account_ref;
                    $acc->aa_account        = $aa_account_ref;
                    $acc->aa_sub_account    = $account_info->aa_id;
                    $acc->aa_account_label  = $u_fullname;
                    $acc->fk_country_id     = 0;
                    $acc->save();
                    $Users->u_account_id = $acc->aa_id;
                }
                if ($Users->u_comission_account_id == 0) {
                    $count_com          = ChartAccounts::where("aa_account_ref", "LIKE", "421%")->count();
                    $aa_account_ref_com = $account_info->aa_account . sprintf('%03d', $count_com + 1);
                    $acc_com = new ChartAccounts();
                    $acc_com->aa_parent_account = $account_info->aa_id;
                    $acc_com->aa_account_ref    = $aa_account_ref_com;
                    $acc_com->aa_account        = $aa_account_ref_com;
                    $acc_com->aa_sub_account    = $account_info->aa_id;
                    $acc_com->aa_account_label  = $u_fullname . " Fixed Comission";
                    $acc_com->fk_country_id     = 0;
                    $acc_com->save();
                    $Users->u_comission_account_id = $acc_com->aa_id;
                }
            } else {
                $count          = ChartAccounts::where("aa_account_ref", "LIKE", "421%")->count();
                $aa_account_ref = $account_info->aa_account . sprintf('%03d', $count + 1);
                $acc = new ChartAccounts();
                $acc->aa_parent_account = $account_info->aa_id;
                $acc->aa_account_ref    = $aa_account_ref;
                $acc->aa_account        = $aa_account_ref;
                $acc->aa_sub_account    = $account_info->aa_id;
                $acc->aa_account_label  = $u_fullname;
                $acc->fk_country_id     = 0;
                $acc->save();
                $Users->u_account_id = $acc->aa_id;

                $count_com          = ChartAccounts::where("aa_account_ref", "LIKE", "421%")->count();
                $aa_account_ref_com = $account_info->aa_account . sprintf('%03d', $count_com + 1);
                $acc_com = new ChartAccounts();
                $acc_com->aa_parent_account = $account_info->aa_id;
                $acc_com->aa_account_ref    = $aa_account_ref_com;
                $acc_com->aa_account        = $aa_account_ref_com;
                $acc_com->aa_sub_account    = $account_info->aa_id;
                $acc_com->aa_account_label  = $u_fullname . " Fixed Comission";
                $acc_com->fk_country_id     = 0;
                $acc_com->save();
                $Users->u_comission_account_id = $acc_com->aa_id;
            }
        }

        // Set all user fields
        $Users->u_fullname                = $u_fullname;
        $Users->u_username                = $u_username;
        $Users->u_user_type               = $u_user_type;
        $Users->fk_role_id                = $fk_role_id;
        $Users->u_lang_id                 = $u_lang_id;
        $Users->u_address                 = $u_address;
        $Users->u_is_active               = $u_is_active;
        $Users->u_attendance_code         = $u_attendance_code;
        $Users->u_gender                  = $u_gender;
        $Users->u_residential_area        = $u_residential_area;
        $Users->u_email                   = $u_email;
        $Users->u_mobile                  = $u_mobile;
        $Users->u_phone                   = $u_phone;
        $Users->u_fax                     = $u_fax;
        $Users->u_website                 = $u_website;
        $Users->u_marital_status          = $u_marital_status;
        $Users->u_number_of_dependencies  = $u_number_of_dependencies;
        $Users->fk_company_id             = $fk_company_id;
        $Users->u_department_id           = $u_department_id;
        $Users->fk_warehouse_id           = $fk_warehouse_id ?: 0;
        $Users->u_job_role_id             = $u_job_role_id;
        $Users->u_job_title_id            = $u_job_title_id;
        $Users->u_employee_type           = $u_employee_type;
        $Users->u_daily_working_hours     = $u_daily_working_hours;
        $Users->u_user_sallary            = $u_user_sallary;
        $Users->u_sales_commission        = $u_sales_commission;
        $Users->u_hourly_rate             = $u_hourly_rate;
        $Users->u_number_holidays         = $u_number_holidays;
        $Users->u_has_insurance           = $u_has_insurance;
        $Users->u_cnss_number             = $u_cnss_number;

        if (!empty($u_date_birth)) {
            $Users->u_date_birth = date("Y-m-d", strtotime($u_date_birth));
        }
        if (!empty($u_employment_date)) {
            $Users->u_employment_date = date("Y-m-d", strtotime($u_employment_date));
        }

        if (!empty($password)) {
            $Users->password              = Hash::make($password);
            $Users->u_last_password_change = now();
        }

        // Avatar upload
        if ($request->hasFile('u_profile_pic')) {
            $UsersManager = new UsersManager();
            $upload = $UsersManager->UploadAvatarUsers($id);
            if ($upload['is_error'] == 0) {
                $image_data = $upload['data'];
                $Users->u_avatar_base_src   = $image_data['u_avatar_base_src'];
                $Users->u_avatar_filename   = $image_data['u_avatar_filename'];
                $Users->u_avatar_extentions = $image_data['u_avatar_extentions'];
            }
        }

        $Users->updated_at = now();
        $Users->save();
        $saved_user_id = $Users->id;

        // Save allowed companies
        if (!empty($allowed_companies)) {
            $companies_list = json_decode($allowed_companies, true);
            if (is_array($companies_list) && count($companies_list) > 0) {
                UserAllowedCompanies::whereAcUserId($saved_user_id)->delete();
                foreach ($companies_list as $company_id) {
                    $ac = new UserAllowedCompanies();
                    $ac->ac_company_id = $company_id;
                    $ac->ac_user_id    = $saved_user_id;
                    $ac->save();
                }
            }
        }

        // Auto-create warehouse for TECHNICIAN / SALES users if none assigned
        $saved_user = Users::find($saved_user_id);
        if (
            (empty($saved_user->fk_warehouse_id) || $saved_user->fk_warehouse_id == 0) &&
            ($u_user_type == UserTypes::USER_TYPE_TECHNICIAN || $u_user_type == UserTypes::USER_TYPE_SALES)
        ) {
            $warehouse_info = new WareHouses();
            $warehouse_info->w_company_id         = $fk_company_id;
            $warehouse_info->w_warehouse_ref      = $u_username;
            $warehouse_info->w_warehouse_name     = $u_fullname;
            $warehouse_info->w_warehouse_adddress = $u_address;
            $warehouse_info->w_owner_id           = $user_id;
            $warehouse_info->w_linked_to          = $saved_user_id;
            $warehouse_info->w_warehouse_status   = 1;
            $warehouse_info->save();
            $saved_user->fk_warehouse_id = $warehouse_info->w_id;
            $saved_user->save();
        }

        // Link new user to the admin's store (required for POS login)
        if ($id == null) {
            $store_id = $request->input('store_id');
            if (!empty($store_id)) {
                $store_emp = new StoreEmployees();
                $store_emp->se_employee_id = $saved_user_id;
                $store_emp->se_store_id    = $store_id;
                $store_emp->se_company_id  = $fk_company_id;
                $store_emp->save();
            }
        }

        // Save payroll payment method
        $payroll = new PayrollsPaymentMethods();
        if (!empty($pm_id) && $pm_id > 0) {
            $payroll = PayrollsPaymentMethods::find($pm_id) ?? new PayrollsPaymentMethods();
        }
        $payroll->pm_company_id  = $fk_company_id;
        $payroll->pm_employee_id = $saved_user_id;
        if (!empty($pm_payment_method)) {
            $payroll->pm_payment_method = $pm_payment_method;
        }
        $payroll->save();

        return Response()->json(['is_error' => 0, 'error_msg' => 'User saved successfully']);
    }

}
