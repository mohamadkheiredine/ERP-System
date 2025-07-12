<?php
/***********************************************************
ProductStockTransferController.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\Inventory;

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
use App\models\Inventory\Products;
use App\models\Inventory\ProductCategories;
use Milon\Barcode\DNS1D;
use models\Product;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\Inventory\ProductLots;
use App\library\ProductManager;
use App\models\Inventory\WareHouses;
use App\models\Users\Users;



class ProductStockTransferController extends Controller
{

    public function index()
    {
        $lst_warehouse  = WareHouses::whereWIsDeleted(0)->get();


        $data = array(
            "lst_warehouse" => $lst_warehouse
        );
        return Response()->view("stocks.stockmovements",$data);
    }


    /**
     * Page to manage transfer stock from warehouse to warehouse
     *
     * @author Moe Mantach
     * @access public
     */
    public function transferstock()
    {
        $lst_warehouse  = WareHouses::whereWIsDeleted(0)->get();
        $lst_products   = Products::wherePProductIsDeleted(0)->get();


        $data = array(
            "lst_warehouse" => $lst_warehouse,
            "lst_products" => $lst_products
        );
        return Response()->view("stocks.stocktransfer",$data);
    }

    /**
     * Add Transfer Items to main array
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function AddTransferItems( Request $request )
    {
        $list_transfer_items            = $request->input('list_transfer_items');
        $mp_product_id                  = $request->input('mp_product_id');
        $mp_movement_quantity           = $request->input('mp_movement_quantity');
        $mp_item_notes                  = $request->input('mp_item_notes');
        $result_array = array();
        $lst_items = array();
        if($list_transfer_items != "")
        {
            $lst_items = json_decode($list_transfer_items);
        }

        $product_info = Products::find($mp_product_id);

        $item = array(
            'mp_product_id' => $mp_product_id,
            'mp_movement_quantity' => $mp_movement_quantity,
            'mp_item_notes' => $mp_item_notes,
            'mp_product_name' => $product_info->p_product_name,
            'mp_product_ref' => $product_info->p_product_ref
        );

        $lst_items[] = $item;


        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Add Item To list of Products";
        $result_array['lst_items'] = json_encode($lst_items);
        $data = array(
            "lst_items" => $lst_items
        );
        $result_array['display'] = view('stocks.lstitems',$data)->render();
        return Response()->json($result_array);
    }


    /**
     * Display list of stock transfer
     *
     * @author Moe mantach
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_products_array = CreateDatabaseArrayByIndex($lst_products , "p_id");
        $lst_warehouse = WareHouses::whereWIsDeleted(0)->get();
        $lst_warehouse_array = CreateDatabaseArrayByIndex($lst_warehouse, "w_id");


        $result_array =array();

        $productStockMovements = StockMovements::whereSmIsDeleted(0)->get();

        $data = array(
            "lst_products_array" => $lst_products_array,
            "warehouses_array" => $lst_warehouse_array,
            "productStockMovements" => $productStockMovements
        );
        $result_array['is_error'] = 0;
        $result_array['display']  = view("stocks.displaylisttransfer",$data)->render();

        return Response()->json($result_array);
    }

    /**
     * Save stock transfer reduce quantity from one warehouse and take it from another
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return json $result_array
     */
    public function StockTransfer(Request $request)
    {
        $p_id                   = $request->input("p_id");
        $warehouse_source       = $request->input("warehouse_source");
        $warehouse_destination  = $request->input("warehouse_destination");
        $stock_quanity          = $request->input("stock_quanity");
        $result_array           = array();

        $ProductInfo = Products::find($p_id);

        $StockProductWarehouse = Stocks::whereFkProductId($p_id)->whereFkWarehouseId($warehouse_source)->get();


        $SourceWarehouse  = WareHouses::find($warehouse_source);
        $destinationWarehouse  = WareHouses::find($warehouse_destination);


        // if we dont have any stock in the warehouse we return a message about the not stock found
        if( count($StockProductWarehouse) == 0 )
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = "No Stock Found";
            return Response()->json($result_array);
        }

        $is_quanity         = $StockProductWarehouse[0]['is_quanity'];
        $is_id              = $StockProductWarehouse[0]['is_id'];

        // you dont have enought quantity to to make the transfer
        if( $stock_quanity > $is_quanity )
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = "Stock not enought for this product to make transfer please change the quantity";
            return Response()->json($result_array);
        }


        $new_quantity = $is_quanity - $stock_quanity;

        $NewStock = Stocks::find($is_id);
        $NewStock->is_quanity = $new_quantity;
        $NewStock->is_updated_at =  date("Y-m-d H:i:s");
        $NewStock->is_price_stock = $new_quantity * $ProductInfo->p_product_selling_price;
        $NewStock->save();


        // add transfer record
        $TransferStock = new StockMovements();
        $TransferStock->fk_warehouse_from = $warehouse_source;
        $TransferStock->fk_warehouse_to   = $warehouse_destination;
        $TransferStock->sm_date_movement  = date("Y-m-d H:i:s");
        $TransferStock->sm_movement_label = "Transfer Stock " . $ProductInfo->p_product_name . " From Warehouse " . $SourceWarehouse->w_warehouse_name . " To " .  $destinationWarehouse->w_warehouse_name;
        $TransferStock->sm_created_by     = session("user_id");
        $TransferStock->fk_product_id     = $p_id;
        $TransferStock->sm_stock_quantity = $stock_quanity;
        $TransferStock->sm_stock_total_price = $stock_quanity * $ProductInfo->p_product_selling_price;
        $TransferStock->save();


        // save the stock warehouse
        $destitionStock =  new Stocks();
        $destitionStock->fk_product_id      = $p_id;
        $destitionStock->fk_warehouse_id    = $warehouse_destination;
        $destitionStock->is_stock_label     = "Add Stock to " . $ProductInfo->p_product_name . " On " . date("Y-m-d H:i:s");
        $destitionStock->is_created_by      = session("user_id");
        $destitionStock->is_quanity         = $stock_quanity;
        $destitionStock->is_creation_date   = date("Y-m-d H:i:s");
        $destitionStock->is_price_stock     = $stock_quanity * $ProductInfo->p_product_selling_price;
        $destitionStock->save();


        $result_array['is_error']  = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
}
