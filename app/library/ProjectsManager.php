<?php
/***********************************************************
 * ProjectsManager.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/25/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/




namespace App\library;


use App\models\PMP\Project;
use App\models\PMP\ProjectJobs;
use App\models\PMP\ProjectPhases;
use Validator;
use Input;
use Config;
use Session;
use Redirect;
use Crypt;
use Cookie;
use Auth;
use DB;
use File;
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\models\Logistics\Vehicules;
use App\models\Inventory\Vendors;
use App\models\System\Companies;
use App\models\Inventory\Customers;


class ProjectsManager
{



    public function GenerateProjectCode($params = array())
    {
        $company_id     = isset( $params['company_id']) ? $params['company_id'] : session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_projects = Project::wherePpIsDeleted(0)->count();

        $index = $count_projects + 1;


        $project_code = "P-" . sprintf('%04d', $index);

        // check if customer code exist

        $code_count = Project::where('pp_project_code',$project_code)->count();
        if($code_count > 0)
        {
            $index = $count_projects + 2;


            $customer_code = "P-" . sprintf('%04d', $index);
        }

        unset($code_count);

        return $project_code;

    }

    /**
     * Generate Project Phases related to the Project
     *
     * @author Moe Mantach
     * @access public
     * @param $params
     * @return string
     */
    public function GenerateProjectPhaseCode($params = array())
    {
        $company_id     = isset( $params['company_id']) ? $params['company_id'] : session('company_id');
        $project_id     = isset( $params['project_id']) ? $params['project_id'] : 0;
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_project_phases = ProjectPhases::wherePpIsDeleted(0)->where('fk_project_id','=',$project_id)->count();

        $index = $count_project_phases + 1;


        $phase_code = "PPHASE-" . sprintf('%04d', $index);


        return $phase_code;

    }


    public function GenerateProjectJobCode($params = array())
    {
        $company_id     = isset( $params['company_id']) ? $params['company_id'] : session('company_id');
        $project_id     = isset( $params['project_id']) ? $params['project_id'] : 0;
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_project_jobs= ProjectJobs::wherePjIsDeleted(0)->where('fk_project_id','=',$project_id)->count();

        $index = $count_project_jobs + 1;


        $phase_code = "PJOB" . $project_id . "-" . sprintf('%04d', $index);


        return $phase_code;

    }

}
