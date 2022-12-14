<?php
/***********************************************************
PhoneTransactionsController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/




namespace App\Http\Controllers\Phones;

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
use App\Library\ProductCategoriesManager;
use App\models\Phones\PhoneLines;



class PhoneTransactionsController extends Controller
{
    
    /**
     * Page to control Phone Transaction Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('phones.transactions',$data);
    }
    
    
    /**
     * Display list of phone lines
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
                
                $phone_lines_count = PhoneLines::wherePlIsDeleted(0);
                
                if(strlen($search_query) > 0)
                    $phone_lines_count      = $phone_lines_count->where('pl_line_number' , 'LIKE' , '%' . $search_query . '%');
                    
                    $phone_lines_count      = $phone_lines_count->count();
                    
                    
                    $total_pages = ceil( $phone_lines_count/$nbr_rows_per_pages );
                    $total_pages = intval($total_pages);
                    
                    $phone_lines = PhoneLines::wherePlIsDeleted(0);
                    
                    if(strlen($search_query) > 0)
                        $phone_lines      = $phone_lines->where('pl_line_number', 'LIKE' , '%' . $search_query . '%');
                        
                        $phone_lines      = $phone_lines->skip($skip)->take($nbr_rows_per_pages)->orderBy('pl_id', 'ASC')->get();
                        
                        $data = array(
                            "phone_lines" => $phone_lines,
                        );
                        
                        $result_array = array();
                        $result_array['total_pages'] = $total_pages;
                        $result_array['display'] = view("phones.displaylistlines",$data)->render();
                        
                        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new line
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $data = array( );
        return view('phones.addline',$data);
    }
    
    
    /**
     * Save Line Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveLineInfo(Request $request)
    {
        $pl_id                      = $request->input('pl_id');
        $pl_line_title              = $request->input('pl_line_title');
        $pl_line_number             = $request->input('pl_line_number');
        $pl_total_units             = $request->input('pl_total_units');
        $pl_phone_type              = $request->input('pl_phone_type');
        $result_array = array();
        
        $PhoneLines = new PhoneLines();
        if($pl_id != null)
        {
            $PhoneLines= PhoneLines::find($pl_id);
        }
        
        $PhoneLines->pl_line_title              = $pl_line_title;
        $PhoneLines->pl_line_number             = $pl_line_number;
        $PhoneLines->pl_total_units             = $pl_total_units;
        $PhoneLines->pl_phone_type              = $pl_phone_type;
        
        
        
        $PhoneLines->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Phone lines Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit  Form of Phone line Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $pc_id
     */
    public function EditForm( $pl_id )
    {
        $phone_lines        = PhoneLines::find($pl_id);
        
        $data = array(
            "phone_lines" => $phone_lines
        );
        return view('phones.editline',$data);
    }
    
    
    /**
     * Delete phone line information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeletePhoneLineInfo(Request $request)
    {
        
        $pl_id = $request->input('pl_id');
        
        $phone_lines = PhoneLines::find( $pl_id );
        $phone_lines->pl_is_deleted          = 1;
        $phone_lines->pl_deleted_by          = Session('user_id');
        $phone_lines->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
}