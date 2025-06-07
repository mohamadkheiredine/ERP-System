<?php
/***********************************************************
 * PayrollsTaxBracketsController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/




namespace App\Http\Controllers\PayRoll;

use App\Http\Controllers\Controller;
use App\models\PayRolls\PayrollsDeductionsBenefits;
use App\models\PayRolls\PayrollsTaxBrackets;
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



class PayrollsTaxBracketsController extends Controller
{
    /**
     * Page to control Tax Brackets Management
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
            'lst_currencies' => $lst_currencies,
        );
        return Response()->view('payrolls.taxbrackets',$data);
    }


    /**
     * Display list of Payroll Tax Brackets saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $tb_company_id          = $request->input("tb_company_id");
        $page_number            = $request->input('page_number');
        $general_search         = $request->input('general_search');
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');

        $taxes_cond = PayrollsTaxBrackets::whereTbIsDeleted(0);

        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        if( $tb_company_id > 0 )
        {
            $taxes_cond = $taxes_cond->whereTbCompanyId($tb_company_id);
        }

        if( strlen($general_search)  > 0)
        {
            $taxes_cond = $taxes_cond->where('db_ben_ded_label','LIKE','%' . $general_search . '%');
        }

        $taxes_count = $taxes_cond->count();


        $total_pages = ceil( $taxes_count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);

        $lst_taxes = $taxes_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $response_array = array();

        $data = array(
            "lst_taxes" => $lst_taxes
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;
        $response_array['display'] = view('payrolls.listtaxbrackets',$data)->render();

        return Response()->json($response_array);
    }


    /**
     * Function of Adding a new Tax Bracket
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
        return view('payrolls.addtaxbracket',$data);
    }


    /**
     * Save Tax Bracket information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SavePayRollBracketInfo(Request $request)
    {
        $tb_bracket_id                      = $request->input('tb_bracket_id');
        $tb_company_id                      = $request->input('tb_company_id');
        $tb_currency_id                      = $request->input('tb_currency_id');
        $tb_bracket_code                      = $request->input('tb_bracket_code');
        $tb_bracket_label                      = $request->input('tb_bracket_label');
        $tb_bracket_description                      = $request->input('tb_bracket_description');
        $tb_min_salary                      = $request->input('tb_min_salary');
        $tb_max_salary                      = $request->input('tb_max_salary');
        $tb_tax_rate                      = $request->input('tb_tax_rate');
        $tb_region                     = $request->input('tb_region');

        $result_array = array();


        $taxes_info = new PayrollsTaxBrackets();
        if( $tb_bracket_id != null )
        {
            $taxes_info = PayrollsTaxBrackets::find($tb_bracket_id);
        }

        $taxes_info->tb_company_id                 = $tb_company_id;
        $taxes_info->tb_currency_id                 = $tb_currency_id;
        $taxes_info->tb_bracket_code                    = $tb_bracket_code;
        $taxes_info->tb_bracket_label                   = $tb_bracket_label;
        $taxes_info->tb_bracket_description             = $tb_bracket_description;
        $taxes_info->tb_min_salary                      = $tb_min_salary;
        $taxes_info->tb_max_salary                      = $tb_max_salary;
        $taxes_info->tb_tax_rate                        = $tb_tax_rate;
        $taxes_info->tb_region                          = $tb_region;



        $taxes_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Tax Brackets Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $tb_id )
    {
        $taxes_info  = PayrollsTaxBrackets::find($tb_id);

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_currencies = Currency::all();


        $data = array(
            "taxes_info" => $taxes_info,
            "lst_currencies" => $lst_currencies,
            "lst_companies" => $lst_companies
        );
        return view('payrolls.edittaxbracket',$data);
    }


    /**
     * Delete Tax Bracket from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteData(Request $request)
    {

        $tb_id = $request->input('tb_id');

        $taxes_info  = PayrollsTaxBrackets::find( $tb_id );
        $taxes_info->tb_is_deleted   = 1;
        $taxes_info->tb_deleted_by   = Session('user_id');
        $taxes_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
