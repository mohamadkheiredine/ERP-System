<?php
/***********************************************************
ContractTypesController.php
Product : Care HMIS
Version : 1.0
Release : 1
Date Created : Jul 8, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/





namespace App\Http\Controllers\LMS;

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
use App\Library\ClientsCategoriesManager;
use App\models\HomeCare\VisitStatus;
use App\models\HomeCare\PlanStatus;
use App\models\HomeCare\PlanTypes;
use App\models\LMS\SampleTypes;



class ContractTypesController extends Controller
{

    /**
     * Page to control Sample Types Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('lms.sampletypes',$data);
    }


    /**
     * Display list of Sample Types saved in the database
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {

        $page_number           = $request->input('page_number');
        $general_search        = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;

            $sampletypes_cond = SampleTypes::wherestIsDeleted(0);

            if(strlen($general_search) > 0)
            {
                $sampletypes_cond = $sampletypes_cond->where('st_type_name','LIKE','%' . $general_search . '%');
                $sampletypes_cond = $sampletypes_cond->orWhere('st_type_description','LIKE','%' . $general_search . '%');
            }


            $types_count = $sampletypes_cond->count();


            $total_pages = ceil( $types_count /$nbr_rows_per_pages );
            $total_pages = intval($total_pages);


            $lst_sample_types = $sampletypes_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('st_id', 'asc')->get();

            $data = array(
                "lst_sample_types" => $lst_sample_types
            );

            $result_array = array();

            $result_array['total_pages'] = $total_pages;
            $result_array['display'] = view("lms.lstsampletypes",$data)->render();

            return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Sample type
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {

        $data = array( );
        return view('lms.addsampletype',$data);
    }


    /**
     * Save Sample type Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveSampleTypeInfo(Request $request)
    {
        $st_id                          = $request->input('st_id');
        $st_type_name                   = $request->input('st_type_name');
        $st_type_description            = $request->input('st_type_description');


        $result_array = array();


        $type_info = new SampleTypes();
        if( $st_id != null )
        {
            $type_info = SampleTypes::find($st_id);
            $type_info->st_created_date = date('Y-m-d');
            $type_info->st_created_by = session('user_id');
        }
        else
        {
            $type_info->st_last_updated_date = date('Y-m-d');
            $type_info->st_last_updated_by = session('user_id');
        }

        $type_info->st_type_name            = $st_type_name;
        $type_info->st_type_description     = $st_type_description;

        $type_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Sample Type Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Sample Type Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $ls_id
     */
    public function EditForm( $st_id )
    {
        $type_info        = SampleTypes::find($st_id);

        $data = array(
            "type_info" => $type_info
        );
        return view('lms.editsampletype',$data);
    }


    /**
     * Delete Sample Type information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSampleTypeInformation(Request $request)
    {
        $result_array = array();
        $st_id= $request->input('st_id');

        $sample_type = SampleTypes::find( $st_id );
        $sample_type->st_is_deleted          = 1;
        $sample_type->st_deleted_by          = Session('user_id');
        $sample_type->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
