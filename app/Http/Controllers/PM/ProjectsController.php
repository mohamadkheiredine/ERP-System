<?php
/***********************************************************
 * ProjectsController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/25/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use App\library\ProjectsManager;
use App\models\CostCenter\CostCenters;
use App\models\PMP\Project;
use App\models\PMP\ProjectJobs;
use App\models\PMP\ProjectPhases;
use App\models\PMP\ProjectStatus;
use App\models\PMP\ProjectTasks;
use App\models\PMP\ProjectTeams;
use App\models\System\Companies;
use App\models\System\Currency;
use App\models\System\SystemStatus;
use App\models\Users\Users;
use App\models\Users\UserTeam;
use App\models\Users\UserTypes;
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



class ProjectsController extends Controller
{

    /**
     * Page to control Projects Created Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_user_teams = UserTeam::whereUtIsDeleted(0)->get();
        $lst_project_types = ProjectTypes::wherePtIsDeleted(0)->get();
        $lst_project_statuses = ProjectStatus::wherePsIsDeleted(0)->get();
        $lst_project_managers = Users::whereUIsDeleted(0)->whereUIsActive(1)->whereUUserType(UserTypes::USER_TYPE_PROJECT_MANAGER)->get();

        $data = array(
            "lst_companies" => $lst_companies,
            "lst_user_teams" => $lst_user_teams,
            "lst_project_managers" => $lst_project_managers,
            "lst_project_types" => $lst_project_types,
            "lst_project_statuses" => $lst_project_statuses
        );
        return Response()->view('pm.projects',$data);
    }


    /**
     * Display list of Project Management saved in the database
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
        $fk_company_id           = $request->input('fk_company_id');
        $fk_project_manager_id           = $request->input('fk_project_manager_id');
        $pp_status_id          = $request->input('pp_status_id');
        $fk_project_type_id          = $request->input('fk_project_type_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $projects_cond = Project::wherePpIsDeleted(0);

        if(strlen($search_query) > 0)
        {
            $projects_cond = $projects_cond->where('pp_project_name' , 'LIKE' , '%' . $search_query . '%');
            $projects_cond = $projects_cond->orWhere('pp_notes' , 'LIKE' , '%' . $search_query . '%');
            $projects_cond = $projects_cond->orWhere('pp_description' , 'LIKE' , '%' . $search_query . '%');
        }

        if(strlen($fk_company_id) > 0)
        {

            $projects_cond = $projects_cond->where('fk_company_id' , '=' , $fk_company_id);
        }


        if(strlen($fk_project_manager_id) > 0)
        {

            $projects_cond = $projects_cond->where('fk_project_manager_id' , '=' , $fk_project_manager_id);
        }

        if(strlen($pp_status_id) > 0)
        {

            $projects_cond = $projects_cond->where('pp_status_id' , '=' , $pp_status_id);
        }

        if(strlen($fk_project_type_id) > 0)
        {

            $projects_cond = $projects_cond->where('fk_project_type_id' , '=' , $fk_project_type_id);
        }



        $projects_count = $projects_cond->count();


        $total_pages = ceil( $projects_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_projects = $projects_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pp_project_name', 'ASC')->get();



        $data = array(
            "lst_projects" => $lst_projects,
        );

        $result_array = array();

        $result_array['display'] = view("pm.listprojects",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Display List Project Teams
     *
     * @author Moe Mantach
     * @param Request $request
     * @return void
     */
    public function DisplayListProjectTeams(Request $request)
    {
        $pp_id = $request->input('pp_id');

        $lst_project_teams = ProjectTeams::wherePtmIsDeleted(0)->whereFkProjectId($pp_id)->get();

        $data = array(
            "lst_project_teams" => $lst_project_teams,
        );

        $result_array = array();

        $result_array['display'] = view("pm.lstprojectteams",$data)->render();

        return Response()->json($result_array);
    }



    public function DisplayListProjectPhases(Request $request)
    {
        $pp_id = $request->input('pp_id');

        $lst_project_phases = ProjectPhases::whereFkProjectId($pp_id)->wherePpIsDeleted(0)->get();


        $data = array(
            "lst_project_phases" => $lst_project_phases,
        );

        $result_array = array();

        $result_array['display'] = view("pm.lstprojectphases",$data)->render();

        return Response()->json($result_array);
    }

    public function DisplayListProjectJobs(Request $request)
    {
        $pp_id = $request->input('pp_id');

        $lst_project_jobs = ProjectJobs::whereFkProjectId($pp_id)->wherePjIsDeleted(0)->get();


        $data = array(
            "lst_project_jobs" => $lst_project_jobs,
        );

        $result_array = array();

        $result_array['display'] = view("pm.lstprojectjobs",$data)->render();

        return Response()->json($result_array);
    }


    public function DisplayListProjectTasks(Request $request)
    {
        $pp_id = $request->input('pp_id');

        $lst_project_tasks = ProjectTasks::whereFkProjectId($pp_id)->whereWtIsDeleted(0)->get();


        $data = array(
            "lst_project_tasks" => $lst_project_tasks,
        );

        $result_array = array();

        $result_array['display'] = view("pm.lstprojecttasks",$data)->render();

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
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_project_types = ProjectTypes::wherePtIsDeleted(0)->get();
        $lst_project_statuses = ProjectStatus::wherePsIsDeleted(0)->get();
        $lst_cost_centers = CostCenters::whereAcIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_project_managers = Users::whereUIsDeleted(0)->whereUIsActive(1)->whereUUserType(UserTypes::USER_TYPE_PROJECT_MANAGER)->get();


        $project_management = new ProjectsManager();
        $pp_project_code = $project_management->GenerateProjectCode();

        $data = array(
            "pp_project_code" => $pp_project_code,
            "lst_cost_centers" => $lst_cost_centers,
            "lst_companies" => $lst_companies,
            "lst_currencies" => $lst_currencies,
            "lst_project_types" => $lst_project_types,
            "lst_project_statuses" => $lst_project_statuses,
            "lst_project_managers" => $lst_project_managers,
        );
        return view('pm.addproject',$data);
    }


    /**
     * Save Project Types information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $pp_id                      = $request->input('pp_id');
        $pp_project_code            = $request->input('pp_project_code');
        $pp_project_name            = $request->input('pp_project_name');
        $fk_project_type_id         = $request->input('fk_project_type_id');
        $fk_client_id               = $request->input('fk_client_id');
        $fk_company_id              = $request->input('fk_company_id');
        $fk_project_manager_id      = $request->input('fk_project_manager_id');
        $pp_start_date              = $request->input('pp_start_date');
        $pp_end_date                = $request->input('pp_end_date');
        $pp_estimated_end_date      = $request->input('pp_estimated_end_date');
        $pp_status_id               = $request->input('pp_status_id');
        $pp_cost_center_id          = $request->input('pp_cost_center_id');
        $pp_budget                  = $request->input('pp_budget');
        $pp_currency_id             = $request->input('pp_currency_id');
        $pp_description             = $request->input('pp_description');
        $pp_notes                   = $request->input('pp_notes');
        $pp_is_template             = $request->has('pp_is_template') ? 1 : 0;

        $result_array = array();

        $project_info = new Project();
        if( $pp_id != null )
        {
            $project_info = Project::find($pp_id);
        }

        $project_info->pp_project_code            = $pp_project_code;
        $project_info->pp_project_name            = $pp_project_name;
        $project_info->fk_project_type_id            = $fk_project_type_id;
        $project_info->fk_client_id            = $fk_client_id;
        $project_info->fk_company_id            = $fk_company_id;
        $project_info->fk_project_manager_id            = $fk_project_manager_id;
        $project_info->pp_start_date            = $pp_start_date;
        $project_info->pp_end_date            = $pp_end_date;
        $project_info->pp_estimated_end_date            = $pp_estimated_end_date;
        $project_info->pp_status_id            = $pp_status_id;
        $project_info->pp_cost_center_id            = $pp_cost_center_id;
        $project_info->pp_budget            = $pp_budget;
        $project_info->pp_currency_id            = $pp_currency_id;
        $project_info->pp_description            = $pp_description;
        $project_info->pp_notes            = $pp_notes;
        $project_info->pp_is_template            = $pp_is_template;

        $project_info->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Info Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pp_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pp_id )
    {
        $project_info = Project::find($pp_id);
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $lst_project_types = ProjectTypes::wherePtIsDeleted(0)->get();
        $lst_project_statuses = ProjectStatus::wherePsIsDeleted(0)->get();
        $lst_cost_centers = CostCenters::whereAcIsDeleted(0)->get();
        $lst_currencies = Currency::all();
        $lst_user_teams = UserTeam::whereUtIsDeleted(0)->get();
        $lst_project_managers = Users::whereUIsDeleted(0)->whereUIsActive(1)->whereUUserType(UserTypes::USER_TYPE_PROJECT_MANAGER)->get();


        $data = array(
            "project_info" => $project_info,
            "lst_companies" => $lst_companies,
            "lst_cost_centers" => $lst_cost_centers,
            "lst_currencies" => $lst_currencies,
            "lst_project_types" => $lst_project_types,
            "lst_project_statuses" => $lst_project_statuses,
            "lst_user_teams" => $lst_user_teams,
            "lst_project_managers" => $lst_project_managers
        );
        return view('pm.editproject',$data);
    }

    public function LinkProjectTeam(Request $request)
    {
        $result_array = array();
        $pt_project_id = $request->input('pt_project_id');
        $pp_team_id = $request->input('pp_team_id');
        $ptm_allocation_pct = $request->input('ptm_allocation_pct');

        $project_teams = new ProjectTeams();
        $project_teams->fk_project_id = $pt_project_id;
        $project_teams->fk_team_id = $pp_team_id;
        $project_teams->ptm_allocation_pct = $ptm_allocation_pct;
        $project_teams->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Team Information Has been saved';
        return Response()->json($result_array);
    }


    /**
     * Delete Product Info from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteProjectInfo(Request $request)
    {

        $pp_id= $request->input('pp_id');

        $project_info = Project::find( $pp_id );
        $project_info->pp_is_deleted   = 1;
        $project_info->pp_deleted_by   = Session('user_id');
        $project_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
