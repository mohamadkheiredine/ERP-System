<?php
/***********************************************************
BOMManager.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 29, 2019
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
use App\models\System\Companies;
use App\models\MRP\BillOfMaterials;


class BOMManager
{
    
    /**
     * Generate BOM Code to save it in the database
     * 
     * @author Moe Mantach
     * @access public
     * @return string
     */
    public function GenerateBomCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_bom = BillOfMaterials::whereBmIsDeleted(0)->count();
        
        $index = $count_bom + 1;
        
        
        $bom_code = "BOM" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $bom_code;
        
    }
}