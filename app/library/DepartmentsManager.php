<?php
/***********************************************************
DepartmentsManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 20, 2020
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
use App\models\Inventory\Customers;


class DepartmentsManager
{
    
    const DEPARTMENT_MANAGEMENT = 1;
    const DEPARTMENT_ACCOUNTING = 2;
    const DEPARTMENT_PRODUCTION = 3;
    
    
    /**
     * Generate Array for Department to draw hierarchy 
     * 
     * @author Moe mantach
     * @access public
     * @param unknown $lst_departments
     */
    public function GenerateDepartmentsArray( $dep_array )
    {
        /**dd($dep_array);*/
    }
    
}