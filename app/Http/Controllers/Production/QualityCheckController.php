<?php
/***********************************************************
QualityCheckController.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 2, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/



namespace App\Http\Controllers\Production;

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
use App\models\CRM\CRMClientCategories;
use App\Library\ClientsCategoriesManager;
use App\models\Sales\OrderStatus;
use App\models\Production\PlanStatus;
use App\models\Production\QualityCheck;



class QualityCheckController extends Controller
{
    
    /**
     * Display list of the quality check for the production plan
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayList( Request $request )
    {
        $fk_plan_id = $request->input('fk_plan_id');
        
        $lst_quality_check = QualityCheck::whereFkPlanId($fk_plan_id)->get();
        $result_array = array();
        
        $data = array(
            "lst_quality_check" => $lst_quality_check
        );
        $result_array['is_error']   = 0;
        $result_array['display']  = view('production.listqualitycheck',$data)->render();
        
        return Response()->json($result_array);
    }
}