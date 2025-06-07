<?php
/***********************************************************
ShipmentController.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 23, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,   itm Solutions COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\Http\Controllers\Shipment;

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
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\WareHouses;
use App\models\CRM\CRMAccounts;
use App\models\Logistics\ShipOperations;
use App\models\Logistics\ShipOperationStatus;
use App\models\Inventory\Stocks;
use App\models\Inventory\Products;
use App\models\Logistics\ShipOperationProducts;
use App\library\WarehouseManager;
use App\models\System\Countries;
use App\models\Shipment\TransportationMode;
use App\models\Shipment\OperationOrders;
use App\models\Shipment\shippingOrders;
use App\models\Inventory\Customers;
use App\models\Shipment\OrderCategories;
use League\Csv\Writer;
use League\Csv\Reader;


class ShipmentController extends Controller
{

    /**
     * Page of shipment operations , manage all shipment operations
     *
     * @author Moe Mantach
     * @access public
     */
    public function index()
    {


        $lst_accounts = Customers::whereIcIsDeleted(0)->get();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

       $data = array(
           "lst_accounts" => $lst_accounts,
           "lst_warehouses" => $lst_warehouses
       );
       return Response()->view('shipment.operations',$data);
    }


    /**
     * Display List of all operations for the current company
     *
     * @author moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayList(Request $request)
    {
        $account_id     = $request->input("account_id");
        $warehouse_id   = $request->input("warehouse_id");
        $general_search   = $request->input("general_search");
        $page_number           = $request->input('page_number');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;
        $result_array   = array();


        $operations_cond = ShipOperations::whereSoIsDeleted(0);

        if(strlen($general_search) > 0)
        {
            $operations_cond = $operations_cond->where("so_operation_label","LIKE","%" . $general_search . "%");
            $operations_cond = $operations_cond->orWhere("so_operation_description","LIKE","%" . $general_search . "%");
        }


        if($warehouse_id > 0)
        {
            $operations_cond = $operations_cond->where("so_warehouse_source","=",$warehouse_id);
            $operations_cond = $operations_cond->orWhere("so_warehouse_source","=",$warehouse_id);
        }

        $operations_count = $operations_cond->count();

         $total_pages = ceil( $operations_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);


        $lst_operations = $operations_cond->skip($skip)->take($nbr_rows_per_pages)->orderBy('so_operation_date',"ASC")->get();

        $data = array(
            "lst_operations" => $lst_operations
        );

        $result_array['display'] = view('shipment.displaylistoperations',$data)->render();
          $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Display all extra fields based on the selected operation type
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayFormType(Request $request)
    {
        $operation_type = $request->input("operation_type");
        $so_id          = $request->input("so_id");
        $result_array   = array();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $so_info        = ShipOperations::find($so_id);


        switch ($operation_type)
        {
            case 1:
            {
                $data = array(
                    "lst_warehouses" => $lst_warehouses,
                    "so_info" => $so_info
                );
                $result_array['display'] = view("shipment.internal-operation",$data)->render();
            }
            break;
            case 2:
            {
                $lst_clients = CRMAccounts::whereCaIsDeleted(0)->get();

                $data = array(
                    "lst_warehouses" => $lst_warehouses,
                    "lst_clients" => $lst_clients,
                    "so_info" => $so_info
                );
                $result_array['display'] = view("shipment.external-operation",$data)->render();
            }
            break;
        }


        return Response()->json($result_array);

    }

    /**
     * display list of products in current operation
     * @param Request $request
     */
    public function DisplayOperationProducts(Request $request)
    {
        $warehouse_id   = $request->input("warehouse_source");
        $so_id          = $request->input("so_id");
        $operation_type = $request->input("operation_type");
        $result_array = array();
        // get products exist in this warehouse
        $lst_stocks = Stocks::whereFkWarehouseId($warehouse_id)->whereIsIsDeleted(0)->get();
        $product_ids = array();

        foreach ($lst_stocks as $key => $stock_info)
        {
            $product_ids[] = $stock_info->fk_product_id;
        }

        $lst_products = Products::whereIn("p_id",$product_ids)->get();

        $shipment_operations_products = null;
        $shipment_operations = null;
        // checxk if so_id exist
        if($so_id != null)
        {
            $shipment_operations  = ShipOperations::find($so_id);
            $shipment_operations_products = ShipOperationProducts::whereFkOperationId($so_id)->whereIn("fk_product_id",$product_ids)->get();
        }


        $data = array(
            "shipment_operations" => $shipment_operations,
            "shipment_operations_products" => $shipment_operations_products,
            "lst_products" => $lst_products,
            "so_id" => $so_id
        );
        $result_array['display'] = view("shipment.internalproducts",$data)->render();
        return Response()->json($result_array);
    }

    /**
     * Save operation shipment
     * @param Request $request
     * @return unknown
     */
    public function SaveShipmentOperationInfo(Request $request)
    {
        $result_array = array();


        $so_id                      = $request->input("so_id");
        $so_operation_reference     = $request->input("so_operation_reference");
        $so_operation_label         = $request->input("so_operation_label");
        $so_operation_type          = $request->input("so_operation_type");
        $so_operation_status        = $request->input("so_operation_status");
        $so_operation_date          = $request->input("so_operation_date");
        $so_operation_time          = $request->input("so_operation_time");
        $so_warehouse_source        = $request->input("so_warehouse_source");
        $so_delivery_date        = $request->input("so_delivery_date");
        $so_delivery_date = date("Y-m-d", strtotime($so_delivery_date));
        $so_warehouse_destination   = $request->input("so_warehouse_destination");
        $so_operation_description   = $request->input("so_operation_description");
        $products                   = $request->input("products");
        $quantity                   = $request->input("quantity");
        $time                       = date("Y-m-d",strtotime($so_operation_date));



        // save shipment operation
        $shipmentOperation = new ShipOperations();
        if($so_id != null)
        {
            $shipmentOperation = ShipOperations::find($so_id);
        }

        $shipmentOperation->so_operation_reference      = $so_operation_reference;
        $shipmentOperation->so_operation_label          = $so_operation_label;
        $shipmentOperation->so_operation_description    = $so_operation_description;
        $shipmentOperation->so_operation_type           = $so_operation_type;
        $shipmentOperation->so_operation_status         = $so_operation_status;
        $shipmentOperation->so_operation_date           = $time;
        $shipmentOperation->so_delivery_date            = $so_delivery_date;
        $shipmentOperation->so_operation_time           = $so_operation_time;
        $shipmentOperation->so_owner_id                 = Session("user_id");
        $shipmentOperation->so_warehouse_source         = $so_warehouse_source;
        $shipmentOperation->so_warehouse_destination    = $so_warehouse_destination;
        $shipmentOperation->so_creation_date            = date("Y-m-d H:i:s");
        $shipmentOperation->save();

        $so_id = $shipmentOperation->so_id;



        $result_array = array();
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Completed Successfully";

        return Response()->json($result_array);
    }



    /**
     * Page to add operation shipment
     * @return unknown
     */
    public function AddForm()
    {
        $operation_status = ShipOperationStatus::whereOsIsDeleted(0)->get();
        $lst_countries = Countries::all();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_modes = TransportationMode::whereTmIsDeleted(0)->get();
        $operation_code = "ORC" . rand(100000,999999);

        $data = array(
            "operation_status" => $operation_status,
            "lst_warehouses" => $lst_warehouses,
            "lst_modes" => $lst_modes,
            "operation_code" => $operation_code,
            "lst_countries" => $lst_countries
        );
        return Response()->view('shipment.addoperation',$data);
    }


    /**
     * Edit Form info of selected operation
     *
     * @author Moe Mantach
     * @param unknown $so_id
     * @return unknown
     */
    public function EditForm( $so_id )
    {
        $operation_shipment = ShipOperations::find($so_id);
        $operation_status = ShipOperationStatus::whereOsIsDeleted(0)->get();
        $lst_countries = Countries::all();
        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();
        $lst_modes = TransportationMode::whereTmIsDeleted(0)->get();

        $OperationOrders = OperationOrders::whereFkOoOperationId($so_id)->get();
        $lst_op_orders = array();

        foreach ($OperationOrders as $key => $oo_info) {
            $lst_op_orders[] = $oo_info->fk_oo_order_id;
        }


        if(count($lst_op_orders) > 0)
        {
            $lst_drpdown_orders = shippingOrders::whereNotIn('so_id',$lst_op_orders)->whereSoIsDeleted(0)->get();
        }
        else
            $lst_drpdown_orders = shippingOrders::whereSoIsDeleted(0)->get();

        $data = array(
            "operation_status" => $operation_status,
            "operation_shipment" => $operation_shipment,
            "lst_warehouses" => $lst_warehouses,
            "lst_modes" => $lst_modes,
            "lst_drpdown_orders" => $lst_drpdown_orders,
            "lst_countries" => $lst_countries
        );
        return Response()->view('shipment.editoperation',$data);
    }


    /**
     * Displaylist of orders linked to specific operation
     * @param Request $request
     */
    public function Displaylistorders(Request $request)
    {
        $so_id = $request->input('so_id');
        $result_array = array();
        $OperationOrders = OperationOrders::whereFkOoOperationId($so_id)->get();


        $result_array['is_error'] = 0;

        $data = array(
           "OperationOrders"  => $OperationOrders
        );
        $result_array['display'] = view('logistics.displaylistorders',$data)->render();

        return Response()->json($result_array);
    }


    /**
     * Link oorder to an operation
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function LinkOperationOrder(Request $request)
    {
          $so_id = $request->input('so_id');
          $fk_oo_order_id = $request->input('fk_oo_order_id');
          $result_array = array();

          $operation_order = new OperationOrders();
          $operation_order->fk_oo_operation_id = $so_id;
          $operation_order->fk_oo_order_id = $fk_oo_order_id;
          $operation_order->save();


           $result_array['is_error'] = 0;
           $result_array['error_msg'] = "Operation Complete Successfuly";

        return Response()->json($result_array);
    }




     public function DeleteShipmentInfo(Request $request)
    {

        $so_id	      = $request->input('so_id');

        $ship_operation   = ShipOperations::find( $so_id );
        $ship_operation->so_is_deleted          = 1;
        $ship_operation->so_deleted_by          = Session('user_id');
        $ship_operation->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }



    public function DeleteOrderShipmentInfo(Request $request)
    {

        $shiping_id	      = $request->input('shiping_id');
        $order_id	      = $request->input('order_id');

        $del_ship_operation   = OperationOrders::whereFkOoOperationId( $shiping_id )->whereFkOoOrderId($order_id)->delete();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }



    public function DownloadPackingList(Request $request)
    {
        $so_id = $request->input('so_id');

        $data = array();
        $data[] = ['DATE', 'CODE','PACK #','KOLI #','PCS','TYPE OF GOODS','WEIGHT','REMARKS'];

        $lst_operation_orders = OperationOrders::whereFkOoOperationId($so_id)->get();
        $customer_count = array();

        foreach ( $lst_operation_orders as $key => $order_info )
        {
            $so_id = $order_info->Order->so_id;
            $total_weight = 0;
            $lst_categories = OrderCategories::whereFkOrderId($so_id)->get();
            foreach ($lst_categories as $key => $order_cat)
            {
                $total_weight = $total_weight + floatval($order_cat->so_package_weight);
            }

            foreach ($lst_categories as $key => $order_cat)
            {
                if(isset($customer_count[$order_info->Order->Customer->ic_id]))
                    $customer_count[$order_info->Order->Customer->ic_id]++;
                else
                    $customer_count[$order_info->Order->Customer->ic_id] = 1;

                $data[] = [
                    date("d/m/Y",strtotime($order_info->Operation->so_delivery_date)),
                    $order_info->Order->Customer->ic_customer_code,
                    $customer_count[$order_info->Order->Customer->ic_id],
                    1,
                    '-',
                    $order_cat->Category->pc_category,
                    $total_weight,
                    ''
                ];
            }



        }


        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertAll($data);

       return $csv->output('data.csv');
    }


    /**
     * Map Tracker where you can track all operations running in the current time
     *
     * @author Moe Mantach
     * @access public
     * @return unknown
     */
    public function MapTracker()
    {

        $data = array();
        return Response()->view('shipment.maptracker',$data);
    }
}
