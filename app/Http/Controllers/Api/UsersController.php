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
use App\models\System\Companies;
use App\models\System\Languages;
use App\models\System\Currency;
use App\models\System\CurrencyExchangeRates;
use App\models\Users\UserTeam;
use App\models\Users\TeamMembers;
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

            $tokenResponse = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
                'username' => $user_name,
                'password' => $password,
                'scope' => '*',
            ]);

            if (!$tokenResponse->ok()) {
                return response()->json([
                    'is_error' => 1,
                    'error_message' => 'Token issue failed',
                    'details' => $tokenResponse->json(),
                ], 401);
            }

            $tokenData = $tokenResponse->json();



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
            $result_array['token_type'] = $tokenData['token_type'];
            $result_array['expires_in'] = $tokenData['expires_in'];
            $result_array['access_token'] = $tokenData['access_token'];

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

                $result_array['company_id']                     = $company_id;
                $result_array['company_country']                = $company_info->cd_company_country;
                $result_array['currency_symbol']                = $currency_info->cc_currency_code;
                $result_array['company_currency']               = $currency_id;
                $result_array['sec_currency_symbol']            = $sec_currency_info->cc_currency_code;
                $result_array['sec_currency_id']                = $sec_currency_id;
                $result_array['company_logo']                   = $company_logo;
                $result_array['warehouse_id']                   = $store_warehouses[0]->sw_warehouse_id;
                $result_array['store_id']                       = $store_id;

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



        return response()->json($result_array)
            ->cookie(
                'refresh_token',
                $tokenData['refresh_token'],
                60 * 24 * 30,
                null,
                null,
                true,
                true
            );

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
        $user_info = Users::where('u_is_active', 1)->where('u_attendance_code', $pin)->first();

        if (!$user_info) {
            return response()->json([
                'is_error' => 1,
                'error_message' => 'Invalid PIN'
            ]);
        }
        // Auth::login($user_info);

        $tokenResult = $user_info->createToken('POS-PIN');
        $accessToken = $tokenResult->accessToken;
        $refreshToken = $tokenResult->token->refresh_token ?? null;

        $g_hash = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $g_hash = hash('sha256', $g_hash);

        $profile_path = public_path() . '/' . \Config::get('constants.USERS_PATH')
            . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;

        $profile_url = url('/') . '/' . \Config::get('constants.USERS_PATH')
            . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;

        if (!is_file($profile_path)) {
            $profile_url = url('images/NoImageAvailable.jpg');
        }

        $company_id = $user_info->fk_company_id;

        $result_array['is_error'] = 0;
        $result_array['g_hash'] = $g_hash;
        $result_array['user_id'] = $user_info->id;
        $result_array['user_profile_url'] = $profile_url;
        $result_array['user_fullname'] = $user_info->u_fullname;
        $result_array['user_email'] = $user_info->u_email;
        $result_array['user_name'] = $user_info->u_username;
        $result_array['user_type'] = $user_info->u_user_type;
        $result_array['u_department_id'] = $user_info->u_department_id;
        $result_array['company_id'] = $company_id;
        $result_array['token_type'] = 'Bearer';
        $result_array['expires_in'] = 31536000;
        $result_array['access_token'] = $accessToken;

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

            $result_array['company_id']                     = $company_id;
            $result_array['company_country']                = $company_info->cd_company_country;
            $result_array['currency_symbol']                = $currency_info->cc_currency_code;
            $result_array['company_currency']               = $currency_id;
            $result_array['sec_currency_symbol']            = $sec_currency_info->cc_currency_code;
            $result_array['sec_currency_id']                = $sec_currency_id;
            $result_array['company_logo']                   = $company_logo;
            $result_array['warehouse_id']                   = $store_warehouses[0]->sw_warehouse_id;
            $result_array['store_id']                       = $store_id;

            // calculate exchange rate of primary and seconday
            $exchange_rate = CurrencyExchangeRates::whereErFromCurrency($currency_id)->whereErToCurrency($sec_currency_id)->orderBy('er_date_exchange', 'DESC')->get();
            if (count($exchange_rate) > 0)
                $result_array['exchange_rate']             = $exchange_rate[0]['er_exchange_rate'];
            else
                $result_array['exchange_rate']             = 1;
        }

        return response()->json($result_array)
            ->cookie(
                'refresh_token',
                $refreshToken,
                60 * 24 * 30,
                null,
                null,
                true,
                true
            );
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

        $c_hash              = "POS567" . $user_info->u_username . $user_info->u_fullname . $user_info->u_email . "POS567";
        $c_hash              =  hash('sha256', $c_hash);
        $result_array        = array();
        $users_array         = array();


        if ($c_hash != $g_hash) {
            $result_array['is_error']       = 1;
            $result_array['error_message']  = 'hash sequence is not valid !!';

            return Response()->json($result_array);
        }

        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();

        foreach ($lst_users as $key => $user_info) {
            $users_array[] = array(
                'id' => $user_info->id,
                'username' => $user_info->u_username,
                'fullname' => $user_info->u_fullname
            );
        }

        $result_array['is_error']        = 0;
        $result_array['error_message']   = 'Operation Completed Successfully';
        $result_array['lst_users']       = $users_array;
        return Response()->json($result_array);
    }
}
