<?php
/***********************************************************
CustomersController.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 14, 2019
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
use Config;
use Illuminate\Support\Facades\Hash;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\Users\Users;
use App\models\Inventory\Customers;
use App\models\Inventory\WareHouses;
use App\models\System\Industry;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMServiceCategories;
use App\library\CustomersManager;
use App\models\System\Countries;
use App\models\Accounting\VatAccounts;
use App\models\Inventory\Vendors;
use App\models\Accounting\ChartAccounts;
use App\models\Accounting\DefaultAccounts;
use League\Csv\Writer;
use League\Csv\Reader;
use App\models\CRM\CRMAccounts;

class CustomersController extends Controller
{

    /**
     * Main Page to display the leads management
     *
     * @author Moe Mantach
     * @access public
     * @return Response
     */
    public function index()
    {

        $data = array();
        return Response()->view("customers.customers",$data);
    }


    /**
     * Display list of the Customers based on selected fields
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DisplayList(Request $request)
    {

        $page_number            = $request->input('page_number');
        $search_query           = $request->input('search_query');
        $nbr_rows_per_pages    = Config::get('appconfig.max_rows_per_page');
        if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
            else
                $skip = 0;

        $customers_cond     = Customers::whereIcIsDeleted(0);
        if(strlen($search_query) > 0)
        {
            $customers_cond = $customers_cond->where('ic_customer_name','LIKE','%' . $search_query. '%');
            $customers_cond = $customers_cond->orWhere('ic_customer_description','LIKE','%' . $search_query. '%');
            $customers_cond = $customers_cond->orWhere('ic_customer_code','LIKE','%' . $search_query. '%');
            $customers_cond = $customers_cond->orWhere('ic_customer_email','LIKE','%' . $search_query. '%');
        }


        $count              = $customers_cond->count();

        $total_pages = ceil( $count/$nbr_rows_per_pages );
        $total_pages = intval($total_pages);


        $lst_customers_obj    = $customers_cond->skip($skip)->take($nbr_rows_per_pages)->get();


        $response_array = array();

        $data = array(
            "lst_customers_obj" => $lst_customers_obj
        );
        $response_array['is_error'] = 0;
        $response_array['total_pages'] = $total_pages;
        $response_array['display'] = view('customers.displaylist',$data)->render();

        return Response()->json($response_array);
    }

    /**
     * Open form of add new Customer
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\Http\Response
     */
    public function AddForm()
    {
        $lst_countries          = Countries::all();
        $lst_vendors_info       = Vendors::whereIvIsDeleted(0)->get();
        $CustomerManagement     = new CustomersManager();
        $customer_code          = $CustomerManagement->GenerateCustomerCode();
        $lst_vat_tax            = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_accounts           = ChartAccounts::whereAaIsDeleted(0)->get();
        $customer_account_info  = DefaultAccounts::whereDaAccountCode("ACCOUNT_CUSTOMER")->get();
        $customer_account_id    = $customer_account_info[0]['da_account_value'];

        $data = array(
            'customer_code' => $customer_code,
            'lst_vendors_info' => $lst_vendors_info,
            'lst_countries' => $lst_countries,
            'lst_accounts' => $lst_accounts,
            'customer_account_id' => $customer_account_id,
            'lst_vat_tax' => $lst_vat_tax
        );

        return Response()->view('customers.addform',$data);
    }

    /**
     * get information of selected customer and open the edit form fields
     * @param unknown $iv_id
     * @return \Illuminate\Http\Response
     */
    public function EditForm($ic_id)
    {
        $customer_info          = Customers::find($ic_id);
        $lst_vendors_info       = Vendors::whereIvIsDeleted(0)->get();
        $lst_countries          = Countries::all();
        $lst_vat_tax            = VatAccounts::whereAvIsDeleted(0)->get();
        $lst_accounts       = ChartAccounts::whereAaIsDeleted(0)->get();
        $customer_account_info  = DefaultAccounts::whereDaAccountCode("ACCOUNT_CUSTOMER")->get();
        $customer_account_id    = $customer_account_info[0]['da_account_value'];

        $customer_code = "";
        if($customer_info->ic_customer_code == null)
        {
            $CustomerManagement = new CustomersManager();
            $customer_code      = $CustomerManagement->GenerateCustomerCode();
        }


        $data = array(
            'customer_info'     => $customer_info,
            'lst_countries'     => $lst_countries,
            'customer_code'     => $customer_code,
            'lst_vendors_info'  => $lst_vendors_info,
            'lst_vat_tax'       => $lst_vat_tax,
            'lst_accounts' => $lst_accounts,
            'customer_account_id' => $customer_account_id
        );

        return Response()->view('customers.editform',$data);

    }

    /**
     * Download CSV template
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function DownloadCsvTemplate(Request $request)
    {
        $data = array();
        $data[] = ['Code', 'Full Name','Email','Address','Phone','Mobile'];


        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertAll($data);

       return $csv->output('data.csv');

    }


    /**
     * import list of all customers from a template already used
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ImportListCustomers(Request $request)
    {

        $result_array = array();
        $file_path = $_FILES['cc_customers_list']['tmp_name'];

         // Create a new CsvReader instance
        $csvReader = Reader::createFromPath($file_path, 'r');

        // Read the CSV records
        $records = $csvReader->getRecords();

        // Process the records
        foreach ($records as $record) {
            $code           = trim($record[0]);
            $full_name      = trim($record[1]);
            $email          = trim($record[2]);
            $address        = trim($record[3]);
            $phone          = trim($record[4]);
            $mobile         = trim($record[5]);

            if($code != 'Code' && $full_name != 'Full Name')
            {
                $customer_info = new Customers();
                $customer_info->ic_customer_code = $code;
                $customer_info->ic_customer_name = $full_name;
                $customer_info->ic_customer_email = $email;
                $customer_info->ic_customer_address = $address;
                $customer_info->ic_customer_phone = $phone;
                $customer_info->ic_customer_mobile = $mobile;


                 $customer_info->ic_date_creation = date("Y-m-d");

                $account_info   = ChartAccounts::where("aa_account_ref","=","4111")->get();
                $account_info = $account_info[0];

                $count   = ChartAccounts::where("aa_account_ref","LIKE","4111%")->count();

                $new_count      = $count + 1;
                $aa_account_ref = $account_info->aa_account . (String)sprintf('%05d', $new_count);

                $AccAccounting = new ChartAccounts();
                $AccAccounting->aa_parent_account   = $account_info->aa_id;
                $AccAccounting->aa_account_ref      = $aa_account_ref;
                $AccAccounting->aa_account          = $aa_account_ref;
                $AccAccounting->aa_sub_account      = $account_info->aa_id;
                $AccAccounting->aa_account_label    = $full_name;
                $AccAccounting->fk_country_id       = 0;
                $AccAccounting->save();
                $aa_id = $AccAccounting->aa_id;
                $customer_info->ic_account_number = $aa_id;

                $customer_info->save();

            }

        }



        $result_array['is_error'] = 0;


        return Response()->json($result_array);
    }


    /**
     * Save Account Accounting and link it to the current customer
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveAccAccounting(Request $request )
    {
        $account_ref        = $request->input("account_ref");
        $parent_account     = $request->input("parent_account");
        $account_label      = $request->input("account_label");
        $country_id         = session("company_country");
        $result_array       = array();

        $acc_info           = ChartAccounts::find($parent_account);
        $count_ref_account  = ChartAccounts::whereAaAccountRef($parent_account)->count();


        // check if this account exist
        $account_info   = ChartAccounts::where("aa_account_ref","LIKE",$acc_info->aa_account_ref . "%")->get();
        $new_count      = count($account_info) + 1;
        $aa_account_ref = $acc_info->aa_account . (String)$new_count;

        $AccAccounting = new ChartAccounts();
        $AccAccounting->aa_parent_account   = $parent_account;
        $AccAccounting->aa_account_ref      = $aa_account_ref;
        $AccAccounting->aa_account          = $aa_account_ref;
        $AccAccounting->aa_sub_account      = $parent_account;
        $AccAccounting->aa_account_label    = $account_label;
        $AccAccounting->fk_country_id       = $country_id;
        $AccAccounting->save();

        $aa_id = $AccAccounting->aa_id;


        $result_array['is_error']           = 0;
        $result_array['error_msg']          = "Operation Complete Successfully";
        $result_array['accounting_label']   = $aa_account_ref . " - " . $account_label;
        $result_array['aa_id']              = $aa_id;
        return Response()->json($result_array);
    }


    /**
     * function to save data of Customers to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function SaveCustomerInfo(Request $request)
    {

        $ic_id                  = $request->input('ic_id');
        $ic_customer_name         = $request->input('ic_customer_name');
        $ic_customer_description  = $request->input('ic_customer_description');
        $ic_customer_code         = $request->input('ic_customer_code');
        $ic_customer_address      = $request->input('ic_customer_address');
        $ic_customer_country      = $request->input('ic_customer_country');
        $ic_customer_email        = $request->input('ic_customer_email');
        $ic_customer_website      = $request->input('ic_customer_website');
        $ic_customer_phone        = $request->input('ic_customer_phone');
        $ic_customer_mobile       = $request->input('ic_customer_mobile');
        $ic_image_base_src      = $request->input('ic_image_base_src');
        $ic_image_file_name     = $request->input('ic_image_file_name');
        $ic_image_extension     = $request->input('ic_image_extension');
        $ic_customer_sales_tax    = $request->input('ic_customer_sales_tax');
        $ic_customer_tax_id       = $request->input('ic_customer_tax_id');
        $ic_vendor_id               = $request->input('ic_vendor_id');
        $ic_vendor_id               = ($ic_vendor_id == "null") ? 0 : $ic_vendor_id;

        $ic_default_customer        = $request->input('ic_default_customer');
        $CustomerInfo = new Customers();
        $CustomerManager = new CustomersManager();
        if($ic_id != null)
        {
            $CustomerInfo = Customers::find( $ic_id );
        }
        else
        {
            $CustomerInfo->ic_date_creation = date("Y-m-d");

            $account_info   = ChartAccounts::where("aa_account_ref","=","41")->get();
            $account_info = $account_info[0];

            $count   = ChartAccounts::where("aa_account_ref","LIKE","41%")->count();

            $new_count      = $count + 1;
            $aa_account_ref = $account_info->aa_account . (String)$new_count;

            $AccAccounting = new ChartAccounts();
            $AccAccounting->aa_parent_account   = $account_info->aa_id;
            $AccAccounting->aa_account_ref      = $aa_account_ref;
            $AccAccounting->aa_account          = $aa_account_ref;
            $AccAccounting->aa_sub_account      = $account_info->aa_id;
            $AccAccounting->aa_account_label    = $ic_customer_name;
            $AccAccounting->fk_country_id       = 0;
            $AccAccounting->save();
            $aa_id = $AccAccounting->aa_id;
            $CustomerInfo->ic_account_number = $aa_id;

        }

        // upload file to the CRM photo
        if(count($_FILES) > 0 )
        {
            $image_data =  $CustomerManager->UploadCustomersAvatar($ic_id);
            $CustomerInfo->ic_image_base_src      = $image_data['data']['ic_image_base_src'];
            $CustomerInfo->ic_image_file_name     = $image_data['data']['ic_file_name'];
            $CustomerInfo->ic_image_extension     = $image_data['data']['ic_file_extension'];

        }


        $CustomerInfo->ic_customer_name         = $ic_customer_name;
        $CustomerInfo->ic_customer_description  = $ic_customer_description;
        $CustomerInfo->ic_customer_code         = $ic_customer_code;
        $CustomerInfo->ic_customer_address      = $ic_customer_address;
        $CustomerInfo->ic_customer_country      = $ic_customer_country;
        $CustomerInfo->ic_customer_email        = $ic_customer_email;
        $CustomerInfo->ic_customer_website      = $ic_customer_website;
        $CustomerInfo->ic_customer_phone        = $ic_customer_phone;
        $CustomerInfo->ic_customer_mobile       = $ic_customer_mobile;
        $CustomerInfo->ic_customer_sales_tax    = $ic_customer_sales_tax;
        $CustomerInfo->ic_vendor_id             = $ic_vendor_id;
        $CustomerInfo->ic_default_customer      = $ic_default_customer;
        $CustomerInfo->ic_created_by            = session('user_id');




        $CustomerInfo->save();

        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);

    }




    /**
     * Delete Customer  info and check all condition before begin deleted
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return Array
     */
    public function DeleteCustomerInfo(Request $request)
    {
        $ic_id = $request->input('ic_id');
        $result_array = array();


        $customer_info = Customers::find($ic_id);
        $customer_info->ic_is_deleted   = 1;
        $customer_info->ic_deleted_by   = session('user_id');
        $customer_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";
        return Response()->json($result_array);
    }



    public function SaveMainCustomerInfo( Request $request )
    {
        $user_id                = Session('user_id');
        $customer_id            = $request->input('customer_id');
        $ic_customer_name       = $request->input('ic_customer_name');
        $ic_customer_address    = $request->input('ic_customer_address');
        $ic_customer_email      = $request->input('ic_customer_email');
        $ic_customer_website    = $request->input('ic_customer_website');
        $ic_customer_phone      = $request->input('ic_customer_phone');
        $ic_customer_mobile     = $request->input('ic_customer_mobile');
        $user_info              = Users::find($user_id);

        $customer_manager = new CustomersManager();
        $params = array(
            'company_id' => $user_info->fk_company_id
        );
        $ic_customer_code = $customer_manager->GenerateCustomerCode($params);

        if($customer_id != 0)
        {
            $customer_info = Customers::find($customer_id);
        }
        else
        {
            $customer_info = new Customers();
        }



        $customer_info->ic_customer_code    = $ic_customer_code;
        $customer_info->ic_customer_name    = $ic_customer_name;
        $customer_info->ic_customer_address = $ic_customer_address;
        $customer_info->ic_customer_email   = $ic_customer_email;
        $customer_info->ic_customer_website = $ic_customer_website;


        if(count($_FILES) > 0 )
        {
            $image_data =  $customer_manager->UploadCustomersAvatar($customer_id);
            $customer_info->ic_image_base_src      = $image_data['data']['ic_image_base_src'];
            $customer_info->ic_image_file_name     = $image_data['data']['ic_file_name'];
            $customer_info->ic_image_extension     = $image_data['data']['ic_file_extension'];

        }

        $customer_info->save();


        $result_array['is_error'] = 0;
        $result_array['customer_id'] = $customer_info->ic_id;
        $result_array['customer_name'] = $ic_customer_name;
        return Response()->json($result_array);
    }


}
