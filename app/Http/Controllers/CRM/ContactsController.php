<?php
/***********************************************************
ContactsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
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
use App\models\CRM\CRMContacts;
use App\models\System\Departments;
use App\library\ContactsManager;
use App\models\CRM\CRMAccounts;


class ContactsController extends Controller
{
    
    
    
    /**
     * Page to manage all contacts saved in the database
     * 
     * @author Moe Mantach
     * @access public
     */
    public function index()
    {
        $lst_leads = CRMLeads::whereClIsDeleted(0)->get();
        $data = array(
            "lst_leads" => $lst_leads
        );
        return Response()->view('contacts.contacts',$data);
    }
    
    /**
     * Display List of Contacts saved in the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListContacts(Request $request)
    {
        $contact_lead = $request->input("contact_lead");
        
        
        $lst_contacts_obj = CRMContacts::whereCcIsDeleted(0);
        
        if( $contact_lead > 0 )
        {
            $lst_contacts_obj= $lst_contacts_obj->whereFkLeadId($contact_lead);
        }

        
        $lst_contacts = $lst_contacts_obj->get();
        
        
        $response_array = array();
        
        $data = array(
            "lst_lead_contacts" => $lst_contacts
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('contacts.lstcontacts',$data)->render();
        
        return Response()->json($response_array);
        
    }
    
    
    /**
     * Disp0lay tab of Lead Notes where you can manage notes of the current Lead
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function LeadContactsManager(Request $request)
    {
        $cl_id = $request->input("cl_id");
        $lst_lead_contacts = CRMContacts::whereFkLeadId($cl_id)->whereCcIsDeleted(0)->get();
        $result_array = array();
        
        $data = array(
            "lst_lead_contacts" => $lst_lead_contacts
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("Leads.leadcontacts",$data)->render();
        
        return Response()->json($result_array);
    }
     
    /**
     * Form to add contact for the selected Lead
     * 
     * @author MOe Mantach
     * @access public
     * @param unknown $cl_id
     */
    public function AddContactForm( $cl_id )
    {
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get();
        $lst_lead_source    = CRMLeadSources::whereLsIsDeleted(0)->get();
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_industries" => $lst_industries,
            "lst_lead_source" => $lst_lead_source,
            "lst_countries" => $lst_countries,
            "lst_departments" => $lst_departments,
            "lst_leads" => $lst_leads,
            "cl_id" => $cl_id,
        ); 
        return Response()->view("leads.addleadcontact",$data);
    }
    
    
    
    
    public function AddForm()
    {
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get(); 
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        $lst_accounts       = CRMAccounts::whereCaIsDeleted(0)->get();
        
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_industries" => $lst_industries,
            "lst_countries" => $lst_countries,
            "lst_departments" => $lst_departments,
            "lst_leads" => $lst_leads,
            "lst_accounts" => $lst_accounts
        ); 
        return Response()->view("contacts.addcontact",$data);
    }
    
    
    
    /**
     * Edit Contact Form 
     * 
     * @author Moe Mantach
     * @access public
     * @param number $cc_id
     */
    public function EditForm($cc_id)
    {
        $contact_info       = CRMContacts::find($cc_id);
        $lst_users          = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_industries     = Industry::whereSiIsDeleted(0)->get();
        $lst_lead_source    = CRMLeadSources::whereLsIsDeleted(0)->get();
        $lst_leads          = CRMLeads::whereClIsDeleted(0)->get();
        $lst_departments    = Departments::whereSdIsDeleted(0)->get();
        $lst_countries      = Countries::all();
        
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_industries" => $lst_industries,
            "lst_lead_source" => $lst_lead_source,
            "lst_countries" => $lst_countries,
            "lst_departments" => $lst_departments,
            "lst_leads" => $lst_leads,
            "contact_info" => $contact_info
        );
        return Response()->view("contacts.editcontact",$data);
    }
    
    
    /**
     * Save Contact info to the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveContactInfo(Request $request)
    {
        $cc_id                  = $request->input("cc_id");
        $fk_owner_id            = $request->input("fk_owner_id"); 
        $cc_first_name          = $request->input("cc_first_name");
        $cc_last_name           = $request->input("cc_last_name");
        $fk_lead_id             = $request->input("fk_lead_id");
        $cc_contact_title       = $request->input("cc_contact_title");
        $cc_contact_email       = $request->input("cc_contact_email");
        $cc_contact_department  = $request->input("cc_contact_department");
        $cc_contact_phone       = $request->input("cc_contact_phone");
        $cc_contact_home_phone  = $request->input("cc_contact_home_phone");
        $cc_contact_other_phone = $request->input("cc_contact_other_phone");
        $cc_contact_fax         = $request->input("cc_contact_fax");
        $cc_contact_mobile      = $request->input("cc_contact_mobile");
        $cc_contact_dob         = $request->input("cc_contact_dob");
        $cc_contact_dob         = strtotime($cc_contact_dob);
        $cc_contact_dob         = date("Y-m-d",$cc_contact_dob);
        $cc_contact_assistant   = $request->input("cc_contact_assistant");
        $cc_contact_asst_phone  = $request->input("cc_contact_asst_phone");
        $cc_skype_id            = $request->input("cc_skype_id");
        $cc_second_email        = $request->input("cc_second_email");
        $cc_reporting_to        = $request->input("cc_reporting_to");
        $cc_twitter_id          = $request->input("cc_twitter_id");
        $cc_mailing_country     = $request->input("cc_mailing_country");
        $cc_mailing_city        = $request->input("cc_mailing_city");
        $cc_mailing_state       = $request->input("cc_mailing_state");
        $cc_mailing_code        = $request->input("cc_mailing_code");
        $cc_mailing_street      = $request->input("cc_mailing_street");
        $cc_other_country     = $request->input("cc_other_country");
        $cc_other_city        = $request->input("cc_other_city");
        $cc_other_state       = $request->input("cc_other_state");
        $cc_other_code        = $request->input("cc_other_code");
        $cc_other_street      = $request->input("cc_other_street");
        $cc_image_base_src      = "";
        $cc_image_file_name     = "";
        $cc_image_extension     = "";
        
        
        $CRMContactObj = new CRMContacts();
        $COntactManager = new ContactsManager();
        
        
        if($cc_id != null)
        {
            $CRMContactObj = CRMContacts::find($cc_id);
        }
        
        
        if(count($_FILES) > 0 )
        {
            $image_data =  $COntactManager->UploadContactAvatar($cc_id);
            
            $CRMContactObj->cc_image_base_src   = $image_data['data']['cc_image_base_src'];
            $CRMContactObj->cc_image_file_name  = $image_data['data']['cc_image_file_name'];
            $CRMContactObj->cc_image_extension  = $image_data['data']['cc_image_extension'];
            
        }
        
        $CRMContactObj->fk_owner_id         = $fk_owner_id;
        $CRMContactObj->cc_first_name       = $cc_first_name;
        $CRMContactObj->cc_last_name        = $cc_last_name;
        $CRMContactObj->fk_lead_id          = $fk_lead_id;
        $CRMContactObj->cc_contact_title    = $cc_contact_title;
        $CRMContactObj->cc_contact_email        = $cc_contact_email;
        $CRMContactObj->cc_contact_department   = $cc_contact_department;
        $CRMContactObj->cc_contact_phone        = $cc_contact_phone;
        $CRMContactObj->cc_contact_home_phone   = $cc_contact_home_phone;
        $CRMContactObj->cc_contact_other_phone  = $cc_contact_other_phone;
        $CRMContactObj->cc_contact_fax          = $cc_contact_fax;
        $CRMContactObj->cc_contact_mobile       = $cc_contact_mobile;
        $CRMContactObj->cc_contact_dob          = $cc_contact_dob;
        $CRMContactObj->cc_contact_assistant    = $cc_contact_assistant;
        $CRMContactObj->cc_contact_asst_phone   = $cc_contact_asst_phone;
        $CRMContactObj->cc_skype_id             = $cc_skype_id;
        $CRMContactObj->cc_second_email         = $cc_second_email;
        $CRMContactObj->cc_reporting_to         = $cc_reporting_to;
        $CRMContactObj->cc_twitter_id           = $cc_twitter_id;
        $CRMContactObj->cc_mailing_country      = $cc_mailing_country;
        $CRMContactObj->cc_mailing_city         = $cc_mailing_city;
        $CRMContactObj->cc_mailing_state        = $cc_mailing_state;
        $CRMContactObj->cc_mailing_code         = $cc_mailing_code;
        $CRMContactObj->cc_mailing_street       = $cc_mailing_street;
        $CRMContactObj->cc_other_country        = $cc_other_country;
        $CRMContactObj->cc_other_city           = $cc_other_city;
        $CRMContactObj->cc_other_state          = $cc_other_state;
        $CRMContactObj->cc_other_code           = $cc_other_code;
        $CRMContactObj->cc_other_street         = $cc_other_street;
        
        $CRMContactObj->save();
        
        $result_array = array();
        
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Contact and informatioan from the database
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteContactInfo(Request $request)
    {
        $cc_id= $request->input('cc_id');
        
        $crm_contact = CRMContacts::find( $cc_id);
        $crm_contact->cc_is_deleted          = 1;
        $crm_contact->cc_deleted_by          = Session('user_id');
        $crm_contact->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}