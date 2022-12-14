<?php
/***********************************************************
SRMManager.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 31, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Library;


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
use App\models\Inventory\Products;
use App\models\CRM\CRMContacts;
use App\models\CRM\CRMLogs;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeadNotes;
use App\models\System\Companies;
use App\models\SRM\SupplierContracts;
use App\models\SRM\SupplierQuotations;


class SRMManager
{
 
    
    /**
     * Generate Contract Code for Contract Supplier
     * 
     * @author Moe Mantach
     * @access public
     * @return string
     */
    public function GenerateContractCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_contract = SupplierContracts::whereYear('sc_creation_date' , $year)->count();
        
        $index = $count_contract + 1;
        
        
        $contract_code = "CNT-" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $contract_code;
        
    }
    
    
    /**
     * Generate Quotation Code to save it into the database
     * 
     * @author Moe Mantach
     * @access public
     * @return string
     */
    public function GenerateQuotationCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_quotations = SupplierQuotations::whereYear('sq_date_submit' , $year)->count();
        
        $index = $count_quotations+ 1;
        
        
        $quotation_code = "QUOT-" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $quotation_code;
        
    }
    
}