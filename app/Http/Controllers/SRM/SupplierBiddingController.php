<?php
/***********************************************************
SupplierBiddingController.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
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
use App\models\Users\Users;
use App\models\SRM\SuppliersBidding;
use App\models\SRM\Suppliers;
use App\models\SRM\SupBiddingItems;
use App\models\System\Currency;



class SupplierBiddingController extends Controller
{

    /**
     * Page to control SRM Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {
        $data = array();
        return Response()->view('srm.bidding',$data);
    }
    
    
   /**
    * Display list of Supplier Bidding saved in the database
    * 
    * @author Moe Mantach
    * @access public
    * @param Request $request
    * @return unknown
    */
    public function DisplayList(Request $request)
    {         
        $supplier_bidding_array   = array();
        $lst_supplier_bidding     = SuppliersBidding::whereSbIsDeleted(0)->get();
        $data = array(
            "lst_supplier_bidding" => $lst_supplier_bidding,
        );
        
        $result_array = array();
        
        $result_array['display'] = view("srm.listbidding",$data)->render();
        
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
        
       $lst_users_info     = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
       $data = array(
            "lst_users_info" => $lst_users_info
        );
        return Response()->view('srm.addbidding',$data);
    }
    
    
    /**
     * Save Supplier Bidding 
     * @param Request $request
     * @return json Array $result_array
     */
    public function SaveSupplierBiddingInfo(Request $request)
    {
        $sb_id                      = $request->input('sb_id');
        $sb_owner_id                = $request->input('sb_owner_id');
        $sb_bidding_ref             = $request->input('sb_bidding_ref');
        $sb_bid_title               = $request->input('sb_bid_title');
        $sb_bid_description         = $request->input('sb_bid_description');
        $sb_start_date              = $request->input('sb_start_date');
        $sb_end_date                = $request->input('sb_end_date');
        $sb_rfq_issue_date          = $request->input('sb_rfq_issue_date');
        $sb_due_rfq_date            = $request->input('sb_due_rfq_date');
        $sb_item_types              = $request->input('sb_item_types');
        
        $result_array = array();
 

        $SupplierBidding     = new SuppliersBidding();
        if($sb_id != null)
        {
            $SupplierBidding= SuppliersBidding::find($sb_id);
        }
         
        $SupplierBidding->sb_owner_id               = $sb_owner_id;
        $SupplierBidding->sb_bidding_ref            = $sb_bidding_ref;
        $SupplierBidding->sb_bid_title              = $sb_bid_title;
        $SupplierBidding->sb_bid_description        = $sb_bid_description;
        $SupplierBidding->sb_start_date             = $sb_start_date;
        $SupplierBidding->sb_end_date               = $sb_end_date;
        $SupplierBidding->sb_rfq_issue_date         = $sb_rfq_issue_date;
        $SupplierBidding->sb_due_rfq_date           = $sb_due_rfq_date;
        $SupplierBidding->sb_item_types             = $sb_item_types;
        
        
        
        $SupplierBidding->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Supplier Bidding Information Has been saved';
        
        return Response()->json($result_array);
    }
    
    
    
    /**
     * Edit Form Page 
     * @param unknown $sc_id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function EditForm( $sb_id )
    {
        $lst_users_info     = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $bidding_info       = SuppliersBidding::find($sb_id);
        $lst_currencies     = Currency::where('cc_id','>',0)->orderBy('cc_currency_code','ASC')->get();
        
        $data = array(
            "lst_currencies" => $lst_currencies,
            "lst_users_info" => $lst_users_info,
            "bidding_info" => $bidding_info,
        );
        return view('srm.editbidding',$data);
    }
    
    
    /**
     * Save Items in the Bidding
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveBidItemsInfo( Request $request )
    {
        $sb_id              = $request->input("sb_id");
        $bi_item_title      = $request->input("bi_item_title");
        $bi_item_quanity    = $request->input("bi_item_quanity");
        $bi_currency_id     = $request->input("bi_currency_id"); 
        $result_array       = array();
        
        $BidItems = new SupBiddingItems();
        $BidItems->bi_bidding_id            = $sb_id;
        $BidItems->bi_item_title            = $bi_item_title;
        $BidItems->bi_item_selling_price    = 0;
        $BidItems->bi_currency_id           = $bi_currency_id;
        $BidItems->bi_item_quanity          = $bi_item_quanity;
        $BidItems->bi_item_type             = 1;
        $BidItems->save();
        
        $result_array['is_error']  = 0;
        $result_array['error_msg'] = 'Supplier Bidding Items Has been saved';
        
        return Response()->json($result_array);
        
    }
    
    /**
     * Display List of Items Selected For this Bidding
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DisplayListBiddingItems(Request $request)
    {
        $sb_id = $request->input("sb_id");
        $lst_bidding_products = SupBiddingItems::whereBiBiddingId($sb_id)->get();
        
        
        $data = array(
            "lst_bidding_products" => $lst_bidding_products
        );
        $result_array['is_error']  = 0;
        $result_array['display'] = view('srm.listbiddingitems',$data)->render();
        
        return Response()->json($result_array);
    }
    
    
    /**
     * Delete Supplier Bidding from the database by change flag of the row
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteSupplierBiddingInfo(Request $request)
    {
        
        $sb_id= $request->input('sb_id');
         
        $supplier_bidding = SuppliersBidding::find( $sb_id);
        $supplier_bidding->sb_is_deleted        = 1;
        $supplier_bidding->sb_deleted_by        = Session('user_id');
        $supplier_bidding->save();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }
    
    
    
    public function DeleteBiddingItemsinfo(Request $request)
    {
        
        $bi_id = $request->input('bi_id');
         
        $bid_items = SupBiddingItems::find( $bi_id); 
        $bid_items->delete();
        
        
        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        
        return Response()->json($result_array);
    }

}