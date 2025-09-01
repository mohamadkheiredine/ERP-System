<?php
/***********************************************************
ProjectRolesController.php
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
use App\models\PMP\ProjectRoles;
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
use App\models\SRM\SupplierCategories;
use App\models\PMP\ProjectTypes;



class ProjectRolesController extends Controller
{

    /**
     * Page to control Project Roles
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('pm.projectroles',$data);
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

        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $project_roles_cond = ProjectRoles::wherePrIsDeleted(0);

        if(strlen($search_query) > 0)
        {
            $project_roles_cond = $project_roles_cond->where('pr_name' , 'LIKE' , '%' . $search_query . '%');
            $project_roles_cond = $project_roles_cond->orWhere('pr_description' , 'LIKE' , '%' . $search_query . '%');
        }

        $project_roles_count = $project_roles_cond->count();


        $total_pages = ceil( $project_roles_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_project_roles = $project_roles_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pr_name', 'ASC')->get();



        $data = array(
            "lst_project_roles" => $lst_project_roles,
        );

        $result_array = array();

        $result_array['display'] = view("pm.listprojectroles",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Project Role
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $data = array();
        return view('pm.addprojectrole',$data);
    }


    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $pr_id                          = $request->input('pr_id');
        $pr_name                        = $request->input('pr_name');
        $pr_description                 = $request->input('pr_description');
        $pr_is_billable_default         = $request->has('pr_is_billable_default') ? 1 : 0;

        $result_array = array();


        $project_roles = new ProjectRoles();
        if( $pr_id != null )
        {
            $project_roles = ProjectRoles::find($pr_id);
        }

        $project_roles->pr_name            = $pr_name;
        $project_roles->pr_description     = $pr_description;
        $project_roles->pr_is_billable_default           = $pr_is_billable_default;



        $project_roles->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Role Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pt_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pr_id )
    {
        $project_roles = ProjectRoles::find($pr_id);

        $data = array(
            "project_roles" => $project_roles
        );
        return view('pm.editprojectrole',$data);
    }


    /**
     * Delete Product Roles from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteProjectRole(Request $request)
    {

        $pr_id= $request->input('pr_id');

        $project_roles = ProjectRoles::find( $pr_id );
        $project_roles->pr_is_deleted   = 1;
        $project_roles->pr_deleted_by   = Session('user_id');
        $project_roles->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
