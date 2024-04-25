<?php
/***********************************************************
PersonalizedGroupsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 18, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Accounting;

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
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use App\library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\Accounting\ChartAccounts;
use App\models\System\Countries;
use App\models\Accounting\AccountingJournals;
use App\models\Accounting\Journaltypes;
use App\models\Accounting\PersonalizedGroups;



class PersonalizedGroupsController extends Controller
{
    
    /**
     * Page Management for personalized group for accounting management
     * 
     * @author Moe Mantach
     * @access public
     */
    public function index()
    {
        
        $data = array();
        return Response()->view("accounting.personalizedgroups",$data);
    }
    
    
    /**
     * Display list of Personalized Groups saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        
        
        $lst_prez_groups = PersonalizedGroups::wherePgIsDeleted(0)->orderBy('pg_group_code', 'asc')->get();
 
        $data = array(
            "lst_prez_groups" => $lst_prez_groups, 
        );
        
        $result_array = array();
        
        $result_array['display'] = view("accounting.listpergroups",$data)->render();
        
        return Response()->json($result_array);
    }
    
    /**
     * Function of Adding a new Group
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $data = array();
        return view('accounting.addpergroup',$data);
    }
    
    
    /**
     * Save Personalized Group Record Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SavePersonalizedGroupInfo(Request $request)
    {
        $pg_id                      = $request->input('pg_id');
        $pg_group_code              = $request->input('pg_group_code');
        $pg_group_label             = $request->input('pg_group_label');
        $pg_group_comment           = $request->input('pg_group_comment');
        $pg_group_calculated        = $request->input('pg_group_calculated');
        $pg_group_formula           = $request->input('pg_group_formula');
        $pg_group_position          = $request->input('pg_group_position');
        $pg_is_active               = $request->input('pg_is_active');
        
        $result_array = array();
        
        
        $PersonalizedGroup  = new PersonalizedGroups();
        if($pg_id != null)
        {
            $PersonalizedGroup = PersonalizedGroups::find($pg_id);
        }
        
        $PersonalizedGroup->pg_group_code       = $pg_group_code;
        $PersonalizedGroup->pg_group_label      = $pg_group_label;
        $PersonalizedGroup->pg_group_comment    = $pg_group_comment;
        $PersonalizedGroup->pg_group_calculated = $pg_group_calculated;
        $PersonalizedGroup->pg_group_formula    = $pg_group_formula;
        $PersonalizedGroup->pg_group_position   = $pg_group_position;
        $PersonalizedGroup->pg_is_active        = $pg_is_active;
        
        $PersonalizedGroup->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Personalized Group Information Has been saved';
        
        return Response()->json($result_array);
    }
    

    /**
     * Display Edit Chart Accounting Account Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $aa_id
     */
    public function EditForm( $pg_id)
    {
        $personalized_group_info      = PersonalizedGroups::find($pg_id);
        
        $data = array(
            "personalized_group_info" => $personalized_group_info, 
        );
        return view('accounting.editpergroup',$data);
    }
    
    
    /**
     * Delete Personalized Group information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeletePersonalizedGroupInfo(Request $request)
    {
        
        $pg_id      = $request->input('pg_id');
        
        $PersonalizedGroup= PersonalizedGroups::find( $pg_id);
        $PersonalizedGroup->pg_is_deleted           = 1;
        $PersonalizedGroup->pg_deleted_by           = Session('user_id');
        $PersonalizedGroup->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
}