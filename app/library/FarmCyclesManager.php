<?php
/***********************************************************
 * FarmCyclesManager.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/29/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/
namespace App\library;

use App\models\Inventory\Customers;
use Validator;
use Input;
use Symfony\Component\Console\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMAccounts;
use App\models\FnB\FnbOrders;
use App\models\System\Companies;
use App\models\Sales\Orders;
use App\models\System\Currency;
use App\models\Inventory\Stocks;


class FarmCyclesManager
{

    public function GenerateCycleCode($params = array())
    {
        $company_id     = isset( $params['company_id']) ? $params['company_id'] : session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_customers = Customers::whereIcIsDeleted(0)->count();

        $index = $count_customers + 1;


        $customer_code = "FC" . sprintf('%04d', $index);

        // check if customer code exist

        $code_count = Customers::where('ic_customer_code',$customer_code)->count();
        if($code_count > 0)
        {
            $index = $count_customers + 2;


            $customer_code = "FC" . sprintf('%04d', $index);
        }

        unset($code_count);

        return $customer_code;

    }

}
