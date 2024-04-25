<?php
/***********************************************************
SupplierContractsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 28, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\Http\Controllers\SRM;

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
use App\models\SRM\SupplierCategories;
use App\models\SRM\SupplierStatus;
use App\models\SRM\Suppliers;
use App\models\Accounting\ChartAccounts;
use App\library\SuppliersManager;
use App\models\Users\Users;
use App\models\System\Countries;
use App\models\System\Industry;
use App\models\SRM\SupplierContracts;
use App\models\System\Currency;
use App\models\SRM\SupContractProducts;
use App\models\Inventory\Products;
use App\models\SRM\SupplierProducts;



class SupplierContractsController extends Controller
{

    /**
     * 
     * @return unknown
     */
    public function index()
    {
        $lst_suppliers      = Suppliers::whereSsIsDeleted(0)->get();
        
        $data = array(
            "lst_suppliers" => $lst_suppliers,
        );
        return Response()->view('srm.suppliercontracts',$data);
    }
    
    
   /**
    * Display list of Suppliers saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {    
        $ss_supplier    = $request->input("ss_supplier"); 
        $lst_supplier_contracts  = SupplierContracts::whereScIsDeleted(0);
        
        if(strlen($ss_supplier) > 0)
            $lst_supplier_contracts = $lst_supplier_contracts->whereFkSupplierId($ss_supplier);
        
 
        
            $lst_supplier_contracts = $lst_supplier_contracts->get();
        
        $lst_currencies = Currency::all();
        $currencies_array = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        
        $data = array(
            "lst_supplier_contracts" => $lst_supplier_contracts, 
            "currencies_array" => $currencies_array
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.listcontracts",$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Function of Adding a new Category
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        
        $lst_users             = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_suppliers         = Suppliers::whereSsIsDeleted(0)->get();
        $lst_currencies        = Currency::all();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_suppliers" => $lst_suppliers,
            "lst_currencies" => $lst_currencies
        );
        return view('srm.addcontractform',$data);
    }
    
    
    /**
     * Save Supplier Categories 
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierContractInfo(Request $request)
    {
        $result_array = array();
        $sc_id                      = $request->input("sc_id");
        $fk_supplier_id             = $request->input("fk_supplier_id");
        $sc_code                    = $request->input("sc_code");
        $sc_contract_title          = $request->input("sc_contract_title");
        $sc_contract_date           = $request->input("sc_contract_date");
        $sc_contract_date           = date("Y-m-d",strtotime($sc_contract_date));
        $sc_contract_delivery_date  = $request->input("sc_contract_delivery_date");
        $sc_contract_delivery_date  = date("Y-m-d",strtotime($sc_contract_delivery_date));
        $sc_creation_date           = date("Y-m-d"); 
        $sc_contract_description    = $request->input("sc_contract_description"); 
        $fk_user_owner              = $request->input("fk_user_owner"); 
        $sc_payment_terms           = $request->input("sc_payment_terms"); 
        $sc_payment_type            = $request->input("sc_payment_type"); 
        $sc_currency_id             = $request->input("sc_currency_id"); 
 
        $contract_info = new SupplierContracts(); 
        if($sc_id   != null)
        {
            $contract_info  = SupplierContracts::find($sc_id);
        }
        
        
       
        
        $contract_info->fk_supplier_id              = $fk_supplier_id;
        $contract_info->sc_code                     = $sc_code;
        $contract_info->sc_contract_title           = $sc_contract_title;
        $contract_info->sc_contract_date            = $sc_contract_date;
        $contract_info->sc_contract_delivery_date   = $sc_contract_delivery_date;
        $contract_info->sc_creation_date            = $sc_creation_date;
        $contract_info->sc_contract_description     = $sc_contract_description;
        $contract_info->fk_user_owner               = $fk_user_owner;
        $contract_info->sc_payment_terms            = $sc_payment_terms;
        $contract_info->sc_payment_type             = $sc_payment_type;
        $contract_info->sc_currency_id              = $sc_currency_id;
        $contract_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page 
     * @param unknown $ss_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $sc_id )
    {
        $lst_users             = Users::whereUIsDeleted(0)->whereUIsActive(1)->get();
        $lst_suppliers         = Suppliers::whereSsIsDeleted(0)->get();
        $contract_info         = SupplierContracts::find($sc_id);
        $lst_currencies        = Currency::all();
        $currency_array        = CreateDatabaseArrayByIndex($lst_currencies, "cc_id");
        
        $lst_contract_products = SupplierProducts::all();
        
        $data = array(
            "lst_users" => $lst_users,
            "lst_currencies" => $lst_currencies,
            "contract_info" => $contract_info,
            "lst_srm_products" => $lst_contract_products,
            "lst_suppliers" => $lst_suppliers
        );
        return view('srm.editcontractform',$data);
    }
    
    /**
     * Add Product Contract
     * @param Request $request
     */
    public function AddRawproductcontract( Request $request )
    {
        $sc_id                  = $request->input('sc_id');
        $product_id             = $request->input('product_id');
        $sr_product_quantity    = $request->input('sr_product_quantity');
        
        $product_info = Products::find($product_id);
        
        $SupContractProduct = new SupContractProducts();
        $SupContractProduct->fk_contract_id         = $sc_id;
        $SupContractProduct->fk_product_id          = $product_id;
        $SupContractProduct->sr_product_quantity    = $sr_product_quantity;
        $SupContractProduct->sr_cost                = $product_info->p_product_selling_price * $sr_product_quantity;
        $SupContractProduct->save();
        
        
        // update total price of contract
        $lst_supplier_contracts  = SupContractProducts::whereFkContractId($sc_id)->get();
        $total_cost = 0;
        
        foreach ($lst_supplier_contracts as $key => $sc_info) {
 
            $total_cost = $total_cost + $sc_info->sr_cost;
        }
        
        
        $contract_info = SupplierContracts::find($sc_id);
        $contract_info->sc_total_cost = $total_cost;
        $contract_info->sc_total_price = $total_cost- ($total_cost * $contract_info->sc_discount);
        $contract_info->save();
        
    }
    
    /**
     * Delete Supplier from the database by change flag of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierContractInfo(Request $request)
    {
        
        $sc_id= $request->input('sc_id');
         
        $supplier_info = Suppliers::find( $ss_id);
        $supplier_info->ss_is_deleted          = 1;
        $supplier_info->ss_deleted_by          = Session('user_id');
        $supplier_info->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    public function Deletecontractproduct(Request $request)
    {
        $sc_id = $request->input("sc_id");
        $sp_id = $request->input("sp_id");
        
        SupContractProducts::whereFkContractId(0)->whereFkProductId($sp_id)->delete();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
        
    }
    
    
    /**
     * DisplayList of Products related to this Contract
     * 
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListContractProducts(Request $request)
    {
        $sc_id = $request->input("sc_id");
        $result_array = array();
        
        $supplier_products = SupplierProducts::all();
        $products_array     = CreateDatabaseArrayByIndex($supplier_products, "sp_id");
        
        $contract_products = SupContractProducts::whereFkContractId($sc_id)->get();
        
        
        $data = array(
            "contract_products" =>$contract_products,
            "products_array" =>$products_array
        );
        $result_array['display'] = view("srm.contractproducts",$data)->render();
        
        return Response()->json($result_array);
    }

}