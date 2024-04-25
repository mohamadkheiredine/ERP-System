<?php
/***********************************************************
WarehouseManager.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
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
use App\models\Users\Users;
use Illuminate\Support\Facades\Hash;
use App\models\Inventory\ProductCategories;
use App\models\Inventory\Products;
use Illuminate\Http\Request;
use App\models\Inventory\WareHouses;
use App\models\Inventory\WareHouseEmployees;
use App\models\Inventory\Stocks;
use App\models\Inventory\StockMovements;
use App\models\System\Companies;


class WarehouseManager
{
    
    const WAREHOUSE_TYPE_SIZE   = 1;
    const WAREHOUSE_TYPE_VOLUME = 2;
    const WAREHOUSE_TYPE_WEIGHT = 3;
    
    /**
     * function of save warehouse Dimensions
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveWarehouseDimensionsInfo(Request $request)
    {
        $result_array = array();
        $warehouse_id               = $request->input("warehouse_id");
        $w_warehouse_length         = $request->input("w_warehouse_length");
        $w_warehouse_length_unit    = $request->input("w_warehouse_length_unit");
        $w_warehouse_width          = $request->input("w_warehouse_width");
        $w_warehouse_width_unit     = $request->input("w_warehouse_width_unit");
        $w_warehouse_height         = $request->input("w_warehouse_height");
        $w_warehouse_height_unit    = $request->input("w_warehouse_height_unit");
        $w_warehouse_volume         = $request->input("w_warehouse_volume");
        $w_warehouse_volume_unit    = $request->input("w_warehouse_volume_unit");
        
        $WarehouseInfo = WareHouses::find($warehouse_id);
        $WarehouseInfo->w_warehouse_length          = $w_warehouse_length;
        $WarehouseInfo->w_warehouse_length_unit     = $w_warehouse_length_unit;
        $WarehouseInfo->w_warehouse_width           = $w_warehouse_width;
        $WarehouseInfo->w_warehouse_width_unit      = $w_warehouse_width_unit;
        $WarehouseInfo->w_warehouse_height          = $w_warehouse_height;
        $WarehouseInfo->w_warehouse_height_unit     = $w_warehouse_height_unit;
        $WarehouseInfo->w_warehouse_volume          = $w_warehouse_volume;
        $WarehouseInfo->w_warehouse_volume_unit     = $w_warehouse_volume_unit;
        $WarehouseInfo->save();
        
        $result_array['is_error'] = 0;        
        $result_array['error_msg'] = "Warehouse Settings Saved successfully";        
        return $result_array;
    }
    
    
    /**
     * generate array to get information of list of employees on selected warehouse
     * 
     * @author Moe Mantach
     * @access public
     * @param WareHouseEmployees $WarehouseEmployee
     * 
     * @return array $employees_array
     */
    public function GetEmployeeInfo( $WarehouseEmployee )
    {
       
        $employees_array = array();
        $lst_users = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $users_array = CreateDatabaseArrayByIndex($lst_users, 'id');
        
        
        foreach ($WarehouseEmployee as $key => $we_info ) {
            $employees_array[ $we_info->fk_employee_id ]['user_name']   = $users_array[ $we_info->fk_employee_id ]['u_username'];
            $employees_array[ $we_info->fk_employee_id ]['full_name']   = $users_array[ $we_info->fk_employee_id ]['u_fullname'];
            $employees_array[ $we_info->fk_employee_id ]['job_title']   = $users_array[ $we_info->fk_employee_id ]['u_job_title'];
            $employees_array[ $we_info->fk_employee_id ]['mobile']      = $users_array[ $we_info->fk_employee_id ]['u_mobile'];
            
        }
        
        
        return $employees_array;
    }
    
    
   
    /**
     * Move stock from source warehouse to destination warehouse
     * 
     * @author Moe Mantach
     * @access public
     * @param Array $params_array
     * $params_array['product_id'] number product id 
     * $params_array['warehouse_source'] number Warehouse Source id 
     * $params_array['warehouse_destination'] number Warehouse Destination id 
     * $params_array['stock_quanity'] number stock quantity 
     * @return array $result_array
     */
    public function MoveStockProducts( $params_array )
    {
        $result_array = array();
        
        $product_id             = $params_array['product_id'];
        $warehouse_source       = $params_array['warehouse_source'];
        $warehouse_destination  = $params_array["warehouse_destination"];
        $stock_quanity          = $params_array["stock_quanity"];
        $result_array           = array();
        
        $ProductInfo = Products::find( $product_id );
        
        $StockProductWarehouse = Stocks::whereFkProductId( $product_id )->whereFkWarehouseId($warehouse_source)->get();
        
        
        $SourceWarehouse        = WareHouses::find($warehouse_source);
        $destinationWarehouse  = WareHouses::find($warehouse_destination);
        
        
        // if we dont have any stock in the warehouse we return a message about the not stock found
        if( count($StockProductWarehouse) == 0 )
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = "No Stock Found";
            return $result_array;
        }
        
        $is_quanity         = $StockProductWarehouse[0]['is_quanity'];
        $is_id              = $StockProductWarehouse[0]['is_id'];
        
        // you dont have enought quantity to to make the transfer
        if( $stock_quanity > $is_quanity )
        {
            $result_array['is_error']  = 1;
            $result_array['error_msg'] = "Stock not enought for this product to make transfer please change the quantity";
            return $result_array;
        }
        
        
        $new_quantity = $is_quanity - $stock_quanity;
        
        $NewStock = Stocks::find($is_id);
        $NewStock->is_quanity = $new_quantity;
        $NewStock->is_updated_at =  date("Y-m-d H:i:s");
        $NewStock->is_price_stock = $new_quantity * $ProductInfo->p_product_selling_price;
        $NewStock->save();
        
        
        // add transfer record
        $TransferStock = new StockMovements();
        $TransferStock->fk_warehouse_from       = $warehouse_source;
        $TransferStock->fk_warehouse_to         = $warehouse_destination;
        $TransferStock->sm_date_movement        = date("Y-m-d H:i:s");
        $TransferStock->sm_movement_label       = "Transfer Stock " . $ProductInfo->p_product_name . " From Warehouse " . $SourceWarehouse->w_warehouse_name . " To " .  $destinationWarehouse->w_warehouse_name;
        $TransferStock->sm_created_by           = session("user_id");
        $TransferStock->fk_product_id           = $product_id;
        $TransferStock->sm_stock_quantity       = $stock_quanity;
        $TransferStock->sm_stock_total_price    = $stock_quanity * $ProductInfo->p_product_selling_price;
        $TransferStock->save();
        
        
        // save the stock warehouse
        $destitionStock =  new Stocks();
        $destitionStock->fk_product_id          = $product_id;
        $destitionStock->fk_warehouse_id        = $warehouse_destination;
        $destitionStock->is_stock_label         = "Add Stock to " . $ProductInfo->p_product_name . " On " . date("Y-m-d H:i:s");
        $destitionStock->is_created_by          = session("user_id");
        $destitionStock->is_quanity             = $stock_quanity;
        $destitionStock->is_creation_date       = date("Y-m-d H:i:s");
        $destitionStock->is_price_stock         = $stock_quanity * $ProductInfo->p_product_selling_price;
        $destitionStock->save();
        
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = "Operation Complete Successfully";
        return $result_array;
    }
    
    
    public function GenerateWarehouserCode()
    {
        $company_id     = session('company_id');
        $company_info   = Companies::find($company_id);
        $cd_company_name = $company_info->cd_company_name;
        $year           = date("Y");
        $count_warehouses = WareHouses::whereWIsDeleted(0)->count();
        
        $index = $count_warehouses + 1;
        
        
        $warehouses_code = "war" . $cd_company_name[0] . $year . "-" . sprintf('%04d', $index);
        
        return $warehouses_code;
        
    }
    
    
    public function getTotalStockAmount( $data_array )
    {
        $lst_stocks         = $data_array['lst_stocks'];
        $total_amount_array = array();
         
        foreach ( $lst_stocks as $key => $stock_info ) 
        {
            $currency_code = (String)$stock_info->Currency->cc_currency_code;
            if( !isset( $total_amount_array[ $currency_code]) )
                $total_amount_array[ $currency_code ] = $stock_info->is_price_stock;
            else 
                $total_amount_array[ $currency_code ] = $total_amount_array[ $currency_code ] + $stock_info->is_price_stock;
        } 
        return $total_amount_array;
    }
}