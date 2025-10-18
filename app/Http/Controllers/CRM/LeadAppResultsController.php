<?php
/***********************************************************
CaseStatusController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\CRM;

use App\Http\Controllers\CallCenter\json;
use App\Http\Controllers\CallCenter\unknown;
use App\Http\Controllers\Controller;
use App\models\CallCenter\ApptResults;
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
use App\models\CallCenter\CaseStatus;


class LeadAppResultsController extends Controller
{

    /**
     * Page to control Case Statuses Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_results_parents = ApptResults::whereArIsDeleted(0)->get();

        $data = array(
            "lst_results_parents" => $lst_results_parents
        );
        return Response()->view('leads.appresults',$data);
    }


   /**
    * Display list of App Results saved in the database
    *
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {
        $general_search = $request->input('general_search');

        $results_cond = ApptResults::whereArIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $results_cond = $results_cond->where("ar_app_result","LIKE","%" . $general_search . "%");
        }

        $lst_app_results     = $results_cond->orderBy("ar_id","asc")->get();

        $data = array(
            "lst_app_results" => $lst_app_results
        );

        $result_array = array();

        $result_array['display'] = view("leads.listappresults",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_results_parents = ApptResults::whereArIsDeleted(0)->get();

        $data = array(
            "lst_results_parents" => $lst_results_parents
        );
        return view('leads.addappresult',$data);
    }


    /**
     * Save App Result Info to saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveAppResultInfo(Request $request)
    {
        $ar_id                     = $request->input('ar_id');
        $ar_app_result          = $request->input('ar_app_result');
        $ar_app_description          = $request->input('ar_app_description');
        $ar_result_color         = $request->input('ar_result_color');
        $ar_result_parent        = $request->input('ar_result_parent');
        $ar_app_show_apt        = $request->has('ar_app_show_apt') ? 1 : 0;

        $result_array = array();


        $appresult_info     = new ApptResults();
        if($ar_id != null)
        {
            $appresult_info = ApptResults::find($ar_id);
        }

        $appresult_info->ar_app_result        = $ar_app_result;
        $appresult_info->ar_app_description        = $ar_app_description;
        $appresult_info->ar_result_color        = $ar_result_color;
        $appresult_info->ar_result_parent        = $ar_result_parent;
        $appresult_info->ar_app_show_apt        = $ar_app_show_apt;



        $appresult_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'App Result Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page for App Result
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ar_id )
    {
        $result_info = ApptResults::find($ar_id);
        $lst_appresults = ApptResults::whereArIsDeleted(0)->whereNotIn('ar_id',array( $ar_id ))->get();


        $data = array(
            "result_info" => $result_info,
            "lst_results_parents" => $lst_appresults
        );
        return view('leads.editappresult',$data);
    }


    /**
     * Delete Supplier status from the database by change flag is_deleted of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteAppResultInfo(Request $request)
    {

        $ar_id = $request->input('ar_id');

        $app_result = ApptResults::find( $ar_id);
        $app_result->ar_is_deleted          = 1;
        $app_result->ar_deleted_by          = Session('user_id');
        $app_result->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
