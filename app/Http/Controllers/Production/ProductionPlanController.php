<?php
/***********************************************************
ProductionPlanController.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 14, 2020
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
use App\library\ClientsCategoriesManager;
use App\models\Sales\OrderStatus;
use App\models\Production\PlanStatus;
use App\models\Production\ProductionPlan;
use App\models\Inventory\Customers;
use App\models\Users\Users;
use App\library\ProdPlansManager;
use App\models\Production\PlanItems;
use App\models\Inventory\Products;
use App\models\System\Departments;
use App\library\DepartmentsManager;
use App\models\Users\UserTeam;
use App\models\Production\CheckStatus;
use App\models\Production\QualityCheck;



class ProductionPlanController extends Controller
{

    /**
     * Page to Manage Production Planning
     * 
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_plan_status = PlanStatus::wherePsIsDeleted(0)->get();
        
        $data = array(
            "lst_plan_status" => $lst_plan_status
        );
        return Response()->view('production.plans',$data);
    }
    
    
    /**
     * Display list of Production Plan
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
    
        $ps_plan_status = $request->input('ps_plan_status');
        $lst_production_plan = ProductionPlan::wherePpIsDeleted(0);
        if($ps_plan_status != '')
        {
            $lst_production_plan = $lst_production_plan->wherePpPlanStatus($ps_plan_status);
        }
        
        $lst_production_plan = $lst_production_plan->get();
        
        $data = array(
            "lst_production_plan" => $lst_production_plan
        );
        
        $result_array = array(); 
        $result_array['display'] = view("production.listplans",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of products entered to this plan to be produce
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayLisproducts(Request $request)
    {
        $pp_id = $request->input('pp_id');
        
        $lst_plan_items = PlanItems::wherePiProductId($pp_id)->wherePiIsDeleted(0)->get();
        
        
        $data = array(
            "lst_plan_items" => $lst_plan_items
        );
        $result_array = array();
        $result_array['display'] = view("production.listproductsplan",$data)->render();
        
        return Response()->json($result_array);
    }
    
    /**
     * Function of Adding a new Plan Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $plan_manag         = new ProdPlansManager();
        $lst_plan_status    = PlanStatus::wherePsIsDeleted(0)->get();
        $lst_customers      = Customers::whereIcIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $plan_code          = $plan_manag->GeneratePlanCode();
        unset($plan_manag);
        $data = array(
            "lst_plan_status" => $lst_plan_status,
            "lst_customers" => $lst_customers,
            "lst_users" => $lst_users,
            "plan_code" => $plan_code,
        );
        return view('production.addplan',$data);
    }
    
    
    /**
     * Save Order Order Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveProductionPlanInfo(Request $request)
    {
        $pp_id                      = $request->input('pp_id');
        $pp_plan_code               = $request->input('pp_plan_code');
        $pp_plan_label              = $request->input('pp_plan_label');
        $pp_production_manager      = $request->input('pp_production_manager');
        $pp_plan_status             = $request->input('pp_plan_status');
        $pp_customer_id             = $request->input('pp_customer_id');
        $pp_prepare_date            = $request->input('pp_prepare_date');
        $pp_start_date              = $request->input('pp_start_date');
        $pp_end_date                = $request->input('pp_end_date');
        $pp_finish_date             = $request->input('pp_finish_date');
        $pp_plan_description        = $request->input('pp_plan_description');
        $pp_estimation_time         = $request->input('pp_estimation_time');
        $timer_hours                = $request->input('timer_hours');
        $timer_minutes              = $request->input('timer_minutes');
        $timer_seconds              = $request->input('timer_seconds');
        
        
        $result_array = array();
 
        
        $product_plan = new ProductionPlan();
        if( $pp_id != null )
        {
            $product_plan = ProductionPlan::find($pp_id);
            $product_plan->pp_real_duration             = $timer_hours. ":" . $timer_minutes . ":" . $timer_seconds;
        }
        else {
            $product_plan->pp_estimation_time           = $pp_estimation_time;
        }
         
        $product_plan->pp_plan_code                 = $pp_plan_code;
        $product_plan->pp_plan_label                = $pp_plan_label;
        $product_plan->pp_production_manager        = $pp_production_manager;
        $product_plan->pp_plan_status               = $pp_plan_status;
        $product_plan->pp_customer_id               = $pp_customer_id;
        $product_plan->pp_prepare_date              = $pp_prepare_date;
        $product_plan->pp_start_date                = $pp_start_date;
        $product_plan->pp_end_date                  = $pp_end_date;
        $product_plan->pp_finish_date               = $pp_finish_date;
        $product_plan->pp_plan_description          = $pp_plan_description;

        
        $product_plan->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Plan Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    /**
     * Save Plan item information
     * 
     * @author Moe Mantach
     * @access public
     */
    public function SavePlanItemInfo( Request $request )
    {
        $fk_plan_id         = $request->input('fk_plan_id');
        $pi_item_product    = $request->input('pi_item_product');
        $pi_item_quanity    = $request->input('pi_item_quanity');
        $result_array = array();
        
        if($pi_item_product == null || $pi_item_product == 0)
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'Please Select A Product Before Continue';
            
            return Response()->json($result_array);
        }
        
        if($pi_item_quanity == null || $pi_item_quanity == 0)
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'Please Add A Quantity Before Continue';
            
            return Response()->json($result_array);
        }
        
        
        
        $product_info = Products::find($pi_item_product);
        
        
        $plan_item = new PlanItems();
        $plan_item->pi_plan_id      = $fk_plan_id;
        $plan_item->pi_product_id   = $pi_item_product;
        $plan_item->pi_item_label   = $product_info->p_product_name;
        $plan_item->pi_total_price  = $product_info->p_product_selling_price * $pi_item_quanity;
        $plan_item->pi_price_currency= $product_info->p_product_currency;
        $plan_item->pi_item_quanity     = $pi_item_quanity;
        
        $plan_item->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Plan Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Begin Production of the Plan
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function StartProductionPlan(Request $request)
    {
        $pp_id = $request->input('pp_id');
        
        $production_plan = ProductionPlan::find($pp_id);
        
        // check if the Plan Approved before start production 
        if($production_plan->pp_approval_user == 0)
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = 'Production Plan Need to Be Approved before start production';
            
            return Response()->json($result_array);
        }
        
        
        
        // change production to in production status
        $production_plan->pp_plan_status        = ProdPlansManager::PLAN_STATUS_INPRODUCTION;
        $production_plan->pp_run_production     = 1;
        $production_plan->pp_start_production   = 1;
        $production_plan->pp_production_pause   = 0;
        $production_plan->pp_production_block   = 0;
        $production_plan->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Has Been Started !!';
        
        return Response()->json($result_array);
    }
    
    /**
     * Pause Production when it already started
     * @param Request $request
     * @return unknown
     */
    public function PauseProductionPlan(Request $request)
    {
        $pp_id = $request->input('pp_id');
        
        $production_plan = ProductionPlan::find($pp_id);
        
        // change production to in production status
        $production_plan->pp_plan_status        = ProdPlansManager::PLAN_STATUS_PLANNING;
        $production_plan->pp_start_production   = 0;
        $production_plan->pp_production_pause   = 1;
        $production_plan->pp_production_block   = 0;
        $production_plan->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Has Been Paused !!';
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Block Production and save log of production
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function BlockProductionPlan(Request $request)
    {
        $pp_id = $request->input('pp_id');
        
        $production_plan = ProductionPlan::find($pp_id);
        
        // change production to in production status 
        $production_plan->pp_start_production   = 0;
        $production_plan->pp_production_pause   = 0;
        $production_plan->pp_production_block   = 1;
        $production_plan->pp_plan_status        = ProdPlansManager::PLAN_STATUS_BLOCK;
        $production_plan->save();
        
        
        $result_array['is_error']           = 0;
        $result_array['production_status']  = ProdPlansManager::PLAN_STATUS_BLOCK;
        $result_array['error_msg']          = 'Production Has Been Blocked !!';
        
        return Response()->json($result_array);
    }
  
    
    
    /**
     * Assign Plan to a selected User from the dropdown and
     * save log for the plan
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function AssignPlanTo( Request $request )
    {
        $fk_plan_id         = $request->input('fk_plan_id');
        $pp_assign_to       = $request->input('pp_assign_to'); 
        $result_array = array();
        
 
        
        
        $plan_info= ProductionPlan::find($fk_plan_id); 
        $plan_info->pp_assign_to= $pp_assign_to;
        
        $plan_info->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Plan Assigned Has been saved';
        
        return Response()->json($result_array);
    }
    
    /**
     * Approve Production Plan to start Production 
     * @param Request $request
     * @return unknown
     */
    public function ProductionPlanApproval( Request $request )
    {
        $fk_plan_id         = $request->input('fk_plan_id');
        $pp_approve_note    = $request->input('pp_approve_note'); 
        $result_array       = array();
        
 
        $plan_info  = ProductionPlan::find($fk_plan_id); 
        $plan_info->pp_approve_note     = $pp_approve_note;
        $plan_info->pp_approval_date    = date("Y-m-d");
        $plan_info->pp_approval_user    = session('user_id');
        
        $plan_info->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Production Plan Has Been Approval';
        
        return Response()->json($result_array);
    }
    
    /**
     * Display Edit Order Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $os_id
     */
    public function EditForm( $ps_id )
    { 
        $plan_info          = ProductionPlan::find($ps_id);
        $lst_plan_status    = PlanStatus::wherePsIsDeleted(0)->get();
        $lst_customers      = Customers::whereIcIsDeleted(0)->get();
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->wherePProductType(1)->get();
        $lst_teams          = UserTeam::whereUtIsDeleted(0)->get();
        $lst_check_status   = CheckStatus::whereCsIsDeleted(0)->get();
        
        $lst_prod_dep_users =  Users::whereUIsDeleted(0)->whereUIsActive(1)->whereUDepartmentId(DepartmentsManager::DEPARTMENT_PRODUCTION)->get();
        $real_estimation    = $plan_info->pp_real_duration;
        $timer_array = explode(":", $real_estimation);
        
        $data = array(
            "plan_info" => $plan_info,
            "lst_plan_status" => $lst_plan_status,
            "lst_teams" => $lst_teams,
            "lst_customers" => $lst_customers,
            "lst_products" => $lst_products,
            "lst_users" => $lst_users,
            "lst_check_status" => $lst_check_status,
            "timer_array" => $timer_array,
            "lst_prod_dep_users" => $lst_prod_dep_users
        );
        return view('production.editplan',$data);
    }
    
    
    /**
     * Delete Production Plan Information by change the flag of pp_is_deleted from 0
     * to 1
     * 
     * @author Moe Mantach
     * @param Request $request
     * @return unknown
     */
    public function DeleteProductionPlan(Request $request)
    {
        
        $pp_id= $request->input('pp_id');
         
        $production_plan = ProductionPlan::find( $pp_id);
        $production_plan->pp_is_deleted    = 1;
        $production_plan->pp_deleted_by    = Session('user_id');
        $production_plan->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Create Quality Check and save info to the database realted
     * to the plan 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function CreateQualityCheck( Request $request )
    {
        $fk_plan_id     = $request->input('fk_plan_id');
        $qc_check_label = $request->input('qc_check_label');
        $qc_team_id     = $request->input('qc_team_id');
        $qc_product_id  = $request->input('qc_product_id');
        $qc_status_id   = $request->input('qc_status_id');
        $qc_description = $request->input('qc_description');
        $result_array   = array();
        
        
        $quality_check = new QualityCheck();
        $quality_check->fk_plan_id      = $fk_plan_id;
        $quality_check->qc_check_label  = $qc_check_label;
        $quality_check->qc_team_id      = $qc_team_id;
        $quality_check->qc_product_id   = $qc_product_id;
        $quality_check->qc_created_by   = session('user_id');
        $quality_check->qc_status_id    = $qc_status_id;
        $quality_check->qc_description  = $qc_description;
        $quality_check->save();
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    /**
     * Open Popup for page of quality check
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function EditQualityCheck(Request $request)
    {
        $qc_id = $request->input('qc_id');
        
        $quality_check = QualityCheck::find($qc_id);
    }
    
    /**
     * Delete Product aand remove link between product and plan
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteProductInfo(Request $request)
    {
        
        $pi_id= $request->input('pi_id');
         
        $plan_items = PlanItems::find( $pi_id);
        $plan_items->pi_is_deleted    = 1;
        $plan_items->pi_deleted_by    = Session('user_id');
        $plan_items->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}