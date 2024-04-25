<?php
/***********************************************************
 RolesManager.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Apr 27, 2019
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2019
 
 Page Description :
 
 ***********************************************************/

namespace App\library;


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
use Illuminate\Support\Facades\Hash;
use App\Models\Roles\PrivilegedActions;
use App\Models\Roles\RolePrivileges;
use App\Models\Roles\Roles;


class RolesManager
{
    
    /**
     * get all privilege Actions Saved in the database
     *
     * @author Moe Mantach
     * @access public
     *
     * @return Array $result_array
     */
    public static function getAllPrivilegeAction()
    {
        $pa_array = PrivilegedActions::all();
 
        $db_result_array = array();
        
        $index = 0;
        foreach ($pa_array as $key => $pa_info) {
            $db_result_array[ $pa_info->pa_group ][$index]['id'] = $pa_info->pa_id;
            $db_result_array[ $pa_info->pa_group ][$index]['code'] = $pa_info->pa_code;
            $db_result_array[ $pa_info->pa_group ][$index]['name'] = $pa_info->pa_name;
            $db_result_array[ $pa_info->pa_group ][$index]['description'] = $pa_info->pa_description;
            $index++;
        }
        
        
        return $db_result_array;
    }
    
    
    public static function getAllRolePrivilege( $role_id )
    {
        $rp_array = RolePrivileges::whereFkRoleRId($role_id)->get();
        
        $db_result_array = array();
         
        foreach ( $rp_array as $key =>  $rp_info ) 
        {
            $db_result_array[ $rp_info->rp_action_code ] = $rp_info->rp_privilege;
        }
        
        
        
        return $db_result_array;
    }
    
    
    
    public static function ResetRolePrivileges($role_id)
    { 
        DB::table('role_privileges')->where('fk_role_r_id', '=', $role_id)->delete();
    }
}