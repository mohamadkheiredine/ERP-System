<?php
/***********************************************************
MaintenanceManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

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
use Illuminate\Http\Request;
use App\models\Logistics\Vehicules;
use App\models\Inventory\Vendors;
use App\models\System\Companies;
use App\models\Maintenance\Jobs;


class MaintenanceManager
{
    
    public function GenerateJobCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_jobs = Jobs::whereJIsDeleted(0)->count();
        
        $index = $count_jobs + 1;
        
        
        $vendor_code = "JOB" . strtoupper($cd_company_name[0]) . $year . "-" . sprintf('%04d', $index);
        
        return $vendor_code;
        
    }

}