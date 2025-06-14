<?php

namespace App\Http\Controllers\Auth;

use App\models\Roles\RolePrivileges;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use Crypt;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\models\System\Companies;
use App\models\Users\Users;
use App\models\System\Currency;
use App\models\Accounting\VatAccounts;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/request/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function Login(Request $request)
    {
        $result_array = array();

        $username = $request->input('username');
        $password = $request->input('password');
        $ua_remember    = $request->input('ua_remember');
        $ua_remember    = ($ua_remember == null) ? false : true;





        if (Auth::attempt(array('u_username' => $username, 'password' => $password),$ua_remember))
        {
           $user_info          = Auth::user();
            // check if user type is administrator to be able to Enter the Admin Section
            /**if( $log_in_user_type != 1 || $log_in_user_type != $user_type )
             {
             $result_array['is_error'] = 1;
             $result_array['error_msg'] = "No Privilege to Enter In this Section";

             return Response()->json($result_array);
             }*/


            // Save information in the session
            self::SaveSessionInformaiton($user_info , $ua_remember);

            $result_array['is_error'] = 0;
            $result_array['company_homepage'] = Session("company_homepage");
        }
        else
        {
            $result_array['is_error'] = 1;
            $result_array['error_msg'] = "Invalid Username And/Or Password";
        }

        return Response()->json($result_array);
    }


    public static function SaveSessionInformaiton($user_info , $ua_remember )
    {
        $user_id = $user_info->id;
        $role_id = $user_info->fk_role_id;
        $company_id = $user_info->fk_company_id;
        $company_info = Companies::find($company_id);

        $company_tax = $company_info->cd_company_tax;

        if( $company_tax > 0 )
            $tax_info = VatAccounts::find($company_info->cd_company_tax);
        else
            $tax_info = new VatAccounts();

        $profile_path     = public_path().'/'.Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
        $profile_url = url('/').'/'.Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
        if(!is_file($profile_path))
        {
            $profile_url= url('images/NoImageAvailable.jpg');
        }

        $company_logo_src_url  = url('/')."/".Config::get('constants.COMPANY_PATH').$company_info->cd_logo_base_src.$company_info->cd_logo_file_name.".".$company_info->cd_logo_file_extension;

        if(strlen($company_info->cd_logo_base_src) > 0 ){
            $company_logo = $company_logo_src_url;
        }else{
            $company_logo = url('images/NoImageAvailable.jpg');
        }




        session()->put('user_id' , $user_id );
        session()->put('user_profile_url' , $profile_url);
        session()->put('user_fullname' , $user_info->u_fullname);
        session()->put('user_email' , $user_info->u_email);
        session()->put('user_name' , $user_info->u_username);
        session()->put('user_type' , $user_info->u_user_type);
        session()->put('u_department_id' , $user_info->u_department_id);
        session()->put('warehouse_id' , $user_info->fk_warehouse_id);
        session()->put('company_id' , $company_id);

        if($company_id > 0)
        {
            $currency_id    = $company_info->cd_company_currency;
            $currency_info  = Currency::find($currency_id);

            $secondary_currency_id      = $company_info->cd_secondary_currency;
            $secondary_currency_info    = Currency::find($secondary_currency_id);

            session()->put('company_country' , $company_info->cd_company_country);
            session()->put('company_tax_id' , $company_info->cd_company_tax);

            if(isset($tax_info) && $company_tax > "0.0")
                session()->put('company_tax_percentage' , $tax_info->av_vat_rate);
            else
                session()->put('company_tax_percentage' , 0);

            session()->put('company_currency' , $company_info->cd_company_currency);
            session()->put('currency_symbol' , $currency_info->cc_currency_code);
            session()->put('secondary_currency' , $company_info->cd_secondary_currency);
            session()->put('company_name_translation' , $company_info->cd_company_name_translation);
            session()->put('sec_currency_symbol' , $secondary_currency_info->cc_currency_code);
            session()->put('default_item' , $company_info->cd_default_item);
            session()->put('company_transportation_fees' , $company_info->cd_transportation_fees);
            session()->put('company_logo' ,$company_logo);
            session()->put('company_homepage' ,$company_info->cd_company_homepage);
            session()->put('company_starting_year' ,date("Y",strtotime($company_info->cd_starting_date)));
            session()->put('cd_exchange_rate' ,$company_info->cd_exchange_rate);
        }

        $role_info = RolePrivileges::getPrivileges($role_id);


        session()->put('role_info' ,json_encode($role_info));
        // save cookie to remember user id and information

        if($ua_remember == true)
        {
            $cookie_value =  $user_info->u_user_type . "-" . $user_id . "-" . $profile_url . "-" . $user_info->u_fullname . "-" . $user_info->u_email;
            $cookie_value = encrypt($cookie_value);

            setcookie('ua_remember_user', $cookie_value, time() + (86400 * 30), "/");

        }
    }

}
