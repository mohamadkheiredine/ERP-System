<?php
/***********************************************************
CRMLogsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 13, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use App\library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\CRM\CRMLeadFiles;
use App\models\CRM\CRMLogs;



class CRMLogsController extends Controller
{
    /**
     * Display lead logs tab
     * 
     * @author Moe Mantach
     * @access public 
     * @param Request $request
     */
    public function DisplayLeadTabsTab(Request $request)
    {
        $cl_id = $request->input("cl_id");
        
        $CRMLogs = CRMLogs::whereFkLeadId($cl_id)->get();
        
        $result_array = array();
        
        $result_array['is_error'] = 0;
        $data = array(
            'CRMLogs' => $CRMLogs
        );
        $result_array['display'] = view("Leads.leadlogs",$data)->render();
        
        return Response()->json($result_array);
    }
}