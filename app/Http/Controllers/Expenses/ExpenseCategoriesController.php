<?php
/***********************************************************
ProductCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventory\unknown;
use App\Http\Controllers\Inventory\View;
use App\models\Accounting\ChartAccounts;
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
use App\models\Expenses\ExpensesCategories;



class ExpenseCategoriesController extends Controller
{

    /**
     * Page to control product categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('expenses.categories',$data);
    }


    /**
     * Display list of Expense categories
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
          $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
          $skip = 0;



        $exp_categories_cond = ExpensesCategories::whereEcIsDeleted(0);

        if(strlen($search_query) > 0)
            $exp_categories_cond = $exp_categories_cond->where('ec_name' , 'LIKE' , '%' . $search_query . '%');

        $expenses_categories_count = $exp_categories_cond->count();


        $total_pages = ceil( $expenses_categories_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_expenses_categories = $exp_categories_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('ec_name', 'ASC')->get();

        $data = array(
            "lst_expenses_categories" => $lst_expenses_categories
        );

        $result_array = array();

        $result_array['total_pages'] = $total_pages;
        $result_array['display'] = view("expenses.lstcategories",$data)->render();

        return Response()->json($result_array);
    }



    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_currencies = Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        $lst_categories  = ExpensesCategories::whereEcIsDeleted(0)->get();

        $data = array(
            'lst_currencies' => $lst_currencies,
            'lst_accounts' => $lst_accounts,
            'lst_categories' => $lst_categories
        );
        return view('expenses.addcategory',$data);
    }


    /**
     * Save Expense Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveExpenseCategoryInfo(Request $request)
    {
        $ec_id                                  = $request->input('ec_id');
        $ec_name                                = $request->input('ec_name');
        $ec_description                         = $request->input('ec_description');
        $ec_max_amount                          = $request->input('ec_max_amount');
        $ec_currency_id                         = $request->input('ec_currency_id');
        $ec_parent_category                         = $request->input('ec_parent_category');
        $ec_require_receipt                     = $request->has('ec_require_receipt') ? 1 : 0;
        $ec_gl_account_id                       = $request->input('ec_gl_account_id');
        $result_array = array();


        $expense_categories = new ExpensesCategories();
        if($ec_id != null)
        {
            $expense_categories = ExpensesCategories::find($ec_id);
        }

        $expense_categories->ec_name                  = $ec_name;
        $expense_categories->ec_description                  = $ec_description;
        $expense_categories->ec_max_amount                  = $ec_max_amount;
        $expense_categories->ec_require_receipt                  = $ec_require_receipt;
        $expense_categories->ec_gl_account_id                  = $ec_gl_account_id;
        $expense_categories->ec_currency_id                  = $ec_currency_id;
        $expense_categories->ec_parent_category                  = $ec_parent_category;


        $expense_categories->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Expenses Category Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Expenses Category Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pc_id
     */
    public function EditForm( $ec_id )
    {
        $category_info        = ExpensesCategories::find($ec_id);
        $lst_currencies = Currency::all();
        $lst_accounts = ChartAccounts::whereAaIsDeleted(0)->get();
        $lst_categories  = ExpensesCategories::whereEcIsDeleted(0)->whereNotIn('ec_id',array( $ec_id ))->get();

        $data = array(
            "category_info" => $category_info,
            "lst_currencies" => $lst_currencies,
            "lst_accounts" => $lst_accounts,
            "lst_categories" => $lst_categories
        );
        return view('expenses.editcategory',$data);
    }


    /**
     * Delete Expenses category information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteExpensesCategoryInfo(Request $request)
    {

        $ec_id= $request->input('ec_id');

        $category_info = ExpensesCategories::find( $ec_id);
        $category_info->ec_is_deleted          = 1;
        $category_info->ec_deleted_by          = Session('user_id');
        $category_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }
}
