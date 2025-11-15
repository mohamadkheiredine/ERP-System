<?php
/***********************************************************
BillOfMaterialsController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Bill of Materials Controller
***********************************************************/


namespace App\Http\Controllers\MRP;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouses;
use App\models\System\Companies;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\MRP\BillOfMaterials;
use App\models\MRP\BOMItems;
use App\library\BOMManager;
use App\models\Inventory\Products;
use App\models\System\Currency;
use App\models\System\Units;
use App\models\Inventory\Stocks;



class BillOfMaterialsController extends Controller
{
    /**
     * Index Page of Bill of Materials Page
     *
     * @author Moe Mantach
     * @access public
     */
    public function index()
    {
        $lst_products = Products::wherePProductIsDeleted(0)->wherePProductType(2)->get();
        $data = array(
            "lst_products" => $lst_products
        );
        return Response()->view('mrp.billofmaterials',$data);
    }


    /**
     * Display list of bil of materials saved in the database based on selected product already added to the
     * system
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListBom(Request $request)
    {
        $bo_product = $request->input('bo_product');

        $lst_bom = BillOfMaterials::whereBmIsDeleted(0)->get();
        $result_array = array();

        $data = array(
            "lst_bom" => $lst_bom
        );
        $result_array['display'] = view('mrp.displaylistbom',$data)->render();
        return Response()->json($result_array);

    }

    /**
     * Display list of items added to the current Bill of Material
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function Displaylistitems(Request $request)
    {
        $bm_id = $request->input('bm_id');

        $lst_bom_items = BOMItems::whereFkBiId($bm_id)->whereBiIsDeleted(0)->get();

        $data = array(
            "lst_bom_items" => $lst_bom_items
        );
        $result_array['display'] = view('mrp.listbomitems',$data)->render();
        return Response()->json($result_array);
    }


    /**
     * Add New Form BOM
     */
    public function AddForm()
    {


        $default_company_id = session('default_company_id');

        $BomManager         = new BOMManager();
        $lst_products       = Products::wherePProductIsDeleted(0)->wherePProductType(2)->get();
        $lst_raw_materials  = Products::wherePProductIsDeleted(0)->wherePProductType(1)->get();
        $lst_sys_units      = Units::whereSuIsDeleted(0)->get();
        $lst_companies = Companies::whereCdIsDeleted(0)->get();

        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();
        $bom_code           = $BomManager->GenerateBomCode();
        $lst_currency       = Currency::all();
        $data = array(
            "lst_products"  => $lst_products,
            "lst_raw_materials"  => $lst_raw_materials,
            "lst_sys_units"  => $lst_sys_units,
            "lst_currency"  => $lst_currency,
            "lst_companies"  => $lst_companies,
            "lst_warehouses"  => $lst_warehouses,
            "bom_code"      => $bom_code
        );
        return Response()->view('mrp.addbom',$data);
    }


    /**
     * Delete  BOM info by changing flag of is_deleted is 1
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteBomInfo( Request $request )
    {
        $bm_id = $request->input('bm_id');

        $bom_info = BillOfMaterials::find( $bm_id );
        $bom_info->bm_is_deleted            = 1;
        $bom_info->bm_deleted_by            = Session('user_id');
        $bom_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }


    /**
     * Display Product info and return data to show it in the page
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayProductInfo(Request $request)
    {
        $product_id     = $request->input('product_id');

        $product_info   = Products::find($product_id);
        $result_array   = array();

        if($product_info == null)
        {
            $result_array['is_error']   = 1;
            $result_array['error_msg']  = "Please select Product  to display info";

            return Response()->json($result_array);
        }


        $result_array['product_label']              = $product_info->p_product_name;
        $result_array['product_code']               = $product_info->p_barcode;
        $result_array['product_unit_type']          = $product_info->p_product_unit_type;
        $result_array['product_selling_price']      = $product_info->p_product_selling_price;
        $result_array['product_currency']           = $product_info->p_product_currency;
        $result_array['is_error']                   = 0;
        $result_array['error_msg']                  = "Operation Complete Successfully";

        return Response()->json($result_array);

    }

    /**
     * Calculation of total price
     * @param Request $request
     */
    public function CalculateTotalPrice(Request $request)
    {
        $bm_quantity_type   = $request->input('bm_quantity_type');
        $bm_item_quanity    = $request->input('bm_item_quanity');
        $bm_system_units    = $request->input('bm_system_units');
        $product_id         = $request->input('product_id');
        $result_array       = array();

        $product_info = Products::find($product_id);


        $p_product_weight       = $product_info->p_product_weight;
        $p_product_weight_unit  = $product_info->p_product_weight_unit;
        $bm_item_price          = $product_info->p_product_selling_price;
        $new_price = 0;

        switch($bm_quantity_type)
        {
            case "qty":
                {
                    $lst_stocks = Stocks::whereFkProductId($product_id)->get();
                    $quantity   = 0;
                    foreach ($lst_stocks as $key => $stock_info) {
                        $quantity   = $quantity + $stock_info->is_quanity;
                    }

                    if( $quantity == 0 )
                    {
                        $result_array['is_error']   = 1;
                        $result_array['error_msg']  = "No Stock for this Product Please Add A Stock Before begin working on BOM";

                        return Response()->json($result_array);
                    }


                    $new_price      = ( $quantity * $bm_item_price );

                }
            break;
            case "weight":
                {
                    $product_unit   = Units::find($p_product_weight_unit);

                    $punit_scale    = $product_unit->su_scale;
                    $system_unit    = Units::find($bm_system_units);
                    $sunit_scale    = $system_unit->su_scale;
                    $new_quantity   = floatval($bm_item_quanity)  * pow(10, $sunit_scale) * pow(10, $punit_scale);
                    $new_price      = ( floatval($bm_item_quanity) * floatval($bm_item_price) ) /  floatval($bm_item_price);
                }
                break;
        }


        //: bm_quantity_type , bm_item_quanity : bm_item_quanity , product_id : product_id }


        $result_array['is_error']   = 0;
        $result_array['new_price']  = $new_price;

        return Response()->json($result_array);
    }

    /**
     * Save Bill of materials to the database
     *
     * @author MOe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveBomInfo(Request $request)
    {
        $bm_id              = $request->input('bm_id');
        $bm_code            = $request->input('bm_code');
        $bm_label           = $request->input('bm_label');
        $bm_product_id      = $request->input('bm_product_id');
        $bm_fixed_cost      = $request->input('bm_fixed_cost');
        $bm_variable_cost   = $request->input('bm_variable_cost');
        $bm_currency_id     = $request->input('bm_currency_id');
        $bm_bom_type        = $request->input('bm_bom_type');
        $bm_bom_notes       = $request->input('bm_bom_notes');
        $bm_final_quantity       = $request->input('bm_final_quantity');
        $bm_expected_waste_percentage       = $request->input('bm_expected_waste_percentage');
        $bm_manufacturing_efficiency       = $request->input('bm_manufacturing_efficiency');
        $bm_unit_id      = $request->input('bm_unit_id');
        $bm_company_id      = $request->input('bm_company_id');
        $bm_target_warehouse      = $request->input('bm_target_warehouse');

        $result_array =array();

        $bom_obj = new BillOfMaterials();

        if( $bm_id != null )
        {
            $bom_obj = BillOfMaterials::find($bm_id);
        }

        $bom_obj->bm_code           = $bm_code;
        $bom_obj->bm_label          = $bm_label;
        $bom_obj->bm_bom_type       = $bm_bom_type;
        $bom_obj->bm_created_by     = session("user_id");
        $bom_obj->bm_creation_date  = date("Y-m-d");
        $bom_obj->bm_product_id     = $bm_product_id;
        $bom_obj->bm_bom_notes      = $bm_bom_notes;
        $bom_obj->bm_fixed_cost     = $bm_fixed_cost;
        $bom_obj->bm_variable_cost  = $bm_variable_cost;
        $bom_obj->bm_currency_id    = $bm_currency_id;
        $bom_obj->bm_final_quantity    = $bm_final_quantity;
        $bom_obj->bm_expected_waste_percentage    = $bm_expected_waste_percentage;
        $bom_obj->bm_manufacturing_efficiency    = $bm_manufacturing_efficiency;
        $bom_obj->bm_unit_id    = $bm_unit_id;
        $bom_obj->bm_company_id    = $bm_company_id;
        $bom_obj->bm_target_warehouse    = $bm_target_warehouse;
        $bom_obj->save();


        $result_array['is_error']       = 0;
        $result_array['error_msg']      = "Operation Complete Successfully";

        return Response()->json($result_array);

    }

    /**
     * Display Page of Edit Form
     * @param unknown $bm_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm($bm_id)
    {
        $bom_info       = BillOfMaterials::find( $bm_id );
        $lst_currency   = Currency::all();
        $lst_products       = Products::wherePProductIsDeleted(0)->wherePProductType(2)->get();
        $lst_raw_materials  = Products::wherePProductIsDeleted(0)->wherePProductType(1)->get();
        $lst_sys_units      = Units::whereSuIsDeleted(0)->get();
        $company_id = session('company_id');
        $lst_companies = Companies::whereCdIsDeleted(0)->get();
        $default_company_id = session('default_company_id');
        $lst_warehouses   = WareHouses::whereWIsDeleted(0)->whereWCompanyId($default_company_id)->get();

        $data = array(
            "bom_info" => $bom_info,
            "lst_sys_units" => $lst_sys_units,
            "lst_companies" => $lst_companies,
            "lst_warehouses" => $lst_warehouses,
            "lst_currency" => $lst_currency,
            "lst_raw_materials" => $lst_raw_materials,
            "lst_products" => $lst_products
        );
        return view('mrp.editbom',$data);
    }

    /**
     * Save BOM Item in the database to link it to the
     * @param Request $request
     */
    public function SaveBomItems(Request $request)
    {
        $bm_item_product    = $request->input('bm_item_product');
        $bm_quantity_type   = $request->input('bm_quantity_type');
        $bm_item_quanity    = $request->input('bm_item_quanity');
        $bm_system_units    = $request->input('bm_system_units');
        $bm_item_price      = $request->input('bm_item_price');
        $bm_currency_id     = $request->input('bm_currency_id');
        $bm_id              = $request->input('bm_id');


        $product_info = Products::find($bm_item_product);

        $result_array = array();

        $BOmItems = new BOMItems();
        $BOmItems->fk_bi_id         = $bm_id;
        $BOmItems->bi_product_id    = $bm_item_product;
        $BOmItems->bi_item_label    = $product_info->p_product_name;
        $BOmItems->bi_item_quanity  = $bm_item_quanity;
        $BOmItems->bi_total_price   = $bm_item_price;
        $BOmItems->bi_price_currency= $bm_currency_id;
        $BOmItems->bi_unit_type     = $bm_quantity_type;
        $BOmItems->bi_unit_id       = $bm_system_units;
        $BOmItems->save();

        $result_array['is_error']               = 0;
        $result_array['bm_item_price']          = $bm_item_price;
        $result_array['error_msg']              = "Operation Complete Successfully";

        return Response()->json($result_array);
    }


    /**
     * Delete item from the BOM Definition
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DeleteBOMitem(Request $request)
    {
        $bi_id = $request->input('bi_id');
        $bom_item = BOMItems::find($bi_id);
        $bom_item->bi_is_deleted = 1;
        $bom_item->bi_deleted_by = session('user_id');
        $bom_item->save();
        $result_array = array();

        $result_array['is_error']    = 0;
        $result_array['price']       = $bom_item->bi_total_price;
        $result_array['error_msg']   = "Operation Complete Successfully";

        return Response()->json($result_array);

    }

}
