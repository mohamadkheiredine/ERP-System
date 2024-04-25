<?php
/***********************************************************
LeadNotesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use App\library\LeadsManager;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMLeadNotes;
use Config;


class LeadNotesController extends Controller
{
    
    /**
     * Disp0lay tab of Lead Notes where you can manage notes of the current Lead
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function LeadNotesManager(Request $request)
    {
        $cl_id = $request->input("cl_id");
        $lst_notes = CRMLeadNotes::whereFkLeadId($cl_id)->get();
        $result_array = array();
        
        $data = array(
            "lst_notes" => $lst_notes
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("Leads.leadnotes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Display list of existing notes with information of every one
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListNotes(Request $request)
    {
        $cl_id = $request->input("cl_id");
        $lst_notes = CRMLeadNotes::whereFkLeadId($cl_id)->get();
        
        $notes_data = array();
        
        foreach ($lst_notes as $key => $note_info) {
            $user_id =  $note_info->fk_writer_id;
            $user_info = Users::find($user_id);
            
            $profile_path     = public_path().'/'.Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            $profile_url = url('/').'/'.Config::get('constants.USERS_PATH') . $user_info->u_avatar_base_src . $user_info->u_avatar_filename . "." . $user_info->u_avatar_extentions;
            if(!is_file($profile_path))
            {
                $profile_url= url('images/avatar.jpg');
            }
            
            
            $notes_data[ $note_info->ln_id ]['profile_pic'] = $profile_url;
            $notes_data[ $note_info->ln_id ]['note_date'] = $note_info->ln_note_date;
            $notes_data[ $note_info->ln_id ]['note'] = $note_info->ln_note;
            $notes_data[ $note_info->ln_id ]['writer_name'] = $user_info->u_fullname;
        }
        
        $data = array(
            "notes_data" => $notes_data
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("Leads.listnotes",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Save Lead Note Info
     * 
     * @author Moe Mantach
     * @access public 
     * @param Request $request
     */
    public function SaveLeadNoteInfo(Request $request)
    {
        $cl_id          = $request->input("cl_id");
        $lead_notes     = $request->input("lead_notes");
        $result_array   = array();
        
        $LeadNotes = new CRMLeadNotes();
        $LeadNotes->fk_lead_id      = $cl_id;
        $LeadNotes->fk_writer_id    = session("user_id");
        $LeadNotes->ln_note_date    = date("Y-m-d H:i:s");
        $LeadNotes->ln_note         = $lead_notes;
        $LeadNotes->save();
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete successfully";
        
        return Response()->json($result_array);
    }
    
}