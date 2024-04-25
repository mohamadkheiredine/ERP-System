<?php
/***********************************************************
BankingManager.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 20, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\library;


use Validator;
use Input;
use Config;
use Session;
use Redirect;
use Crypt;
use Cookie;
use Auth;
use DB;
use File;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\ProductCategories;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMAccounts;
use App\models\Billing\InvoiceProducts;
use App\models\Accounting\VatAccounts;
use App\models\Billing\Invoices;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\CRM\CRMServices;
use App\models\System\Companies;
use App\models\Billing\Receipts;
use App\models\Accounting\BankAccounts;


class BankingManager
{
    
    /**
     * Generate Code/Reference based on created account
     * 
     * @author Moe Mantach
     * @access public
     * 
     * @return string $account_code
     */
    public function GetAccountCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_accounts = BankAccounts::whereYear('ba_creation_date' , $year)->count();
        
        $index = $count_accounts + 1;
        
        
        $account_code = "AC" . strtoupper($cd_company_name[0]) . $year . sprintf('%04d', $index);
        
        return $account_code;
    }
    
}