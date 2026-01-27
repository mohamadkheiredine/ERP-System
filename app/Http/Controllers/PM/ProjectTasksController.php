<?php
/***********************************************************
 * ProjectJobsController.php
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
use App\models\PMP\ProjectJobs;
use App\models\PMP\ProjectPhases;
use App\models\System\Departments;
use App\models\System\SystemStatus;
use App\models\Users\Users;
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



class ProjectTasksController extends Controller
{

    /**
     * Page to control Project Tasks Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_projects = Project::wherePpIsDeleted(0)->get();
        $lst_project_phases = ProjectPhases::wherePpIsDeleted(0)->get();

        $data = array(
            'lst_projects' => $lst_projects,
            'lst_project_phases' => $lst_project_phases,
        );
        return Response()->view('projectmanagement.projectjobs',$data);
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
        $fk_phase_id           = $request->input('fk_phase_id');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;



        $jobs_cond = ProjectJobs::wherePjIsDeleted(0);

        if(strlen($search_query) > 0)
        {
            $jobs_cond = $jobs_cond->where('pj_job_code' , 'LIKE' , '%' . $search_query . '%');
            $jobs_cond = $jobs_cond->orWhere('pj_job_name' , 'LIKE' , '%' . $search_query . '%');
            $jobs_cond = $jobs_cond->orWhere('pj_description' , 'LIKE' , '%' . $search_query . '%');
        }

        if($fk_project_id > 0)
        {
            $jobs_cond = $jobs_cond->where('fk_project_id' , '=' ,  $fk_project_id);

        }

        if($fk_phase_id > 0)
        {
            $jobs_cond = $jobs_cond->where('fk_phase_id' , '=' ,  $fk_phase_id);

        }


        $jobs_count = $jobs_cond->count();


        $total_pages = ceil( $jobs_count /$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_project_jobs = $jobs_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('pj_job_code', 'ASC')->get();



        $data = array(
            "lst_project_jobs" => $lst_project_jobs,
        );

        $result_array = array();

        $result_array['display'] = view("projectmanagement.lstjobs",$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Project job
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_projects = Project::wherePpIsDeleted(0)->where('pp_end_date','>',date('Y-m-d'))->get();
        $lst_phases = ProjectPhases::wherePpIsDeleted(0)->get();
        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_job_status = SystemStatus::whereSsIsDeleted(0)->where('ss_status_type','job_status')->get();
        $data = array(
            "lst_projects" => $lst_projects,
            "lst_phases" => $lst_phases,
            "lst_users" => $lst_users,
            "lst_job_status" => $lst_job_status
        );
        return view('projectmanagement.addjob',$data);
    }


    /**
     * Save Project Job information
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveInfo(Request $request)
    {
        $pj_id                      = $request->input('pj_id');
        $fk_project_id                      = $request->input('fk_project_id');
        $fk_phase_id                      = $request->input('fk_phase_id');
        $pj_job_code                      = $request->input('pj_job_code');
        $pj_job_name                      = $request->input('pj_job_name');
        $pj_description                      = $request->input('pj_description');
        $pj_owner_id                     = $request->input('pj_owner_id');
        $pj_planned_start                     = $request->input('pj_planned_start');
        $pj_planned_end                     = $request->input('pj_planned_end');
        $pj_actual_start                     = $request->input('pj_actual_start');
        $pj_actual_end                     = $request->input('pj_actual_end');
        $pj_status_id                     = $request->input('pj_status_id');
        $pj_sort_order                     = $request->input('pj_sort_order');

        $result_array = array();


        $project_jobs = new ProjectJobs();
        if( $pj_id != null )
        {
            $project_jobs = ProjectJobs::find($pj_id);
        }

        $project_jobs->fk_project_id            = $fk_project_id;
        $project_jobs->fk_phase_id            = $fk_phase_id;
        $project_jobs->pj_job_code            = $pj_job_code;
        $project_jobs->pj_job_name            = $pj_job_name;
        $project_jobs->pj_description            = $pj_description;
        $project_jobs->pj_owner_id            = $pj_owner_id;
        $project_jobs->pj_planned_start            = $pj_planned_start;
        $project_jobs->pj_planned_end            = $pj_planned_end;
        $project_jobs->pj_actual_start            = $pj_actual_start;
        $project_jobs->pj_actual_end            = $pj_actual_end;
        $project_jobs->pj_status_id            = $pj_status_id;



        $project_jobs->save();

        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Project Jobs Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Edit Form Page
     * @param unknown $pp_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $pj_id )
    {
        $project_jobs = ProjectJobs::find($pj_id);

        $lst_projects = Project::wherePpIsDeleted(0)->where('pp_end_date','>',date('Y-m-d'))->get();
        $lst_phases = ProjectPhases::wherePpIsDeleted(0)->get();
        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_job_status = SystemStatus::whereSsIsDeleted(0)->where('ss_status_type','job_status')->get();
        $data = array(
            "project_jobs" => $project_jobs,
            "lst_projects" => $lst_projects,
            "lst_phases" => $lst_phases,
            "lst_users" => $lst_users,
            "lst_job_status" => $lst_job_status
        );
        return view('projectmanagement.editjob',$data);
    }


    public function GenerateJobsCode(Request $request)
    {
        $fk_project_id = $request->input('fk_project_id');
        $project = new ProjectsManager();
        $job_code = $project->GenerateProjectJobCode(array('project_id' => $fk_project_id));
        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = 'New Job Code has been created';
        $result_array['job_code'] = $job_code;

        return Response()->json($result_array);
    }


    /**
     * Delete Product Job from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteData(Request $request)
    {

        $pj_id = $request->input('pj_id');

        $project_jobs = ProjectJobs::find( $pj_id );
        $project_jobs->pj_is_deleted   = 1;
        $project_jobs->pj_deleted_by   = Session('user_id');
        $project_jobs->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
