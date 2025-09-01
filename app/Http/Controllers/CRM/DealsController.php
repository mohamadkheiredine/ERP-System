<?php
/***********************************************************
DealsController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/




namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App;
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
use App\models\CRM\CRMClientCategories;
use App\library\ClientsCategoriesManager;
use App\models\CRM\CRMAccounts;
use App\models\CRM\CRMDeals;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMContacts;
use App\models\Users\Users;
use App\models\CRM\CRMDealStages;
use App\models\Inventory\Products;
use App\models\Users\UserTypes;
use App\models\System\Currency;
use App\models\CRM\CRMDealProducts;
use App\library\AccountingManager;
use App\models\Billing\InvoicePayments;
use App\models\Billing\InvoiceProducts;
use App\models\CRM\CRMContractTypes;
use App\models\Accounting\ChartAccounts;
use App\models\Billing\Invoices;
use App\models\CallCenter\MaintenanceCase;
use PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\models\Billing\Receipts;
use App\models\CallCenter\InboundCall;
use App\models\CallCenter\MaintenanceTypes;
use App\models\Inventory\StockIds;
use App\models\Inventory\Stocks;
use App\models\PayRolls\PayrollsComissions;
use App\models\System\SysCurrency;
use App\models\System\Companies;

class DealsController extends Controller
{

    /**
     * Page to control Client Categories Management
     *
     * @author Moe mantach
     * @access public
     * @return unknown
     */
    public function index()
    {

        $lst_accounts = CRMAccounts::whereCaIsDeleted(0)->get();

        $data = array(
            "lst_accounts" => $lst_accounts
        );
        return Response()->view('accounts.deals',$data);
    }


    /**
     * Display list of Account Deals
     *
     * @author Moe Mantach
     * @param Request $request
     * @return View
     */
   public function DisplayList(Request $request)
    {

        $ad_account             = $request->input("ad_account");
        $general_search         = $request->input("general_search");
        $page_number            = $request->input("page_number");
        $nbr_rows_per_pages     = Config::get('appconfig.max_rows_per_page');


         if($page_number > 1)
            $skip = ( $page_number - 1 ) * $nbr_rows_per_pages ;
        else
            $skip = 0;


        $lst_deals_cond = CRMDeals::whereAdIsDeleted(0);

        if( $ad_account > 0 )
        {
            $lst_deals_cond = $lst_deals_cond->whereFkAccountId($ad_account);
        }

        $deals_count = $lst_deals_cond->count();


         $total_pages = ceil( $deals_count/$nbr_rows_per_pages );
         $total_pages = intval($total_pages);

         $lst_account_deals = $lst_deals_cond->skip($skip)->take($nbr_rows_per_pages)->get();
          $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();

        $accounts_array   = array();

        foreach ( $lst_accounts as $key => $account_info )
        {
            $accounts_array[ $account_info->ca_id ] =  $account_info->ca_account_name;
        }
        $data = array(
            "lst_account_deals" => $lst_account_deals,
            "accounts_array" => $accounts_array,
        );

        $result_array = array();

        $result_array['display'] = view("accounts.listdeals",$data)->render();
        $result_array['total_pages'] = $total_pages;

        return Response()->json($result_array);
    }


    /**
     * Get Deal info and return deal info
     *
     * @author Moe Mantach
     * @param Request $request
     */
    public function GetDealInfo(Request $request)
    {
        $result_array = array();
        $deal_code = $request->input('deal_code');

        $deal_info = CRMDeals::where('ad_deal_code','LIKE','%' .$deal_code . '%')->whereAdIsDeleted(0)->get();


        if(count($deal_info) == 0)
        {
             $result_array['is_error'] = 0;
             $result_array['error_msg'] = "No Contract with this Contract Code";
             return Response()->json($result_array);
        }

        $deal_info = $deal_info[0];

        $invoice_id = $deal_info->ad_invoice_id;

        $total_count = Receipts::whereBrIsDeleted(0)->whereFkInvoiceId($invoice_id)->count();
        $paid_count = Receipts::whereBrIsDeleted(0)->whereFkInvoiceId($invoice_id)->whereBrReceiptPaid(1)->count();

        $result_array['is_error'] = 0;
        $result_array['deal_info'] = array(
            'fk_sales_id' => $deal_info->fk_sales_id,
            'billing_situation' => $paid_count . "/" .$total_count,
            'fk_telemarketing_id' => $deal_info->fk_telemarketing_id
        );

        return Response()->json($result_array);
    }


    /**
     * Function of Adding a new Account Deals
     *
     * @author Moe Mantach
     * @access public
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function AddForm()
    {
        $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts   = CRMContacts::whereCcIsDeleted(0)->get();
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_deal_stages = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies = SysCurrency::all();
        $lst_contract_types = CRMContractTypes::whereCtIsDeleted(0)->get();

        $lst_telemarketing = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_supervisors = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SUPERVISOR)->get();
        $lst_technicians = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_general_managers = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_GENERAL_MANAGER)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();



        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_products" => $lst_products,
            "lst_leads" => $lst_leads,
            "lst_users" => $lst_users,
            "lst_general_managers" => $lst_general_managers,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_user_telemarketing" => $lst_telemarketing,
            "lst_technicians" => $lst_technicians,
            "lst_contract_types" => $lst_contract_types,
            "lst_supervisors" => $lst_supervisors,
            "lst_admins" => $lst_admins,
            "lst_user_sales" => $lst_sales,
            "lst_currencies" => $lst_currencies,
            "lst_contacts" => $lst_contacts
        );
        return Response()->view('accounts.adddeals',$data);
    }


    /**
     * Generate and Download Contract
     *
     * @author Moe mantach
     * @access public
     * @param Request $request
     */
    public function GenerateAndDownloadContract(Request $request)
    {
        $ad_id = $request->input('ad_id');
        $deal_info = CRMDeals::find($ad_id);
        $company_info = Companies::find(session('company_id'));
        $contract_document = view('templates.contractstatments')->render();
        $contract_document = str_replace("%FULLNAME%", $deal_info->Account->ca_account_name, $contract_document);
        $contract_document = str_replace("%COMPANY_NAME_TRANSLATION%", $company_info->cd_company_name, $contract_document);
        $contract_document = str_replace("%NATIONAL_ID%", $deal_info->Account->ca_national_id, $contract_document);
        $contract_document = str_replace("%PHONE_NUMBER%", $deal_info->Account->ca_account_phone, $contract_document);
        $contract_document = str_replace("%ADDRESS%", $deal_info->Account->ca_billing_address, $contract_document);
        $contract_document = str_replace("%NUMBER_PAYMENTS%", $deal_info->ad_nbr_of_payments, $contract_document);
        $contract_document = str_replace("%FIRSTINVOICE%", $deal_info->ad_first_bill_date, $contract_document);
        $contract_document = str_replace("%TOTAL_PRICE%", $deal_info->ad_remaining_payment, $contract_document);
        $contract_document = str_replace("%CURRENCY%", $deal_info->Currency->cc_currency_ar, $contract_document);
        $contract_document = str_replace("%PAPERTYPE%", $deal_info->Account->PaperType->pt_description, $contract_document);
        $contract_document = str_replace("%NATIONALITY%", ( $deal_info->Account->Nationality ? $deal_info->Account->Nationality->sn_nationality_fem_ar : ""), $contract_document);

        // get list of payments
        //LST_PAYMENTS

         $deal_info = CRMDeals::find($ad_id);
         $invoice_id =   $deal_info->ad_invoice_id;

         $lst_invoice_payments = InvoicePayments::whereFkInvoiceId($invoice_id)->get();
         $data = array(
             "lst_invoice_payments" => $lst_invoice_payments,
             "currency_name" => $deal_info->Currency->cc_currency_ar
         );
         $payments = view('deals.lsttemplatedproducts',$data)->render();


        $contract_document = str_replace("%LST_PAYMENTS%", $payments, $contract_document);


       $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($contract_document);
        return $pdf->inline();

    }


    public function generatePDF(Request $request)
    {
        // Define the CSV headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="example.csv"',
        ];

        // Use a streamed response to send the CSV content
        $callback = function() {
            // Open the output stream in write mode
            $handle = fopen('php://output', 'w');

            // Add the CSV headers (column names)
            fputcsv($handle, ['ID', 'Name', 'Email']);

            // Add some sample data (this can be replaced with dynamic data)
            fputcsv($handle, [1, 'John Doe', 'john@example.com']);
            fputcsv($handle, [2, 'Jane Doe', 'jane@example.com']);
            fputcsv($handle, [3, 'James Smith', 'james@example.com']);

            // Close the file handle
            fclose($handle);
        };

        // Return the response as a streamed download
        return new StreamedResponse($callback, 200, $headers);
    }

       public function GetProductInfo( Request $request )
    {
        $product_id         = $request->input('product_id');

        $result_array        = array();
        $product_array       = array();

        $product_info = Products::find($product_id);

        $product_array['product_id']                   = $product_id;
        $product_array['reference']                   = $product_info->p_product_ref;
        $product_array['p_barcode']                   = $product_info->p_barcode;
        $product_array['p_barcode_img']               = $product_info->p_barcode_img;
        $product_array['p_product_name']              = $product_info->p_product_name;
        $product_array['category_id']                 = $product_info->fk_pc_id;
        $product_array['category_name']               = $product_info->Category->pc_category;
        $product_array['p_product_selling_price']     = $product_info->p_product_selling_price;
        $product_array['p_product_cost_price']     = $product_info->p_product_cost_price;


        $product_array['p_product_tax_rate']          = $product_info->p_product_tax_rate;
        $product_array['currency_code']               = $product_info->Currency->cc_currency_code;
        $product_array['currency']                    = $product_info->p_product_currency;

        $image_src_url  = url('/')."/".Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        $image_src_path = public_path(). "/" .Config::get('constants.PRODUCTS_PATH').$product_info->p_product_profile_base_src.$product_info->p_product_profile_file_name.".".$product_info->p_product_profile_extention;
        if(strlen($product_info->p_product_profile_base_src) > 0 ){
            $img_src = $image_src_url;
        }else{
            $img_src = url('images/NoImageAvailable.jpg');
        }

        $product_array['product_avatar']   = $img_src;


        $result_array['is_error']       = 0;
        $result_array['product_info']   = $product_array;

        unset($product_array);
        $product_array = null;

        return Response()->json($result_array);

    }


    /**
     * Save Deal Category Info to the database
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     *
     * @return Response Json
     */
    public function SaveDealsInfo(Request $request)
    {
        $ad_id                      = $request->input('ad_id');
        $fk_account_id              = $request->input('fk_account_id');
        $fk_contact_id              = $request->input('fk_contact_id');
        $fk_lead_id                 = $request->input('fk_lead_id');
        $ad_deal_code               = $request->input('ad_deal_code');
        $ad_deal_title              = $request->input('ad_deal_title');
        $ad_deal_description        = $request->input('ad_deal_description');
        $ad_deal_owner              = $request->input('ad_deal_owner');
        $ad_deal_amount             = $request->input('ad_deal_amount');
        $ad_closing_date            = $request->input('ad_closing_date');
        $ad_closing_date            = date("Y-m-d",strtotime($ad_closing_date));
        $ad_deal_stage              = $request->input('ad_deal_stage');
        $ad_deal_type               = $request->input('ad_deal_type');
        $ad_deal_probability        = $request->input('ad_deal_probability');
        $ad_next_step               = $request->input('ad_next_step');
        $ad_expected_revenue        = $request->input('ad_expected_revenue');
        $fk_sales_id                = $request->input('fk_sales_id');
        $ad_currency_id             = $request->input('ad_currency_id');
        $ad_down_payment            = $request->input('ad_down_payment');
        $ad_nbr_of_payments         = $request->input('ad_nbr_of_payments');
        $ad_first_bill_date         = $request->input('ad_first_bill_date');
        $ad_warranty_date         = $request->input('ad_warranty_date');
        $ad_deal_date         = $request->input('ad_deal_date');
        $ad_is_approved             = $request->has('ad_is_approved') ? 1 : 0;
        $deals                      = $request->input('deals');
        $fk_telemarketing_id                        = $request->input('fk_telemarketing_id');
        $fk_collector_id                            = $request->input('fk_collector_id');
        $fk_supervisor_id                           = $request->input('fk_supervisor_id');
        $ad_sales_comm                              = $request->input('ad_sales_comm');
        $ad_telemarketing_comm                      = $request->input('ad_telemarketing_comm');
        $ad_supervisor_comm                         = $request->input('ad_supervisor_comm');
        $ad_account_code                        = $request->input('ad_account_code');
        $ad_serial_number                        = $request->input('ad_serial_number');
        $fk_technician_id                        = $request->input('fk_technician_id');
        $ad_technician_comm                        = $request->input('ad_technician_comm');
        $fk_manager_id                        = $request->input('fk_manager_id');
        $ad_manager_comm                        = $request->input('ad_manager_comm');
        $ad_contract_type                        = $request->input('ad_contract_type');
        $bill_sales_commission                        = $request->input('bill_sales_commission');
        $bill_amount                                = $request->input('bill_amount');

        $result_array = array();


        // validate that code exist


        // Validate
         $action = "add";
        $account_deal = new CRMDeals();
        if($ad_id != null)
        {
            $account_deal= CRMDeals::find($ad_id);
            $action = "edit";
        }

        $client_info = CRMAccounts::find($fk_account_id);


        $account_deal->fk_account_id         =   $fk_account_id;
        $account_deal->fk_contact_id         =   $fk_contact_id;
        $account_deal->fk_lead_id            =   $fk_lead_id;
        $account_deal->ad_deal_code          =   $ad_deal_code;
        $account_deal->ad_deal_title         =   $ad_deal_title;
        $account_deal->ad_deal_description   =   $ad_deal_description;
        $account_deal->ad_deal_owner         =   $ad_deal_owner;
        $account_deal->ad_deal_amount        =   $ad_deal_amount;
        $account_deal->ad_closing_date       =   $ad_closing_date;
        $account_deal->ad_deal_stage         =   $ad_deal_stage;
        $account_deal->ad_deal_type          =   $ad_deal_type;
        $account_deal->ad_deal_probability   =   $ad_deal_probability;
        $account_deal->ad_next_step          =   $ad_next_step;
        $account_deal->ad_expected_revenue   =   $ad_expected_revenue;
        $account_deal->fk_sales_id           =   $fk_sales_id;
        $account_deal->fk_telemarketing_id   =   $fk_telemarketing_id;
        $account_deal->fk_collector_id       =   $fk_collector_id;
        $account_deal->fk_supervisor_id      =   $fk_supervisor_id;
        $account_deal->ad_contract_type      =   $ad_contract_type;
        $account_deal->ad_sales_comm         =   $ad_sales_comm;
        $account_deal->ad_telemarketing_comm =   $ad_telemarketing_comm;
        $account_deal->ad_supervisor_comm    =   $ad_supervisor_comm;
        $account_deal->ad_currency_id        =   $ad_currency_id;
        $account_deal->ad_down_payment       =   $ad_down_payment;
        $account_deal->ad_nbr_of_payments    =   $ad_nbr_of_payments;
        $account_deal->ad_remaining_payment  =   ( $ad_deal_amount - $ad_down_payment );
        $account_deal->ad_first_bill_date    =   $ad_first_bill_date;
        $account_deal->ad_is_approved        =   $ad_is_approved;
        $account_deal->ad_account_code       =   $ad_account_code;
        $account_deal->ad_serial_number      =   $ad_serial_number;
        $account_deal->ad_deal_date      =   $ad_deal_date;
        $account_deal->ad_warranty_date      =   $ad_warranty_date;

        $account_deal->fk_technician_id   = $fk_technician_id;
        $account_deal->ad_technician_comm   = $ad_technician_comm;
        $account_deal->fk_manager_id   = $fk_manager_id;
        $account_deal->ad_manager_comm   = $ad_manager_comm;


        $account_deal->save();
        $ad_id = $account_deal->ad_id;
        $account_deal= CRMDeals::find($ad_id);

        // save product deals
        if(strlen($deals) > 0)
        {
            $product_deals = explode(",", $deals);
            $ad_id = $account_deal->ad_id;

            $delete_product = CRMDealProducts::whereDpDealId($ad_id)->delete();
            foreach ($product_deals as $key => $product_id)
            {
                $d_products_obj = new CRMDealProducts();
                $d_products_obj->dp_deal_id = $ad_id;
                $d_products_obj->dp_product_id = $product_id;
                $d_products_obj->save();
            }
        }


        // validate serial number of the product
        if($ad_is_approved > 0)
        {
            $delete_old_comissions = PayrollsComissions::wherePcDealId($ad_id)->delete();
            // create comissions records
            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $fk_sales_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = $ad_sales_comm;
            $payroll_comissions->pc_currency_id = $ad_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $account_deal->Account->ca_account_code . " - " . $account_deal->Account->ca_account_name;
            $payroll_comissions->pc_deal_id = $ad_id;
            $payroll_comissions->save();


            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $fk_technician_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = $ad_technician_comm;
            $payroll_comissions->pc_currency_id = $ad_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_deal_id = $ad_id;
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $account_deal->Account->ca_account_code . " - " . $account_deal->Account->ca_account_name;
            $payroll_comissions->save();


            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $fk_telemarketing_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = $ad_telemarketing_comm;
            $payroll_comissions->pc_currency_id = $ad_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_deal_id = $ad_id;
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $account_deal->Account->ca_account_code . " - " . $account_deal->Account->ca_account_name;
            $payroll_comissions->save();


            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $fk_supervisor_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = $ad_supervisor_comm;
            $payroll_comissions->pc_currency_id = $ad_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_deal_id = $ad_id;
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $account_deal->Account->ca_account_code . " - " . $account_deal->Account->ca_account_name;
            $payroll_comissions->save();



            $payroll_comissions = new PayrollsComissions();
            $payroll_comissions->pc_employee_id = $fk_manager_id;
            $payroll_comissions->pc_company_id = session('company_id');
            $payroll_comissions->pc_comission_value = $ad_manager_comm;
            $payroll_comissions->pc_currency_id = $ad_currency_id;
            $payroll_comissions->pc_effective_date = date('Y-m-d');
            $payroll_comissions->pc_deal_id = $ad_id;
            $payroll_comissions->pc_comission_label = "Commission on file # "  . $account_deal->Account->ca_account_code . " - " . $account_deal->Account->ca_account_name;
            $payroll_comissions->save();

        }


        // when approve create invoice and generate receipts and payment for all number of
        // payments
        {
            // delete old invoice and payments exist
            $delete_invoice = Invoices::whereBiId($account_deal->ad_invoice_id)->delete();
            $delete_payments = InvoicePayments::whereFkInvoiceId($account_deal->ad_invoice_id)->delete();


            if($fk_account_id == 0)
            {
                $result_array['is_error'] = 1;
                $result_array['error_msg'] = "Please Select Account Before Save";

                return Response()->json($result_array);
            }

            // Create Accounting Account
            $crm_account = CRMAccounts::find($fk_account_id);


            $account_info   = ChartAccounts::where("aa_account_ref","=","41")->get();
            $account_info = $account_info[0];

            $count   = ChartAccounts::where("aa_account_ref","LIKE","41%")->count();

            $new_count      = $count + 1;
            $aa_account_ref = $account_info->aa_account . (String)$new_count;

            $acc_accounting = new ChartAccounts();
            $acc_accounting->aa_parent_account   = $account_info->aa_id;
            $acc_accounting->aa_account_ref      = $aa_account_ref;
            $acc_accounting->aa_account          = $aa_account_ref;
            $acc_accounting->aa_sub_account      = $account_info->aa_id;
            $acc_accounting->aa_account_label    = $crm_account->ca_account_name;
            $acc_accounting->fk_country_id       = 0;
            $acc_accounting->save();
            $aa_id = $acc_accounting->aa_id;


            $creation_date = date("Y-m-d H:i:s");
            $company_id = Session('company_id');

            $bi_id = 0;
            // link deal to invoice
            $deal_info = CRMDeals::find($ad_id);
            $deal_info->ad_invoice_id = $bi_id;
            $deal_info->save();
            // Save invoice Payments
            $remaining_amount = $ad_deal_amount - $ad_down_payment;

            $payment_amount = $remaining_amount / $ad_nbr_of_payments;

            $percentage_amount = ( $payment_amount/$ad_deal_amount ) * 100;

            $downpayment_percentage = ($ad_down_payment/$ad_deal_amount ) * 100;

            $neareset_amount = ceil($payment_amount);
            $percentage_near_amount = ( $neareset_amount /$ad_deal_amount ) * 100;

              $total = ($payment_amount - $neareset_amount) * ($ad_nbr_of_payments - 1);
              $last_payment = $payment_amount + $total;

              // generate all bills for this deal
                if($ad_contract_type == 2)
                {
                    for ($index = 1; $index <= $ad_nbr_of_payments - 1; $index++)
                    {
                        $invoice_payment = new InvoicePayments();
                        $invoice_payment->fk_invoice_id = $bi_id;
                        $invoice_payment->ip_deal_id = $ad_id;
                        $invoice_payment->ip_client_id = $fk_account_id;
                        $invoice_payment->ip_payment_percentage = $percentage_near_amount;
                        $invoice_payment->ip_payment_amount = $action == 'add' ? $bill_amount[$index - 1]  : $neareset_amount;
                        $invoice_payment->ip_remaining_amount = $action == 'add' ? $bill_amount[$index - 1]  : $neareset_amount;
                        $invoice_payment->ip_client_code = $client_info->ca_account_code;
                        $invoice_payment->ip_client_name = $client_info->ca_account_name;
                        $invoice_payment->ip_currency_id = $ad_currency_id;
                        $invoice_payment->ip_payment_type = 2;
                        $invoice_payment->ip_billing_date = date("Y-m-d",strtotime($ad_first_bill_date . " + ". ( $index - 1 )  . " Month"));
                        $invoice_payment->ip_billing_nbr = "00" . $index;
                        $invoice_payment->ip_billing_status = 0;
                        $invoice_payment->ip_sales_comission = $bill_sales_commission[$index -1];
                        $invoice_payment->ip_payment_label = "Payment number #00" . $index . " of Deal Code #" . $ad_deal_code;
                        $invoice_payment->save();
                    }

                    $percentage_last_amount = ( $last_payment /$ad_deal_amount ) * 100;
                    $invoice_payment = new InvoicePayments();
                    $invoice_payment->fk_invoice_id = $bi_id;
                    $invoice_payment->ip_deal_id = $ad_id;
                    $invoice_payment->ip_client_id = $fk_account_id;
                    $invoice_payment->ip_payment_percentage = $percentage_last_amount;
                    $invoice_payment->ip_payment_amount = $action == 'add' ? $bill_amount[$ad_nbr_of_payments - 1]  : $last_payment;;
                    $invoice_payment->ip_remaining_amount = $action == 'add' ? $bill_amount[$ad_nbr_of_payments - 1]  : $last_payment;;
                    $invoice_payment->ip_payment_type = 2;
                    $invoice_payment->ip_billing_date = date("Y-m-d",strtotime($ad_first_bill_date . " + ".$ad_nbr_of_payments . " Month"));
                    $invoice_payment->ip_billing_nbr = "00" . $ad_nbr_of_payments;
                    $invoice_payment->ip_billing_status = 0;
                    $invoice_payment->ip_client_code = $client_info->ca_account_code;
                    $invoice_payment->ip_client_name = $client_info->ca_account_name;
                    $invoice_payment->ip_currency_id = $ad_currency_id;
                    $invoice_payment->ip_sales_comission = isset($bill_sales_commission[$ad_nbr_of_payments - 1]) ? $bill_sales_commission[$ad_nbr_of_payments - 1] : 0;
                    $invoice_payment->ip_payment_label = "Payment of Deal Code #" . $ad_deal_code;
                    $invoice_payment->save();
                }



        }

        if($action == "add")
        {
           // Create Instalation Date Pending Maintenance Call
            $count_calls = InboundCall::whereIcIsDeleted(0)->count();
            $index = $count_calls + 1;
            $call_index = "CC" . sprintf('%05d', $index);
            $today = $ad_deal_date;
            $call_info = new InboundCall();
            $call_info->ic_call_index      = $call_index;
            $call_info->ic_sales_id      = $fk_sales_id;
            $call_info->fk_customer_id      = $fk_account_id;
            $call_info->ic_telemarketing_id      = $fk_telemarketing_id;
            $call_info->ic_client_code      = $account_deal->Account->ca_account_code;
            $call_info->ic_contract_code      = $account_deal->ad_deal_code;
            $call_info->ic_serial_number      = $ad_serial_number;
            $call_info->ic_call_date            = $today;
            $call_info->ic_call_start_time      = "00:00";
            $call_info->ic_maintenance_type      = MaintenanceTypes::MAINTENANCE_INSTALLATION;
            $call_info->save();
        }




        // if deal approve create maintenance appointment next 3 month
        if($ad_is_approved > 0)
        {
                $today = $ad_deal_date;
                $three_maint_date = date('Y-m-d', strtotime('+3 month', strtotime($today)));
                $ro_date = date('Y-m-d', strtotime('+18 month', strtotime($today)));

                $count_calls = InboundCall::whereIcIsDeleted(0)->count();
                $index = $count_calls + 1;
                $call_index = "CC" . sprintf('%05d', $index);

                $call_info = new InboundCall();
                $call_info->ic_call_index      = $call_index;
                $call_info->ic_sales_id      = $fk_sales_id;
                $call_info->fk_customer_id      = $fk_account_id;
                $call_info->ic_technician_id      = $fk_technician_id;
                $call_info->ic_telemarketing_id      = $fk_telemarketing_id;
                $call_info->ic_client_code      = $account_deal->Account->ca_account_code;
                $call_info->ic_contract_code      = $account_deal->ad_deal_code;
                $call_info->ic_serial_number      = $ad_serial_number;
                $call_info->ic_call_date      = $ro_date;
                $call_info->ic_call_start_time      = "00:00";
                $call_info->ic_maintenance_type      = MaintenanceTypes::MAINTENANCE_RO;
                $call_info->save();


                $count_calls = InboundCall::whereIcIsDeleted(0)->count();
                $index = $count_calls + 1;
                $call_index = "CC" . sprintf('%05d', $index);

                $call_info = new InboundCall();
                $call_info->ic_call_index      = $call_index;
                $call_info->ic_sales_id      = $fk_sales_id;
                $call_info->fk_customer_id      = $fk_account_id;
                $call_info->ic_technician_id      = $fk_technician_id;
                $call_info->ic_telemarketing_id      = $fk_telemarketing_id;
                $call_info->ic_client_code      = $account_deal->Account->ca_account_code;
                $call_info->ic_contract_code      = $account_deal->ad_deal_code;
                $call_info->ic_serial_number      = $ad_serial_number;
                $call_info->ic_call_date      = $three_maint_date;
                $call_info->ic_call_start_time      = "00:00";
                $call_info->ic_maintenance_type      = MaintenanceTypes::MAINTENANCE_SCHEDULED_MAIN;
                $call_info->save();
        }


        $result_array['is_error']  = 0;
        $result_array['ad_id']  = $ad_id;
        $result_array['error_msg'] = 'Account Deal Information Has been saved';

        return Response()->json($result_array);
    }



    /**
     * Display Edit Account Deals Form Page
     *
     * @author Moe Mantach
     * @access public
     * @param unknown $cc_id
     */
    public function EditForm( $ad_id )
    {
        $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts   = CRMContacts::whereCcIsDeleted(0)->get();
        $deal_info      = CRMDeals::find($ad_id);
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_deal_stages    = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies     = SysCurrency::all();
        $lst_contract_types = CRMContractTypes::whereCtIsDeleted(0)->get();

        $lst_telemarketing      = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_supervisors        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SUPERVISOR)->get();
        $lst_technicians        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_general_managers   = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_GENERAL_MANAGER)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();

        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_general_managers" => $lst_general_managers,
            "lst_leads" => $lst_leads,
            "lst_products" => $lst_products,
            "deal_info" => $deal_info,
            "lst_users" => $lst_users,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_currencies" => $lst_currencies,
            "lst_user_telemarketing" => $lst_telemarketing,
            "lst_contract_types" => $lst_contract_types,
            "lst_supervisors" => $lst_supervisors,
            "lst_user_sales" => $lst_sales,
            "lst_admins" => $lst_admins,
            "lst_technicians" => $lst_technicians,
            "lst_contacts" => $lst_contacts
        );
        return Response()->view('accounts.editdeals',$data);
    }


    /**
     * View Deal Form Information Saved in the database
     *
     * @author Moe Mantach
     * @access public
     * @param $ad_id
     * @return void
     */
    public function ViewDealForm($ad_id)
    {
        $lst_accounts   = CRMAccounts::whereCaIsDeleted(0)->get();
        $lst_leads      = CRMLeads::whereClIsDeleted(0)->get();
        $lst_contacts   = CRMContacts::whereCcIsDeleted(0)->get();
        $deal_info      = CRMDeals::find($ad_id);
        $lst_users      = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
        $lst_deal_stages    = CRMDealStages::whereCsIsDeleted(0)->get();
        $lst_products       = Products::wherePProductIsDeleted(0)->get();
        $lst_currencies     = SysCurrency::all();
        $lst_contract_types = CRMContractTypes::whereCtIsDeleted(0)->get();

        $lst_telemarketing      = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TELEMARKETING)->get();
        $lst_sales              = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SALES)->get();
        $lst_supervisors        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_SUPERVISOR)->get();
        $lst_technicians        = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_TECHNICIAN)->get();
        $lst_general_managers   = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_GENERAL_MANAGER)->get();
        $lst_admins = Users::whereUIsActive(1)->whereUIsDeleted(0)->whereUUserType(UserTypes::USER_TYPE_ADMIN)->get();

        $data = array(
            "lst_accounts" => $lst_accounts,
            "lst_general_managers" => $lst_general_managers,
            "lst_leads" => $lst_leads,
            "lst_products" => $lst_products,
            "deal_info" => $deal_info,
            "lst_users" => $lst_users,
            "lst_deal_stages" => $lst_deal_stages,
            "lst_currencies" => $lst_currencies,
            "lst_user_telemarketing" => $lst_telemarketing,
            "lst_contract_types" => $lst_contract_types,
            "lst_supervisors" => $lst_supervisors,
            "lst_user_sales" => $lst_sales,
            "lst_admins" => $lst_admins,
            "lst_technicians" => $lst_technicians,
            "lst_contacts" => $lst_contacts
        );
        return Response()->view('deals.viewdeal',$data);
    }


    /**
     * Generate Deal Payments Preview to display it inside the deal added information
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function GenerateDealPaymentsPreview(Request $request)
    {
        $ad_deal_amount         = $request->input('ad_deal_amount');
        $ad_down_payment        = $request->input('ad_down_payment');
        $ad_nbr_of_payment      = $request->input('ad_nbr_of_payment');
        $ad_first_bill_date     = $request->input('ad_first_bill_date');
        $ad_id                  = $request->input('ad_id');
        $result_array = array();
        $payments_array = array();


        if($ad_id == null)
        {
            $remaining_amount = $ad_deal_amount - $ad_down_payment;

            $payment_amount = $remaining_amount / $ad_nbr_of_payment;

            $percentage_amount = ( $payment_amount/$ad_deal_amount ) * 100;

            $downpayment_percentage = ($ad_down_payment/$ad_deal_amount ) * 100;

            $neareset_amount = ceil($payment_amount);
            $percentage_near_amount = ( $neareset_amount /$ad_deal_amount ) * 100;
            $total = ($payment_amount - $neareset_amount) * ($ad_nbr_of_payment - 1);
            $last_payment = $payment_amount + $total;

            for ($index = 1; $index < $ad_nbr_of_payment; $index++)
            {
                $bill_nbr = sprintf("%07d",$index);
                $payments_array[] =array(
                    'bill_nbr' => $bill_nbr,
                    'value_date' => date("Y-m-d",strtotime($ad_first_bill_date ." + " . ( $index - 1 ) . " months")),
                    'bill_status' => "Pending",
                    'bill_amount' => $neareset_amount,
                    'bill_sales_commission' => 0
                );
            }

            $bill_nbr = sprintf("%07d",$index);
            $payments_array[] =array(
                'bill_nbr' => $bill_nbr,
                'value_date' => date("Y-m-d",strtotime($ad_first_bill_date ." + " . ($ad_nbr_of_payment - 1) . " months")),
                'bill_status' => "Pending",
                'bill_amount' => $last_payment,
                'bill_sales_commission' => 0
            );





            $result_array['is_error'] = 0;
            $data = array(
                "payments_array"   => $payments_array
            );
            $result_array['display'] = view('deals.paymentpreview',$data)->render();
            $result_array['billscoms'] = view('deals.billscoms',$data)->render();
        }
        else
        {
            $deal_info = CRMDeals::find($ad_id);
            $lst_invoice_payment = InvoicePayments::whereFkInvoiceId($deal_info->ad_invoice_id)->get();
            $payments_array = array();

            foreach ($lst_invoice_payment as $index => $payment_info) {
                $payments_array[] =array(
                    'bill_nbr' => $payment_info->ip_billing_nbr,
                    'value_date' => $payment_info->ip_billing_date,
                    'bill_status' => $payment_info->ip_billing_status == 0 ? "Pending" : "Paid",
                    'bill_amount' => $payment_info->ip_payment_amount,
                    'bill_sales_commission' => $payment_info->ip_sales_comission,
                );
            }

            $result_array['is_error'] = 0;
            $data = array(
                "payments_array"   => $payments_array
            );
            $result_array['display'] = view('deals.rpaymentpreview',$data)->render();
            $result_array['billscoms'] = view('deals.billscoms',$data)->render();

        }



        return Response()->json($result_array);
    }


    /**
     * Delete Account Deal
     *
     * @author Moe Mantach
     * @access public
     * @param Request $request
     * @return unknown
     */
    public function DeleteDealsInfo(Request $request)
    {

        $ad_id= $request->input('ad_id');

        $deal_info = CRMDeals::find( $ad_id);
        $deal_info->ad_is_deleted           = 1;
        $deal_info->ad_deleted_by           = Session('user_id');
        $deal_info->save();


        $result_array['is_error']   = 0;
        $result_array['error_msg']  = "Operation Complete Successfully";

        return Response()->json($result_array);
    }

}
