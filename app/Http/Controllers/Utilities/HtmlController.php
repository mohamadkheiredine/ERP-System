<?php
/***********************************************************
HtmlController.php
Product :
Version : 1.0
Release : 1
Date Created : May 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/





namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input;
use Session;
use Config;
use Redirect;
use File;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\models\System\Appconfig;
use App\models\Restaurants\Restaurant;
use App\models\Restaurants\RestaurantCategories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\WareHouseVehicules;
use App\models\Logistics\Vehicules;
use App\models\CRM\CRMServices;
use App\models\Inventory\WareHouseZones;
use App\models\System\Units;



class HtmlController extends Controller
{

    public function GetDropdown( $key , Request $request)
   {
       $result_array = array();


       switch($key)
       {
           case "warehouse_vehicules":
               {

                   $w_id = $request->input("w_id");
                   $vehicule_warehouses = WareHouseVehicules::whereFkWarehouseId($w_id)->get();

                   $vehicule_ids = array();

                   foreach ($vehicule_warehouses as $key => $vw_info)
                   {
                       $vehicule_ids[] = $vw_info->fk_vehicule_id;
                   }

                   $lst_vehicules = Vehicules::whereIn("lv_id",$vehicule_ids)->get();

                   $vehicules_array = array();

                   foreach ($lst_vehicules as $key => $value)
                   {
                       $vehicules_array[$value->lv_id] = $value->lv_vehicule_name;
                   }


                   $data = array(
                       "html_array" => $vehicules_array,
                       "name" => 'so_operation_vehicule',
                       "id" => 'SO_OPERATION_VEHICULE'
                   );
                   $result_array['html'] = view('html.dropdown',$data)->render();

               }
           break;
           case "services":
               {
                 $sc_id = $request->input("sc_id");

                 $lst_services = CRMServices::whereCsIsDeleted(0)->whereFkCategoryId($sc_id)->get();

                 $services_array = array();
                 foreach ($lst_services as $key => $value)
                 {
                     $services_array[$value->cs_id] = $value->cs_service_title;
                 }
                 $data = array(
                     "html_array" => $services_array,
                     "name" => 'cs_service',
                     "id" => 'CS_SERVICE'
                 );
                 $result_array['html'] = view('html.dropdown',$data)->render();

               }
           break;
           case "systemunits":
               {
                   $bm_quantity_type    = $request->input("bm_quantity_type");

                   $list_system_units   = Units::whereSuIsDeleted(0)->whereSuUnityType($bm_quantity_type)->get();

                 $units_array = array();
                 foreach ($list_system_units as $key => $value)
                 {
                     $units_array[$value->su_id] = $value->su_unit_label;
                 }
                 $data = array(
                     "html_array" => $units_array,
                     "name" => 'bm_system_units',
                     "is_required" => true,
                     "value" => 0,
                     "id" => 'BM_SYSTEM_UNITS'
                 );
                 $result_array['dropdown'] = view('html.dropdown',$data)->render();

               }
           break;
           case "zones":
               {
                 $warehouse_id = $request->input("warehouse_id");

                 $lst_warehouse_zones = WareHouseZones::whereWzIsDeleted(0)->whereFkWarehouseId($warehouse_id)->get();

                 $zones_array = array();
                 foreach ( $lst_warehouse_zones as $key => $zone_info )
                 {
                     $zones_array[$zone_info->wz_id] = $zone_info->wz_zone_label;
                 }
                 $data = array(
                     "html_array" => $zones_array,
                     "name" => 'fk_zone_id',
                     "id" => 'FK_ZONE_ID'
                 );
                 $result_array['html'] = view('html.dropdown',$data)->render();

               }
           break;
       }


       return Response()->json($result_array);

   }
}
