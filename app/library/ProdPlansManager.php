<?php
/***********************************************************
ProdPlansManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 16, 2020
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
use App\models\System\Companies;
use App\models\MRP\BillOfMaterials;
use App\models\Production\ProductionPlan;


class ProdPlansManager
{
    const PLAN_STATUS_PENDING           = 1;
    const PLAN_STATUS_PLANNING          = 2;
    const PLAN_STATUS_INPRODUCTION      = 3;
    const PLAN_STATUS_BLOCK             = 4;
    const PLAN_STATUS_MAINTENANCE       = 5;
    
    /**
     * Generate a Plan Code to saveit in the database
     * 
     * @author Moe mantach
     * @access public
     * @return string
     */
    public function GeneratePlanCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_plans = ProductionPlan::wherePpIsDeleted(0)->count();
        
        $index = $count_plans+ 1;
        
        
        $plans_code = "PLAN" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $plans_code;
        
    }
}