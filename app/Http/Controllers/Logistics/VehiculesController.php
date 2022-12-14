<?php
/***********************************************************
VehiculesController.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 7, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
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
use App\Library\ProductCategoriesManager;
use App\models\System\Departments;
use App\models\System\Companies;
use App\models\System\Countries;
use App\Library\CompaniesManager;
use App\models\System\Currency;
use App\models\Logistics\Vehicules;
use App\Library\VehiculesManager;
use App\models\Logistics\VehiculeTypes;
use App\models\Users\Users;



class VehiculesController extends Controller
{
    
    /**
     * Page to control vehicules Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('logistics.vehicules',$data);
    }
    
    
    /**
     * Display list of vehicules saved in the system
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
    public function DisplayList(Request $request)
    {
        $page_number           = $request->input('page_number');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;
                
                $vehicules_count = Vehicules::whereLvVeIsDeleted(0)->count();
                
                
                $total_pages = ceil( $vehicules_count /$nbr_rows_per_pages );
                $total_pages = intval($total_pages);
                
                $lst_vehicules = Vehicules::whereLvVeIsDeleted(0)->skip($skip)->take($nbr_rows_per_pages)->get();
                
                $data = array(
                    "lst_vehicules" => $lst_vehicules
                );
                
                $result_array = array();
                
                $result_array['total_pages'] = $total_pages;
                $result_array['display'] = view("logistics.listvehicules",$data)->render();
                
                return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new vehicule
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $vehicule_types = VehiculeTypes::whereVtIsDeleted(0)->get();
        $lst_users = Users::whereUIsDeleted(0)->get();
        $data = array(
            "vehicule_types" => $vehicule_types,
            "lst_users" => $lst_users,
        );
        return view('logistics.addvehicule',$data);
    }
    
    
    /**
     * Save Vehicules Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveVehiculesInfo(Request $request)
    {
        $lv_id                      = $request->input('lv_id');
        $lv_vehicule_name           = $request->input('lv_vehicule_name');
        $lv_vehicule_number         = $request->input('lv_vehicule_number');
        $lv_plate_number            = $request->input('lv_plate_number');
        $lv_model_year              = $request->input("lv_model_year");
        $lv_vehicule_type           = $request->input("lv_vehicule_type");
        $lv_pickup_size             = $request->input("lv_pickup_size");
        $lv_driver_id               = $request->input("lv_driver_id");
        
        $result_array = array();
        
        $VehiculesManager  = new VehiculesManager();
        $lv_image_base_src      = "";
        $lv_file_name           = "";
        $lv_file_extension      = "";
        
        if(count($_FILES) > 0)
        {
            
            $image_data =  $VehiculesManager->UploadVehiculeAvatar(null);
            
            $lv_image_base_src          = $image_data['data']['lv_image_base_src'];
            $lv_file_name               = $image_data['data']['lv_file_name'];
            $lv_file_extension          = $image_data['data']['lv_file_extension'];
            
        }
        
        $Vehicules = new Vehicules();
        if( $lv_id != null )
        {
            $Vehicules = Vehicules::find( $lv_id);
        }
        
        $Vehicules->lv_vehicule_name    = $lv_vehicule_name;
        $Vehicules->lv_vehicule_number  = $lv_vehicule_number;
        $Vehicules->lv_plate_number     = $lv_plate_number;
        $Vehicules->lv_model_year       = $lv_model_year;
        $Vehicules->lv_vehicule_type    = $lv_vehicule_type;
        $Vehicules->lv_pickup_size      = $lv_pickup_size;
        $Vehicules->lv_driver_id        = $lv_driver_id;
        
        if(strlen($lv_image_base_src) > 0)
        {
            $Vehicules->lv_image_base_src      = $lv_image_base_src;
            $Vehicules->lv_file_name           = $lv_file_name;
            $Vehicules->lv_file_extension      = $lv_file_extension;
        }
        
        $Vehicules->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Vehicules Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Display Edit Vehicule Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $lv_id
     */
    public function EditForm( $lv_id )
    {
        $vehicules_info  = Vehicules::find($lv_id); 
        $vehicule_types = VehiculeTypes::whereVtIsDeleted(0)->get();
        $lst_users = Users::whereUIsDeleted(0)->get();
        
        $data = array(
            "vehicules_info" => $vehicules_info,
            "vehicule_types" => $vehicule_types,
            "lst_users" => $lst_users
        );
        return view('logistics.editvehicule',$data);
    }
    
    
    /**
     * Delete Vehicule information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteVehiculeInfo(Request $request)
    {
        
        $lv_id= $request->input('lv_id');
        
        $vehicules = Vehicules::find( $lv_id );
        $vehicules->lv_is_deleted          = 1;
        $vehicules->lv_deleted_by          = Session('user_id');
        $vehicules->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
}