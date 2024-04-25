<?php
/************************************************************
RolesController.php
Product :
Version : 1.0
Release : 0
Date Created : Aug 7, 2015
Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015

Page Description :
Roles controle contain all functionality related to role table
************************************************************/

namespace App\Http\Controllers\Roles;

use App\User;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use DB;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Hash;
use App\Models\Roles\Roles;
use App\library\RolesManager;
use App\Models\Roles\RolePrivileges;


class RolesController extends Controller
{
    public function roles()
    {   
        $data = array();
        return view('roles.roles',$data);
    }
    
    
    /**
     * Display add role form to add new role to the database
     *
     * @author Moe Mantach
     * @access pubic
     * @return Ambigous <\Illuminate\View\View, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
     */
    public function AddRoleForm()
    {
        $roles_manager = new RolesManager();
        $pa_result_array = $roles_manager->getAllPrivilegeAction();
         
        
        $data = array(
            'pa_result_array' => $pa_result_array
        );
        return view('roles.addrole',$data);
    }
    
    public function EditRoleForm( $id )
    {
        /**
         $role_info = session()->get('role_info');
         if( ( isset($role_info['om_edit_role_privilege'])  && $role_info['om_edit_role_privilege'] == 'deny') || ( !isset( $role_info['om_edit_role_privilege'] ) ) )
         {
         return Redirect::to('administrator/PermissionDenied');
         }*/
        
        $roles = Roles::find($id);
        $pa_result_array = RolesManager::getAllPrivilegeAction();
        $rp_result_array = RolesManager::getAllRolePrivilege( $id );
        
        
        $data = array(
            "roles" =>$roles ,
            'role_id' => $id ,
            "pa_result_array" => $pa_result_array,
            "rp_result_array" => $rp_result_array
            
        );
        
        return view('roles.editrole',$data);
    }
    
    
    public function PermissionDenied()
    {
        $data = array();
        
        return Response()->view('messages.permission_denied',$data);
    }
    
    
    
    
    /**
     * Display list of roles saved in the database
     *
     * @author Moe Mantach
     * @access public
     *
     * @return Array $result_array
     */
    public function DisplayListRoles()
    {
        $list_roles =  Roles::whereRoleIsActive(1)->whereRoleIsDeleted(0)->get();
        $result_array = array();
        
        $data = array(
            "list_roles" => $list_roles
        );
        $result_array['display'] = view("roles.displaylist",$data)->render();
        $result_array['is_error'] = 0;
        
        return Response()->json($result_array);
    }
    
    
    /**
     *Add new role to the database with title and description
     *
     *@author Moe Mantach
     *@access public
     *
     *@return Array $result_array
     */
    public function SaveRoleInfo(Request $request)
    {
        $role_id          = $request->input('role_id');
        $role_name          = $request->input('role_name');
        $role_description   = $request->input('role_description');
        
        // save roles information to the database
        $roles = new Roles();
        if($role_id != null)
        {
            $roles = Roles::find($role_id);
        }
        $roles->role_name = $role_name;
        $roles->role_description = $role_description;
        $roles->role_is_active = 1;
        $roles->save();
        $role_id = $roles->role_id;
        
        
        
        $pa_result_array = RolesManager::ResetRolePrivileges($role_id);
        $pa_result_array = RolesManager::getAllPrivilegeAction();
        
        $fields_array = array();
        
        foreach ($pa_result_array as $categoryTitle => $privilageInfo) {
            foreach ($privilageInfo as $index => $PrivilageData) {
                $code = $request->input( $PrivilageData['code']);
                $rp_privilege = $code !== null ? "allow" : 'deny';
                $rolePrivilege = new RolePrivileges();
                $rolePrivilege->fk_role_r_id    = $role_id;
                $rolePrivilege->rp_action_code  = $PrivilageData['code'];
                $rolePrivilege->rp_privilege  = $rp_privilege;
                $rolePrivilege->rp_random  = rand(0, 99999);
                $rolePrivilege->save();
            }
            
        }
        
        
        $result_array               = array();
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        $result_array['id']         = $role_id;
        
        return json_encode($result_array);
    }
    
    
    
    /**
     * function to delete role from the database
     *
     * @author Moe Mantach
     * @access public
     *
     * @return Array $result_array
     */
    public function DeleteRoleInfo(Request $request)
    {
        /** $role_info = session()->get('role_info');
         if( ( isset($role_info['om_delete_roles_privilege'])  && $role_info['om_delete_roles_privilege'] == 'deny') || ( !isset( $role_info['om_delete_roles_privilege'] ) ) )
         {
         $result_array['is_error']   = 0;
         $result_array['error_msg']  = "Operation Complete Successfully";
         
         return json_encode($result_array);
         }
         */
        $role_id            = $request->input('role_id');
        $roles = new Roles();
        $roles = Roles::find($role_id);
        $roles->role_is_deleted = 1;
        $roles->role_deleted_by = Session('user_id');
        $roles->save();
        
        $result_array               = array();
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return json_encode($result_array);
    }

}
