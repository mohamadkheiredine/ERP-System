<?php
/***********************************************************
SuppliersCategoriesController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
COntroller File for Supplier Categories
***********************************************************/




namespace App\Http\Controllers\SRM;

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
use App\models\SRM\SupplierCategories;



class SuppliersCategoriesController extends Controller
{

    /**
     * Page to control SRM Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('srm.categories',$data);
    }
    
    
   /**
    * Display list of Supplier Categories saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {         
        $supplier_categories_array   = array();
        $lst_supplier_categories     = SupplierCategories::whereScIsDeleted(0)->get();
        foreach ( $lst_supplier_categories as $key => $sc_info ) 
        {
            $supplier_categories_array[ $sc_info->sc_id ] =  $sc_info->sc_category_title;
        } 
        $data = array(
            "lst_srm_categories" => $lst_supplier_categories,
            "supplier_categories_array" => $supplier_categories_array
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.listcategories",$data)->render();
        
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
        
        $lst_srm_categories     = SupplierCategories::whereScIsDeleted(0)->get();
        
        $data = array(
            "lst_supplier_categories" => $lst_srm_categories,
        );
        return view('srm.addcategory',$data);
    }
    
    
    /**
     * Save Supplier Categories 
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierCategoryInfo(Request $request)
    {
        $sc_id                      = $request->input('sc_id');
        $fk_category_id             = $request->input('fk_category_id');
        $sc_category_ref            = $request->input('sc_category_ref');
        $sc_category_title          = $request->input('sc_category_title');
        $sc_category_description    = $request->input('sc_category_description');
        
        $result_array = array();
 

        $SupplierCategories = new SupplierCategories();
        if($sc_id != null)
        {
            $SupplierCategories= SupplierCategories::find($sc_id);
        }
         
        $SupplierCategories->fk_category_id           = $fk_category_id;
        $SupplierCategories->sc_category_ref          = $sc_category_ref;
        $SupplierCategories->sc_category_title        = $sc_category_title;
        $SupplierCategories->sc_category_description  = $sc_category_description;
        
        
        
        $SupplierCategories->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Supplier Category Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page 
     * @param unknown $sc_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $sc_id )
    {
        $category_info = SupplierCategories::find($sc_id);
        $lst_srm_categories     = SupplierCategories::whereScIsDeleted(0)->get();
        
        $data = array(
            "lst_supplier_categories" => $lst_srm_categories,
            "category_info" => $category_info
        );
        return view('srm.editcategory',$data);
    }
    
    
    /**
     * Delete Supplier Category from the database by change flag of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierCategoryInfo(Request $request)
    {
        
        $sc_id= $request->input('sc_id');
         
        $supplier_category = SupplierCategories::find( $sc_id);
        $supplier_category->sc_is_deleted          = 1;
        $supplier_category->sc_deleted_by          = Session('user_id');
        $supplier_category->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}