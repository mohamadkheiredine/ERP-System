<?php
/***********************************************************
WareHouseController.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 22, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\Http\Controllers\WareHouses;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouseMovement;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseZones;
use App\library\WarehouseManager;
use App\models\Inventory\WareHouseEmployees;
use App\models\Users\Users;
use App\models\Logistics\Vehicules;
use App\models\Inventory\WareHouseVehicules;
use App\models\Inventory\Stocks;
use App\models\Inventory\Products;
use League\Csv\Writer;
use League\Csv\Reader;
use Dompdf\Dompdf;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;



class WareHouseController extends Controller
{

    public function index()
    {
        $data = array();
        return Response()->view("warehouses.warehouses",$data);
    }


    /**
     * get and display list of warehouses saved in the databasae
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayList(Request $request)
    {
        $general_search = $request->input('general_search');
        $w_company_id   = session('default_company_id');

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->whereIn('w_company_id', array(0,$w_company_id));
        if(strlen($general_search) > 0)
        {
            $lst_warehouses = $lst_warehouses->where("w_warehouse_name","LIKE",'%' . $general_search . '%');
            $lst_warehouses = $lst_warehouses->orWhere("w_warehouse_description","LIKE",'%' . $general_search . '%');
        }
        $lst_warehouses = $lst_warehouses->get();

        $response_array = array();

        $data = array(
            "lst_warehouses" => $lst_warehouses
        );
        $response_array['is_error'] = 0;
        $response_array['display'] = view('warehouses.displaylist',$data)->render();

        return Response()->json($response_array);
    }

    /**
     * Open form of add new warehouse
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddNewWarehouse()
    {
        $warehouseManager = new WarehouseManager();

        $warehouse_code = $warehouseManager->GenerateWarehouserCode();

        $lstWarehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_vehicules = Vehicules::whereLvVeIsDeleted(0)->get();

        $data = array(
            'warehouse_code' => $warehouse_code,
            'lstWarehouses' => $lstWarehouses,
            'lst_vehicules' => $lst_vehicules,
        );

        return Response()->view('warehouses.addwarehouse',$data);
    }

    /**
     * get information of selected warehouse and open the edit form fields
     * @param unknown $w_id
     * @return \Illuminate\Http\Response
     */
    public function EditWarehouse($w_id)
    {
        $wareHouse = WareHouses::find($w_id);

        $warehouse_vehicules =  WareHouseVehicules::whereFkWarehouseId($w_id)->get();

        $wv_array = array();

        foreach ($warehouse_vehicules as $key => $wv_info) {
            $wv_array[] = $wv_info->fk_vehicule_id;
        }


        $lstWarehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_vehicules = Vehicules::whereLvVeIsDeleted(0)->whereNotIn('lv_id', $wv_array)->get();
        $lst_allowed_vehicules = Vehicules::whereLvVeIsDeleted(0)->whereIn('lv_id', $wv_array)->get();

        $data = array(
            'wareHouseInfo' => $wareHouse,
            'lstWarehouses' => $lstWarehouses,
            'lst_vehicules' => $lst_vehicules,
            'lst_allowed_vehicules' => $lst_allowed_vehicules
        );

        return Response()->view('warehouses.editwarehouse',$data);

    }


    /**
     * function to save data of warehouse to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveWareHouse(Request $request)
    {
        $w_id                   = $request->input('w_id');
        $w_warehouse_ref        = $request->input('w_warehouse_ref');
        $w_warehouse_name       = $request->input('w_warehouse_name');
        $w_warehouse_zipcode    = $request->input('w_warehouse_zipcode');
        $w_warehouse_city       = $request->input('w_warehouse_city');
        $w_warehouse_size_type  = $request->input('w_warehouse_size_type');
        $w_warehouse_description= $request->input('w_warehouse_description');
        $w_warehouse_status     = $request->input('w_warehouse_status');
        $w_opening_time         = $request->input('w_opening_time');
        $w_closing_time         = $request->input('w_closing_time');
        $w_warehouse_location   = $request->input('w_warehouse_location');
        $w_material_warehouse   = $request->input('w_material_warehouse') == null ? 0 : 1;
        $w_company_id   = session('default_company_id');
        if($w_warehouse_status == null)
            $w_warehouse_status = 0;
        $allowed_vehicules      = $request->input('allowed_vehicules');
        $fk_w_id                = $request->input('fk_w_id');

        $WareHouse = new WareHouses();

        if($w_id > 0)
        {
            $WareHouse = WareHouses::find($w_id);
        }
        else
        {

            $WareHouse->w_warehouse_created_by      = Session('user_id');
            $WareHouse->w_warehouse_creation_date   = date('Y-m-d H:i:s');
        }

        $WareHouse->w_warehouse_ref             = $w_warehouse_ref;
        $WareHouse->w_warehouse_name            = $w_warehouse_name;
        $WareHouse->fk_w_id                     = $fk_w_id;
        $WareHouse->w_warehouse_zipcode         = $w_warehouse_zipcode;
        $WareHouse->w_warehouse_city            = $w_warehouse_city;
        $WareHouse->w_warehouse_size_type       = $w_warehouse_size_type;
        $WareHouse->w_warehouse_description     = $w_warehouse_description;
        $WareHouse->w_warehouse_status          = $w_warehouse_status;
        $WareHouse->w_material_warehouse        = $w_material_warehouse;
        $WareHouse->w_opening_time              = $w_opening_time;
        $WareHouse->w_closing_time              = $w_closing_time;
        $WareHouse->w_warehouse_location        = $w_warehouse_location;
        $WareHouse->w_company_id                = $w_company_id;
        $WareHouse->save();

        $w_id = $WareHouse->w_id;


        // delete the rows of warehouse vehicules and save it again
        $vehicules_delete = WareHouseVehicules::whereFkWarehouseId($w_id)->delete();

        if(isset($allowed_vehicules) && count($allowed_vehicules) > 0)
        {
            foreach ( $allowed_vehicules as $key => $vehicule_id ) {

                $warehousevehicule = new WareHouseVehicules();
                $warehousevehicule->fk_warehouse_id = $w_id;
                $warehousevehicule->fk_vehicule_id  = $vehicule_id;
                $warehousevehicule->save();
            }
        }


        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);

    }


    /**
     * Delete warehouse info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteWarehouseInfo(Request $request)
    {
        $w_id = $request->input('w_id');
        $result_array = array();


        // check if the warehouse contain product if it's contain you cannot delete it
        $count_warehouse_stock = Stocks::whereFkWarehouseId($w_id)->whereIsIsDeleted(0)->count();
        if($count_warehouse_stock > 0)
        {
            $result_array['is_error'] = 0;
            $result_array['error_msg'] = "Warehouse Contain stock , Please move all the stocks before remove it ";
            return Response()->json($result_array);
        }



        $Warehouse = WareHouses::find($w_id);
        $Warehouse->w_is_deleted = 1;
        $Warehouse->w_deleted_by = session('user_id');
        $Warehouse->save();




        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }



    /**
     * Remove warehouse employee from the database and unlink an employee
     * as a employee work in this warehouse
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Response
     */
    public function RemoveWarehouseEmployee(Request $request)
    {
        $we_id          = $request->input('we_id');
        $warehouse_id   = $request->input('warehouse_id');

        $WarehouseEmployee = WareHouseEmployees::whereFkWarehouseId($warehouse_id)->whereFkEmployeeId($we_id)->delete();


        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Delete Warehouse zone from a warehouse
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteWarehouseZone(Request $request)
    {
        $wz_id = $request->input('wz_id');

        $WarehouseZone = WareHouseZones::find($wz_id);
        $WarehouseZone->delete();


        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }


    /**
     * Display the age of warehouse settings
     *
     * @author Moe mantach
     * @param Integer $w_id
     */
    public function WareHouseSettings( $w_id )
    {

        $WareHouse = WareHouses::find($w_id);
        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();

        $data = array(
            'lst_users' => $lst_users,
            'wareHouseInfo' => $WareHouse,
            'w_id' => $w_id
        );

        return Response()->view('warehouses.settings',$data);
    }


    /**
     * Display Settings tab based on value of tab hidden field
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplaySettingsTabs(Request $request)
    {
        $tab            = $request->input("tab");
        $warehouse_id   = $request->input("warehouse_id");
        $result_array = array();

        $warehouse_info = WareHouses::find($warehouse_id);
        $WarehouseManager = new WarehouseManager();

        switch ($tab)
        {
            case "warehouse_dimension":
                {
                    $data = array(
                        "warehouse_info" => $warehouse_info
                    );

                    switch ($warehouse_info->w_warehouse_size_type)
                    {
                        case WareHouses::WT_SIZE_SIZE_TYPE :
                            {
                                $result_array['display'] = view("warehouses.dimensions",$data)->render();
                            }
                         break;
                        case WareHouses::WT_AREA_SIZE_TYPE :
                            {
                                $result_array['display'] = view("warehouses.areadimesions",$data)->render();
                            }
                         break;
                        case WareHouses::WT_LIQUID_SIZE_TYPE :
                            {
                                $result_array['display'] = view("warehouses.volumedimesions",$data)->render();
                            }
                         break;
                    }


                }
            break;
            case "warehouse_zones":
                {
                    $warehousezones_obj = WareHouseZones::whereFkWarehouseId($warehouse_id)->get();

                    $data = array(
                        "warehouse_info" => $warehouse_info,
                        "warehousezones_obj" => $warehousezones_obj,
                    );
                    $result_array['display'] = view("warehouses.zones",$data)->render();
                }
            break;
            case "warehouse_employees":
                {
                    $WareHouseEmployees = WareHouseEmployees::whereFkWarehouseId($warehouse_id)->get();

                    // get employee info by user id
                    $employees_info = $WarehouseManager->GetEmployeeInfo($WareHouseEmployees);

                    $data = array(
                        "warehouse_info" => $warehouse_info,
                        "WareHouseEmployees" => $WareHouseEmployees,
                        "employees_info" => $employees_info
                       // "lst_users" => $lst_users,
                    );
                    $result_array['display'] = view("warehouses.employees",$data)->render();
                    unset($WareHouseEmployees);
                }
            break;
            case "warehouse_load":
                {
                    $lst_stock = Stocks::whereFkWarehouseId($warehouse_id)->get();
                    $products_warehouse = Products::whereFkWarehouseId($warehouse_id)->wherePProductIsDeleted(0)->get();

                    // get  array for chart stock by zones
                    $lst_zonesstock_array = array();
                    foreach ( $lst_stock as $key => $stock_info ) {
                        //
                        $zone_id = $stock_info->fk_zone_id;
                        $zone_label = ( $zone_id == 0 ) ? "No Zone" : $stock_info->Zones->wz_zone_label;


                        $lst_zonesstock_array[$zone_label] = isset( $lst_zonesstock_array[$zone_label]) ? ( $lst_zonesstock_array[$zone_label] + $stock_info->is_quanity ) : $stock_info->is_quanity;
                    }

                    $zonesstock_array = array();

                    foreach ($lst_zonesstock_array as $label => $quantity) {
                        $zonesstock_array[] = array(
                            'zone' => $label,
                            'quantity' => $quantity
                        );
                    }


                    $lst_productsstock_array = array();
                    foreach ( $lst_stock as $key => $stock_info ) {

                        if($stock_info->products)
                        {
                            if(isset($lst_productsstock_array[ $stock_info->products->p_product_name ])  )
                            {
                                $lst_productsstock_array[ $stock_info->products->p_product_name ] = ( $lst_productsstock_array[ $stock_info->products->p_product_name ] + $stock_info->is_quanity );
                            }
                            else {
                                $lst_productsstock_array[ $stock_info->products->p_product_name ] =  $stock_info->is_quanity;
                            }
                        }
                    }


                    foreach ( $products_warehouse as $key => $product_info ) {
                        if( isset($lst_productsstock_array[ $product_info->p_product_name ])  )
                        {
                            $lst_productsstock_array[ $product_info->p_product_name ] = $lst_productsstock_array[ $product_info->p_product_name ] + $product_info->p_product_quantity;
                        }
                        else {
                            $lst_productsstock_array[ $product_info->p_product_name ] =  $product_info->p_product_quantity;
                        }

                    }


                    // get list of porducts by quantity
                    $products_quantity_array = array();
                    foreach ($lst_productsstock_array as $label => $quantity) {
                        $products_quantity_array[] = array(
                            'product' => $label,
                            'quantity' => $quantity
                        );
                    }


                    $data = array(
                        "warehouse_info" => $warehouse_info,
                        "lst_stock" => $lst_stock,
                    );
                    $result_array['display']    = view("warehouses.load",$data)->render();
                    $result_array['zonesstock'] = (String)json_encode($zonesstock_array);
                    $result_array['productstock'] = (String)json_encode($products_quantity_array);
                }
            break;
        }

        unset($WarehouseManager);

        return Response()->json($result_array);
    }


    /**
     * Assign New Employee to selected warehouse
     * Save New row of warehouse Employee
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function AddWarehouseEmployee(Request $request)
    {
        $warehouse_id   = $request->input("warehouse_id");
        $fk_user_id     = $request->input("fk_user_id");
        $result_array   = array();

        $WarehouseEmployees = new WareHouseEmployees();
        $WarehouseEmployees->fk_warehouse_id    = $warehouse_id;
        $WarehouseEmployees->fk_employee_id     = $fk_user_id;
        $WarehouseEmployees->save();


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }


    public function ExpiryDateReport(Request $request)
    {
        $days = $request->input('days', 30); // default 30 days upcoming

        $sql = "
        SELECT
            p.p_id,
            p.p_product_name,
            p.p_product_ref,
            s.is_stock_label,
            s.is_quanity AS quantity,
            s.is_expiry_date AS expiry_date,
            w.w_warehouse_name AS warehouse_name,
            DATEDIFF(s.is_expiry_date, CURDATE()) AS days_to_expiry,
            CASE
                WHEN s.is_expiry_date < CURDATE() THEN 'Expired'
                WHEN s.is_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY) THEN 'Expiring Soon'
                ELSE 'Valid'
            END AS expiry_status
        FROM inventory_stocks AS s
        LEFT JOIN inventory_products AS p ON s.fk_product_id = p.p_id
        LEFT JOIN inventory_warehouses AS w ON s.fk_warehouse_id = w.w_id
        WHERE s.is_is_deleted = 0
          AND s.is_expiry_date IS NOT NULL
        ORDER BY s.is_expiry_date ASC
    ";

        $results = DB::select($sql, [$days]);

        return view('reports.stock-expiry', compact('results', 'days'));
    }


    /**
     * Save warehouse settings
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Savewarehousesettings(Request $request)
    {
        $tab = $request->input('tab');

        $warehousesettings_obj = new WarehouseManager();
        $result_array = array();
        switch ($tab)
        {
            case "warehouse_dimension":
                {
                    $result_array = $warehousesettings_obj->SaveWarehouseDimensionsInfo($request);
                }
                break;
            case "warehouse_zones":
                {

                }
                break;
            case "warehouse_employees":
                {

                }
                break;
            case "warehouse_load":
                {

                }
                break;
        }

        return Response()->json($result_array);
    }



    /**
     * Page of Warehouse zone
     * @param Request $request
     */
    public function AddWarezone( $w_id )
    {
        $warehouseInfo = WareHouses::find($w_id);

        $data = array(
            "warehouseInfo" => $warehouseInfo
        );

        return view("warehouses.addzone",$data)->render();
    }



    public function EditWarehousezone( $wz_id )
    {
        $warehouseZoneInfo = WareHouseZones::find($wz_id);

        $data = array(
            "warehouseZoneInfo" => $warehouseZoneInfo
        );

        return view("warehouses.editzone",$data)->render();
    }


    public function Displaydimensions(Request $request)
    {
        $warehouse_id = $request->input("warehouse_id");

        $WareHouseInfo = WareHouses::find($warehouse_id);


        $result_array = array();

        $data = array(
            "WareHouseInfo" => $WareHouseInfo
        );
        $result_array['is_error'] = 0;
        $result_array['display'] = view("warehouses.dimensions",$data)->render();
        return Response()->json($result_array);
    }


    public function SaveWarehouseZone(Request $request)
    {
        $wz_id                  = $request->input("wz_id");
        $fk_warehouse_id        = $request->input("w_id");
        $wz_zone_label          = $request->input("wz_zone_label");
        $wz_zone_color          = $request->input("wz_zone_color");
        $result_array = array();

        $WarehouseZone = new WareHouseZones();

        if($wz_id != null)
        {
            $WarehouseZone = WareHouseZones::find($wz_id);
        }



        $WarehouseZone->fk_warehouse_id     = $fk_warehouse_id;
        $WarehouseZone->wz_zone_label       = $wz_zone_label;
        $WarehouseZone->wz_zone_color       = $wz_zone_color;
        $WarehouseZone->save();




        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";
        return Response()->json($result_array);
    }




    public function WarehouseStockAvailability(Request $request)
    {

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            'lst_warehouses' => $lst_warehouses
        );

        return Response()->view('warehouses.stockavailability',$data);
    }

    public function WarehouseStockMovements(Request $request)
    {

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            'lst_warehouses' => $lst_warehouses
        );

        return Response()->view('warehouses.stockmovements',$data);
    }



    public function displaylistWarehouseMovement(Request $request)
    {
        $sm_stock_warehouse = $request->input("sm_stock_warehouse");
        $sm_upto_date = $request->input("sm_upto_date");

        $lst_warehouse_movements = new WareHouseMovement();

        if($sm_stock_warehouse > 0)
            $lst_warehouse_movements = $lst_warehouse_movements->where('wm_warehouse_id', $sm_stock_warehouse);

        if(strlen($sm_upto_date) > 0)
            $lst_warehouse_movements = $lst_warehouse_movements->where('wm_action_date', '<=' ,  $sm_upto_date);

        $lst_warehouse_movements = $lst_warehouse_movements->get();



        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";

        $data = array(
            "lst_warehouse_movements" => $lst_warehouse_movements
        );
        $result_array['display'] = view("warehouses.lstrwarehousemovement",$data)->render();

        return Response()->json($result_array);
    }


    public function DisplayListStockAvailability(Request $request)
    {
        $sw_stock_warehouse = $request->input("sw_stock_warehouse");
        $where_cond = "";
        if($sw_stock_warehouse > 0)
        {
            $where_cond = " AND stock.fk_warehouse_id = " . $sw_stock_warehouse;
        }
        $query = "SELECT
        p_product_name,
        p_barcode,
        p_id,
        w_warehouse_name,
        fk_product_id,
        is_price_item,
        is_price_stock,
        SUM(is_quanity) as total_quantity
    FROM inventory_stocks as stock
    LEFT JOIN inventory_products as product ON product.p_id = stock.fk_product_id
    LEFT JOIN inventory_warehouses as warehouse ON warehouse.w_id = stock.fk_warehouse_id
    WHERE is_is_deleted = 0 " . $where_cond . "
    GROUP BY stock.fk_product_id, stock.fk_warehouse_id,stock.is_price_item,is_price_stock
    HAVING SUM(is_quanity) > 0;";
        $lst_stock_availability = DB::select($query);

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";

        $data = array(
            "lst_stock_availability" => $lst_stock_availability
        );
        $result_array['display'] = view("warehouses.lststockavailability",$data)->render();


        return Response()->json($result_array);
    }


    /**
     * Download stock availability report
     * @param Request $request
     * @return void
     */
    public function DownloadStockAvailability(Request $request)
    {
        $sw_stock_warehouse = $request->input("sw_stock_warehouse");
        $type = $request->input("type");
        $where_cond = "";
        if($sw_stock_warehouse > 0)
        {
            $where_cond = " AND stock.fk_warehouse_id = " . $sw_stock_warehouse;
        }
        $query = "SELECT
    p_product_name,
    p_barcode,
    p_id,
    w_warehouse_name,
    fk_product_id,
    is_price_item,
    is_price_stock,
    SUM(is_quanity) as total_quantity,
FROM inventory_stocks as stock
LEFT JOIN inventory_products as product ON product.p_id = stock.fk_product_id
LEFT JOIN inventory_warehouses as warehouse ON warehouse.w_id = stock.fk_warehouse_id
WHERE is_is_deleted = 0 " . $where_cond . "
GROUP BY stock.fk_product_id, stock.fk_warehouse_id,stock.is_price_item,is_price_stock
HAVING SUM(is_quanity) > 0;";

        $lst_stock_availability = DB::select($query);

        if($type == 'csv')
        {
            $data = array();
            $data[] = ['id', 'Warehouse','Product','Cost Item','Total Cost Price','Stock Quantity'];

            foreach ($lst_stock_availability as $index => $stock_info)
            {
                $data[] = [$stock_info->p_id, $stock_info->w_warehouse_name, $stock_info->p_product_name,$stock_info->is_price_item,$stock_info->total_price,$stock_info->total_quantity];
            }


            $csv = Writer::createFromFileObject(new \SplTempFileObject());

            $csv->insertAll($data);

            return $csv->output('data.csv');
        }
        else
        {

            $data = array(
                "lst_stock_availability" => $lst_stock_availability
            );
            $display = view('warehouses.printstockavailability',$data)->render();

            return PDF::loadHTML($display)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->download('stockavailability-report.pdf');
        }



    }


    public function DownloadStockMovements(Request $request)
    {
        $sm_stock_warehouse = $request->input("sm_stock_warehouse");
        $sm_upto_date = $request->input("sm_upto_date");
        $type = $request->input("type");

        $lst_warehouse_movements = new WareHouseMovement();

        if($sm_stock_warehouse > 0)
            $lst_warehouse_movements = $lst_warehouse_movements->where('wm_warehouse_id', $sm_stock_warehouse);

        if(strlen($sm_upto_date) > 0)
            $lst_warehouse_movements = $lst_warehouse_movements->where('wm_action_date', '<=' ,  $sm_upto_date);

        $lst_warehouse_movements = $lst_warehouse_movements->get();

        if($type == 'csv')
        {
            $data = array();
            $data[] = ['Type', 'Description', 'Warehouse','Product','Stock Quantity'];

            foreach ($lst_warehouse_movements as $index => $stock_info)
            {
                $data[] = [$stock_info->wm_action_type, $stock_info->wm_action_description, $stock_info->Warehouse->w_warehouse_name,$stock_info->Product->p_barcode . " - " . $stock_info->Product->p_product_name , $stock_info->wm_quantity];
            }

            $csv = Writer::createFromFileObject(new \SplTempFileObject());

            $csv->insertAll($data);

            return $csv->output('data.csv');
        }
        else
        {

            $data = array(
                "lst_warehouse_movements" => $lst_warehouse_movements
            );
            $display = view('warehouses.printstockmovement',$data)->render();

            return PDF::loadHTML($display)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->download('warehouse-stockmovement-report.pdf');
        }



    }

}
