<?php
/***********************************************************
UsersTeamController.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Users Team Controller to manage all pages for the 
***********************************************************/



namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Config;
use File;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\System\JobTitles;
use App\models\System\JobRoles;
use App\models\System\Departments;
use App\models\System\Roles;
use App\models\Timesheet\EmploymentType;
use App\Library\UsersManager;
use App\models\System\Companies;
use App\models\System\Languages;
use App\models\Users\UserTeam;
use App\models\Users\TeamMembers;



class UsersTeamController extends Controller
{

    /**
     * Display Page of Users Team
     * 
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('users.team',$data);
    }
    
    
    /**
     * Display List of Users team
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    { 
        $lst_user_teams     = UserTeam::whereUtIsDeleted(0)->get(); 
        
        
        $data = array(
            "lst_user_teams" => $lst_user_teams, 
        );
        
        $result_array = array();
        
        $result_array['display'] = view("users.listteams",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
   /**
    * Add new Team Form
    * 
    * @author Moe Mantach
    * @access public
    * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    */
    public function AddForm()
    {
        
        $lst_users     = Users::whereUIsDeleted(0)->get();
        
        $data = array(
            "lst_users" => $lst_users,
        );
        return view('users.addteam',$data);
    }
    
    
    /**
     * Save Users Team Info
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function SaveUsersTeamInfo(Request $request)
    {
        $ut_id                      = $request->input('ut_id');
        $ut_team                    = $request->input('ut_team');  
        $ut_description             = $request->input('ut_description');  
        $ut_members_id              = $request->input('ut_members_id');  
        
        $result_array   = array();
        
        $user_team      = new UserTeam();
        if( $ut_id  != null )
        {
            $user_team= UserTeam::find($ut_id);
        }
        
        $user_team->ut_team         = $ut_team; 
        $user_team->ut_description  = $ut_description; 
        $user_team->save();
        $ut_id = $user_team->ut_id;
        
        $tem_members_del =  TeamMembers::whereFkTeamId($ut_id)->delete();
        
        // save members in the team
        foreach ( $ut_members_id as $key => $user_id ) 
        {
            $tem_members = new TeamMembers();
            $tem_members->fk_team_id    = $ut_id;
            $tem_members->fk_user_id    = $user_id;
            $tem_members->save();
        }
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Users Team Information Has been saved';
        
        unset($tem_members_del);
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit User Team Form
     * @param unknown $ut_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $ut_id )
    {
        $team_info            = UserTeam::find($ut_id);
        $lst_team_members     = TeamMembers::whereFkTeamId($ut_id)->get();
        $team_array = array();
        foreach ($lst_team_members as $key => $um_info) 
        {
            $team_array[] = $um_info->fk_user_id;
        }
        
        $lst_users           = Users::whereUIsDeleted(0)->get();
        
        $data = array(
            "team_array" => $team_array,
            "team_info" => $team_info,
            "lst_users" => $lst_users,
        );
        return view('users.editteam',$data);
    }
    
    
    /**
     * Delete Supplier Category from the database by change flag of the row
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteUserTeamInfo(Request $request)
    {
        
        $ut_id= $request->input('ut_id');
        
        $user_team = UserTeam::find( $ut_id);
        $user_team->ut_is_deleted           = 1;
        $user_team->ut_deleted_by           = Session('user_id');
        $user_team->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
 

}