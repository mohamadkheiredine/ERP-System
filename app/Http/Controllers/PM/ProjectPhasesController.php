<?php
/***********************************************************
 * ProjectPhasesController.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/31/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use App\library\ProjectsManager;
use App\models\PMP\Project;
use App\models\PMP\ProjectPhases;
use App\models\System\Departments;
use App\models\System\SystemStatus;
use App\models\Users\UserTeam;
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
use App\models\PMP\ProjectTypes;



class ProjectPhasesController extends Controller
{

    /**
     * Page to control Project Phases Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_projects = Project::wherePpIsDeleted(0)->get();

        $data = array(
            'lst_projects' => $lst_projects,
        );
        return Response()->view('pm.projectphases',$data);
    }


    /**
     * Display list of Project Phases saved in the database
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
        $fk_project_id           = $request->input('fk_project_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $phases_cond = ProjectPhases::wherePpIsDeleted(0);

        if(strlen($search_query) > 0)
        {
            $phases_cond = $phases_cond->where('pp_phase_name' , 'LIKE' , '%' . $search_query . '%');
            $phases_cond = $phases_cond->orWhere('pp_phase_description' , 'LIKE' , '%' . $search_query . '%');
            $phases_cond = $phases_cond->orWhere('pp_phase_code' , 'LIKE' , '%' . $search_query . '%');
        }

        if($fk_project_id > 0)
        {
            $phases_cond = $phases_cond->where('fk_project_id' , '=' ,  $fk_project_id);

        }


        $phases_count = $phases_cond->count();


        $total_pages = ceil( $phases_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_project_phases = $phases_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pp_phase_code', 'ASC')->get();



        $data = array(
            "lst_project_phases" => $lst_project_phases,
        );

        $result_array = array();

        $result_array['display'] = view("pm.lstphases",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Project Phase
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_projects = Project::wherePpIsDeleted(0)->where('pp_end_date','>',date('Y-m-d'))->get();
        $lst_departments = Departments::where('sd_is_deleted',0)->get();
        $lst_teams = UserTeam::where('ut_is_deleted',0)->get();
        $lst_phase_statuses  = SystemStatus::where('ss_status_type','=','phase_status')->where('ss_is_deleted','=','0')->get();
        $data = array(
            "lst_projects" => $lst_projects,
            "lst_teams" => $lst_teams,
            "lst_phase_statuses" => $lst_phase_statuses,
            "lst_departments" => $lst_departments,
        );
        return view('pm.addphase',$data);
    }


    /**
     * Save Project Phase information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $pp_phase_id                      = $request->input('pp_phase_id');
        $fk_project_id                      = $request->input('fk_project_id');
        $pp_department_id                     = $request->input('pp_department_id');
        $pp_phase_code                     = $request->input('pp_phase_code');
        $pp_phase_name                     = $request->input('pp_phase_name');
        $pp_phase_description                     = $request->input('pp_phase_description');
        $pp_planned_start                     = $request->input('pp_planned_start');
        $pp_planned_end                     = $request->input('pp_planned_end');
        $pp_actual_start                     = $request->input('pp_actual_start');
        $pp_actual_end                    = $request->input('pp_actual_end');
        $pp_sort_order                    = $request->input('pp_sort_order');
        $pp_phase_status                    = $request->input('pp_phase_status');
        $pp_team_id                    = $request->input('pp_team_id');

        $result_array = array();


        $project_phases = new ProjectPhases();
        if( $pp_phase_id != null )
        {
            $project_phases = ProjectPhases::find($pp_phase_id);
        }

        $project_phases->fk_project_id            = $fk_project_id;
        $project_phases->pp_department_id            = $pp_department_id;
        $project_phases->pp_team_id                 = $pp_team_id;
        $project_phases->pp_phase_code            = $pp_phase_code;
        $project_phases->pp_phase_name            = $pp_phase_name;
        $project_phases->pp_phase_description            = $pp_phase_description;
        $project_phases->pp_planned_start            = $pp_planned_start;
        $project_phases->pp_planned_end            = $pp_planned_end;
        $project_phases->pp_actual_start            = $pp_actual_start;
        $project_phases->pp_actual_end            = $pp_actual_end;
        $project_phases->pp_sort_order            = $pp_sort_order;
        $project_phases->pp_phase_status            = $pp_phase_status;
        $project_phases->pp_team_id                 = $pp_team_id;



        $project_phases->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Phase Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pp_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pp_id )
    {
        $project_phase = ProjectPhases::find($pp_id);
        $lst_teams = UserTeam::where('ut_is_deleted',0)->get();

        $lst_projects = Project::wherePpIsDeleted(0)->where('pp_end_date','>',date('Y-m-d'))->get();
        $lst_departments = Departments::where('sd_is_deleted',0)->get();
        $lst_phase_statuses  = SystemStatus::where('ss_status_type','=','phase_status')->where('ss_is_deleted','=','0')->get();

        $data = array(
            "project_phase" => $project_phase,
            "lst_projects" => $lst_projects,
            "lst_teams" => $lst_teams,
            "lst_phase_statuses" => $lst_phase_statuses,
            "lst_departments" => $lst_departments,
        );
        return view('pm.editphase',$data);
    }


    public function GeneratePhaseCode(Request $request)
    {
        $fk_project_id = $request->input('fk_project_id');
        $project = new ProjectsManager();
        $phase_code = $project->GenerateProjectPhaseCode(array('project_id' => $fk_project_id));
        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'New Phase Code has been created';
        $result_array['phase_code'] = $phase_code;

        return Response()->json($result_array);
    }


    /**
     * Delete Product Phase from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteData(Request $request)
    {

        $pp_phase_id = $request->input('pp_phase_id');

        $project_phases = ProjectPhases::find( $pp_phase_id );
        $project_phases->pp_is_deleted   = 1;
        $project_phases->pp_deleted_by   = Session('user_id');
        $project_phases->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
