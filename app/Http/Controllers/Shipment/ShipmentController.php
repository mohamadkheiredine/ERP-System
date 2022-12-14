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
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\WareHouses;
use App\models\CRM\CRMAccounts;
use App\models\Logistics\ShipOperations;
use App\models\Logistics\ShipOperationStatus;
use App\models\Inventory\Stocks;
use App\models\Inventory\Products;
use App\models\Logistics\ShipOperationProducts;
use App\Library\WarehouseManager;



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
      
        
        $lst_accounts = CRMAccounts::whereCaIsDeleted(0)->get();
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
        $result_array   = array();
        $lst_operations = ShipOperations::whereSoIsDeleted(0)->get();
        
        $data = array(
            "lst_operations" => $lst_operations
        );
        $result_array['display'] = view('shipment.displaylistoperations',$data)->render();
        
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
        $shipmentOperation->so_operation_time           = $so_operation_time;
        $shipmentOperation->so_owner_id                 = Session("user_id");
        $shipmentOperation->so_warehouse_source         = $so_warehouse_source;
        $shipmentOperation->so_warehouse_destination    = $so_warehouse_destination;
        $shipmentOperation->so_creation_date            = date("Y-m-d H:i:s");
        $shipmentOperation->save();
       
        $so_id = $shipmentOperation->so_id;
        
        
        if($products != null)
        {
            // save operation products info
            foreach ($products as $index  => $product)
            {
                $OperationProducts = new ShipOperationProducts();
                
                $product_id = $product;
                $product_quantity = $quantity[ $index ];
                
                
                $OperationProducts->fk_operation_id     = $so_id;
                $OperationProducts->fk_warehouse_id     = $so_warehouse_source;
                $OperationProducts->fk_product_id       = $product_id;
                $OperationProducts->op_product_quantity = $product_quantity;
                $OperationProducts->save();
                
                // save stock movement
                $warehouseManager = new WarehouseManager();
                $params_array = array(
                    "product_id" => $product_id,
                    "warehouse_source" => $so_warehouse_source,
                    "warehouse_destination" => $so_warehouse_destination,
                    "stock_quanity" => $product_quantity
                );
                $result = $warehouseManager->MoveStockProducts($params_array);
                
            }
            $result_array = $result;
        }
        else 
        {
            $result_array = array();
            $result_array['is_error']   = 0;
            $result_array['error_msg']  = "Operation Completed Successfully";
        }

        
       
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Page to add operation shipment 
     * @return unknown
     */
    public function AddForm()
    {
        $operation_status = ShipOperationStatus::whereOsIsDeleted(0)->get();
        
        $data = array(
            "operation_status" => $operation_status
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
        
        $data = array(
            "operation_status" => $operation_status,
            "operation_shipment" => $operation_shipment
        );
        return Response()->view('shipment.editoperation',$data);
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