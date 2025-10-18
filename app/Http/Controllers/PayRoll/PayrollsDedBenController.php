<?php
/***********************************************************
PayrollsDedBenController.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 5, 2025
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2025

Page Description :

 ***********************************************************/


namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use App\models\PayRolls\PayrollsDeductionsBenefits;
use App\models\System\Companies;
use App\models\System\Currency;
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
use App\models\CRM\CRMAccounts;
use App\models\Users\Users;
use App\models\Users\Payroll;



class PayrollsDedBenController extends Controller
{
    /**
     * Page to control Project Status Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_currencies = Currency::all();

        $data = array(
            'lst_companies' => $lst_companies,
            'lst_currencies' => $lst_currencies
        );
        return Response()->view('payrolls.dedben',$data);
    }


    /**
     * Display list of Payroll Ded Ben saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $db_company_id          = $request->input("db_company_id");
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        $dedben_cond = PayrollsDeductionsBenefits::whereDbIsDeleted(0);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        if( $db_company_id > 0 )
        {
            $dedben_cond = $dedben_cond->whereDbCompanyId($db_company_id);
        }

        if( strlen($general_search)  > 0)
        {
            $dedben_cond = $dedben_cond->where('db_ben_ded_label','LIKE','%' . $general_search . '%');
            $dedben_cond = $dedben_cond->orWhere('db_description','LIKE','%' . $general_search . '%');
        }

        $debben_count = $dedben_cond->count();


        $total_pages = ceil( $debben_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_dedben = $dedben_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $response_array = array();

        $data = array(
            "lst_dedben" => $lst_dedben
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;
        $response_array['display'] = view('payrolls.listdedben',$data)->render();

        return Response()->json($response_array);
    }


    /**
     * Function of Adding a new Project Status
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_currencies = Currency::all();


        $data = array(
            "lst_companies" => $lst_companies,
            "lst_currencies" => $lst_currencies,
        );
        return view('payrolls.adddedben',$data);
    }


    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveDedBenInfo(Request $request)
    {
        $db_id                      = $request->input('db_id');
        $db_company_id              = $request->input('db_company_id');
        $db_ben_ded_label            = $request->input('db_ben_ded_label');
        $db_description               = $request->input('db_description');
        $db_amount               = $request->input('db_amount');
        $db_currency_id               = $request->input('db_currency_id');
        $db_type              = $request->input('db_type');
        $db_effective_date              = $request->input('db_effective_date');
        $db_end_date              = $request->input('db_end_date');

        $result_array = array();


        $dedben_info = new PayrollsDeductionsBenefits();
        if( $db_id != null )
        {
            $dedben_info = PayrollsDeductionsBenefits::find($db_id);
        }

        $dedben_info->db_company_id                 = $db_company_id;
        $dedben_info->db_ben_ded_label              = $db_ben_ded_label;
        $dedben_info->db_description                = $db_description;
        $dedben_info->db_amount                     = $db_amount;
        $dedben_info->db_currency_id                = $db_currency_id;
        $dedben_info->db_type                       = $db_type;
        $dedben_info->db_effective_date             = $db_effective_date;
        $dedben_info->db_end_date                   = $db_end_date;



        $dedben_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Deduction & Benefit Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $db_id )
    {
        $dedben_info  = PayrollsDeductionsBenefits::find($db_id);

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_currencies = Currency::all();


        $data = array(
            "dedben_info" => $dedben_info,
            "lst_companies" => $lst_companies,
            "lst_currencies" => $lst_currencies
        );
        return view('payrolls.editdedben',$data);
    }


    /**
     * Delete Product Types from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteData(Request $request)
    {

        $db_id = $request->input('db_id');

        $dedben_info = PayrollsDeductionsBenefits::find( $db_id );
        $dedben_info->db_is_deleted   = 1;
        $dedben_info->db_deleted_by   = Session('user_id');
        $dedben_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
