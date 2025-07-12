<?php
/***********************************************************
WarehouseController.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
warehouse
***********************************************************/



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use Milon\Barcode\DNS1D;
use models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\VatAccounts;
use App\models\System\Currency;
use Swap\Swap;
use App\models\System\Units;
use App\models\Users\Users;

class WarehouseController extends Controller
{

    /**
     * get list of currencies saved in the database
     *
     * @author Moe Mantach
     * @access public
     */
    public function getlistcurrency(Request $request)
    {

        $user_id             = $request->input('user_id');
        $g_hash              = $request->input('g_hash');
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


        $lst_currencies = Currency::all();

        $currencies_array = array();

        foreach ( $lst_currencies as  $index => $currency_info )
        {
            $currencies_array[ $currency_info->cc_id ] = array(
                'currency_code' => $currency_info->cc_currency_code ,
                'currency_name' => $currency_info->cc_currency_name
            );
        }

        $result_array['currencies'] = $currencies_array;

        return Response()->json($result_array);
    }

}
