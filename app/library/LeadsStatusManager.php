<?php
/***********************************************************
LeadsStatusManager.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 22, 2020
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
use App\models\Inventory\ProductCategories;
use App\models\Inventory\Products;
use Illuminate\Http\Request;
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseEmployees;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMLeadAppointments;


class LeadsStatusManager
{
    const STATUS_ATTEMPTED_TO_CONTACT   = 1;
    const STATUS_CONTACT_IN_FUTURE      = 2;
    const STATUS_CONTACTED              = 3;
    const STATUS_JUNK_LEAD              = 4;
    const STATUS_LOST_LEAD              = 5;
    const STATUS_NOT_CONTACTED          = 6;
    const STATUS_PRE_QUALIFIED          = 7;
    const STATUS_NOT_QUALIFIED          = 8;
    const STATUS_QUALIFIED              = 9;
    const STATUS_CONVERSION_TO_CLIENT   = 10;
    const STATUS_CONVERTED_TO_CLIENT    = 11;
}