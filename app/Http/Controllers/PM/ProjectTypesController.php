<?php
/***********************************************************
ProjectTypesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/



namespace App\Http\Controllers\PM;

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
use App\models\PMP\ProjectTypes;



class ProjectTypesController extends Controller
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
        return Response()->view('pm.projects.types',$data);
    }
    
    
    /**
     * Display list of Project Types saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $lst_project_types     = ProjectTypes::wherePtIsDeleted(0)->get(); 
        $data = array(
            "lst_project_types" => $lst_project_types, 
        );
        
        $result_array = array();
        
        $result_array['display'] = view("pm.projects.listtypes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Project Type
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    { 
        $data = array();
        return view('pm.projects.addtypes',$data);
    }
    
    
    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $pt_id                      = $request->input('pt_id');
        $pt_type_name               = $request->input('pt_type_name');
        $pt_type_description        = $request->input('pt_type_description');
        $pt_type_color              = $request->input('pt_type_color'); 
        
        $result_array = array();
        
        
        $project_types = new ProjectTypes();
        if( $pt_id != null )
        {
            $project_types = ProjectTypes::find($pt_id);
        }
        
        $project_types->pt_type_name            = $pt_type_name;
        $project_types->pt_type_description     = $pt_type_description;
        $project_types->pt_type_color           = $pt_type_color;
        
        
        
        $project_types->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Type Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pt_id )
    {
        $project_types = ProjectTypes::find($pt_id); 
        
        $data = array( 
            "project_types" => $project_types
        );
        return view('pm.projects.edittypes',$data);
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
        
        $pt_id= $request->input('pt_id');
        
        $project_types = ProjectTypes::find( $pt_id );
        $project_types->pt_is_deleted   = 1;
        $project_types->pt_deleted_by   = Session('user_id');
        $project_types->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}