<?php
/***********************************************************
LeadsStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CRM\unknown;
use App\Http\Controllers\CRM\View;
use App\models\Inventory\ProductCategories;
use App\models\System\SystemStatus;
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
use App\models\CRM\CRMLeadStatus;



class SystemStatusController extends Controller
{

    /**
     * Page to control Lead Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index(Request $request)
    {
        $ss_status_type = $request->input('ss_status_type');

        $lst_status = SystemStatus::whereSsIsDeleted(0)->get();
        $data = array(
            'ss_status_type' => $ss_status_type,
            'lst_statuses' => $lst_status
        );
        return Response()->view('system.status',$data);
    }


    /**
     * Display list of system Status saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {

        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $ss_status_type           = $request->input('ss_status_type');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $system_status_cond = SystemStatus::whereSsIsDeleted(0)->whereSsStatusType($ss_status_type);

        if(strlen($search_query) > 0)
            $system_status_cond = $system_status_cond->where('ss_status_title' , 'LIKE' , '%' . $search_query . '%');

        $system_status_count = $system_status_cond->count();


        $total_pages = ceil( $system_status_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_system_status = $system_status_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('ss_parent_status', 'ASC')->get();



        $data = array(
            "lst_system_status" => $lst_system_status
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("system.liststatuses",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Lead Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm(Request $request)
    {

        $lst_system_status = SystemStatus::whereSsIsDeleted(0)->get();
        $ss_status_type = $request->input('ss_status_type');

        $data = array(
            "lst_statuses" => $lst_system_status,
            "ss_status_type" => $ss_status_type,
        );
        return view('system.addstatus',$data);
    }


    /**
     * Save System Status Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveStatusInfo(Request $request)
    {
        $ss_id                      = $request->input('ss_id');
        $ss_parent_status                      = $request->input('ss_parent_status');
        $ss_status_type                      = $request->input('ss_status_type');
        $ss_status_title                      = $request->input('ss_status_title');
        $ss_status_description                      = $request->input('ss_status_description');
        $ss_status_color                      = $request->input('ss_status_color');


        $result_array = array();


        $systemstatus_info = new SystemStatus();
        if( $ss_id != null )
        {
            $systemstatus_info= SystemStatus::find($ss_id);
        }

        $systemstatus_info->ss_parent_status           = $ss_parent_status;
        $systemstatus_info->ss_status_type           = $ss_status_type;
        $systemstatus_info->ss_status_title           = $ss_status_title;
        $systemstatus_info->ss_status_description           = $ss_status_description;
        $systemstatus_info->ss_status_color           = $ss_status_color;

        $systemstatus_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'System Status Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit System Status Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $ls_id
     */
    public function EditForm( $ss_id )
    {
        $lst_system_status = SystemStatus::whereSsIsDeleted(0)->whereNotIn('ss_id',array($ss_id))->get();
        $status_info        = SystemStatus::find($ss_id);

        $data = array(
            "lst_statuses" => $lst_system_status,
            "status_info" => $status_info
        );
        return view('system.editstatus',$data);
    }


    /**
     * Delete System Status information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteStatusInfo(Request $request)
    {

        $ss_id= $request->input('ss_id');

        $system_status = SystemStatus::find( $ss_id);
        $system_status->ss_is_deleted          = 1;
        $system_status->ss_deleted_by          = Session('user_id');
        $system_status->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
