<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','IndexController@index')->name('login');
Route::get('/installation','IndexController@installation');
Route::get('/generatelicense','IndexController@GenerateLicense');


Route::get('/order/posreceipt/{os_id}','Sales\OrdersController@POSReceipt');


Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard','Dashboard\DashboardController@Dashboard');
    Route::get('/callcenters/dashboard','Dashboard\DashboardController@CallcenterDashboard');
    Route::get('/accounting/dashboard','Dashboard\DashboardController@Accounting');
    Route::get('/services/dashboard','Dashboard\DashboardController@Services');
    
    Route::get('/user/logout','Users\UsersController@LogOut');
    Route::get('/user/myprofile','Users\UsersController@MyProfile');

    Route::get('/administrator/users','Users\UsersController@UserManagement');
    
    Route::get('/administrator/config','Utilities\ConfigurationController@index');
    
    
    Route::get('/administrator/users','Users\UsersController@UserManagement');
    Route::get('/administrator/users/addform','Users\UsersController@AddForm');
    Route::get('/administrator/edituser/{user_id}','Users\UsersController@EditForm');

    Route::get('administrator/roles','Roles\RolesController@roles');
    Route::get('roles/addform','Roles\RolesController@AddRoleForm');
    Route::get('roles/editform/{role_id}','Roles\RolesController@EditRoleForm');
    

    Route::get('/inventory/products','Inventory\ProductsController@index');
    Route::get('/inventory/addnewproduct','Inventory\ProductsController@AddNewProduct');
    Route::get('/inventory/editproduct/{p_id}','Inventory\ProductsController@EditProduct');
    Route::get('/inventory/AddNewStock/{p_id}','Inventory\ProductStocksController@CreateNewStock');
    Route::get('/inventory/products/transferstocks/{p_id}','Inventory\ProductStocksController@Displaytransferstocks');
    Route::get('/products/stocks/displaybarodelabels/{ps_id}','Inventory\ProductStocksController@Displaybarodelabels');
    Route::get('/stock/addserialnumbers','Inventory\ProductStocksController@Addserialnumbers');
    
    Route::get('/inventory/productcategories','Inventory\ProductCategoriesController@index');
    Route::get('/inventory/product/addcategory','Inventory\ProductCategoriesController@AddForm');
    Route::get('/inventory/product/editcategory/{p_id}','Inventory\ProductCategoriesController@EditForm');
    Route::get('/inventory/categories/listitems/{pc_id}','Inventory\ProductCategoriesController@ListProducts');
    
    
    Route::get('/system/departments','System\DepartmentsController@index');
    Route::get('/system/departments/addform','System\DepartmentsController@AddForm');
    Route::get('/system/departments/editform/{d_id}','System\DepartmentsController@EditForm');
    Route::get('/system/departments/drawhierarchy','System\DepartmentsController@DrawHierarchy');
    
    Route::get('/system/jobtitles','System\JobTitlesController@index');
    Route::get('/system/jobtitles/addform','System\JobTitlesController@AddForm');
    Route::get('/system/jobtitles/editform/{jt_id}','System\JobTitlesController@EditForm');
        
    Route::get('/system/jobroles','System\JobRolesController@index');
    Route::get('/system/jobroles/addform','System\JobRolesController@AddForm');
    Route::get('/system/jobroles/editform/{jr_id}','System\JobRolesController@EditForm');
    
    
    Route::get('/inventory/stocks','Inventory\ProductStocksController@index');
    Route::get('/inventory/addstock','Inventory\ProductStocksController@AddForm');
    Route::get('/inventory/editstock/{is_id}','Inventory\ProductStocksController@EditForm');
    
    
    Route::get('/inventory/vendors','Inventory\VendorsController@index');
    Route::get('/inventory/vendors/addform','Inventory\VendorsController@AddForm');
    Route::get('/inventory/vendors/editform/{iv_id}','Inventory\VendorsController@EditForm');
    
    
    Route::get('/inventory/customers','Inventory\CustomersController@index');
    Route::get('/inventory/customers/addform','Inventory\CustomersController@AddForm');
    Route::get('/inventory/customers/editform/{ic_id}','Inventory\CustomersController@EditForm');
    
    Route::get('/inventory/stocktransfer','Inventory\ProductStockTransferController@index');
    Route::get('/inventory/transferstock','Inventory\ProductStockTransferController@transferstock');


    Route::get('/inventory/warehouses','WareHouses\WareHouseController@index');
    Route::get('/inventory/addnewwarehouse','WareHouses\WareHouseController@AddNewWarehouse');
    Route::get('/inventory/editwarehouse/{w_id}','WareHouses\WareHouseController@EditWarehouse');
    Route::get('/inventory/WareHouseSettings/{w_id}','WareHouses\WareHouseController@WareHouseSettings');
    Route::get('/inventory/warehouse/addzone/{w_id}','WareHouses\WareHouseController@AddWarezone');
    
    
    
    Route::get('/system/employmenttype','System\EmploymentTypeController@index');
    Route::get('/system/employmenttype/addform','System\EmploymentTypeController@AddForm');
    Route::get('/system/employmenttype/editform/{et_id}','System\EmploymentTypeController@EditForm');
    
    
    Route::get('/accounting/accountstatment','Accounting\AccountingController@Accountstatment');
    Route::get('/accounting/accountstatmentdetails','Accounting\AccountingController@AccountStatmentDetails');
    
    
    Route::get('/accounting/exchangerates','Accounting\ExchangeRatesController@index');
    Route::get('/accounting/exchangerates/addform','Accounting\ExchangeRatesController@AddForm');
    Route::get('/accounting/exchangerates/editform/{er_id}','Accounting\ExchangeRatesController@EditForm');
    
    Route::get('/timesheet/sendholidayrequest','Timesheet\HolidayRequestsController@SendHolidayRequest');
    Route::get('/timesheet/holidayrequests','Timesheet\HolidayRequestsController@HolidayRequest');
    Route::get('/timesheet/personalholidays','Timesheet\HolidaysController@PersonalHolidays');
    Route::get('/timesheet/timesheetmanagement','Timesheet\TimeSheetController@index');
    Route::get('/timesheet/generaltimesheetmanagement','Timesheet\TimeSheetController@TimesheetManagement');
    Route::get('/timesheet/onlineemployees','Timesheet\TimeSheetController@onlineemployees');
    Route::get('/timesheet/transportationemployees','Timesheet\TimeSheetController@TransportationEmployees');
    Route::get('/timesheet/holidayemployees','Timesheet\TimeSheetController@HolidayEmployees');
    Route::get('/timesheet/hourlysalaries','Timesheet\TimeSheetController@Hourlysalaries');
    
    Route::get('/timesheet/holidays','Timesheet\HolidaysController@index');
    Route::get('/timesheet/holidays/addform','Timesheet\HolidaysController@AddForm');
    Route::get('/timesheet/holidays/editform/{et_id}','Timesheet\HolidaysController@EditForm');
    
    Route::get('/timesheet/daytypes','Timesheet\DayTypesController@index');
    Route::get('/timesheet/daytypes/addform','Timesheet\DayTypesController@AddForm');
    Route::get('/timesheet/daytypes/editform/{dt_id}','Timesheet\DayTypesController@EditForm');
    

    Route::get('/system/companies','System\CompaniesController@index');
    Route::get('/system/companies/addform','System\CompaniesController@AddForm');
    Route::get('/system/companies/editform/{cd_id}','System\CompaniesController@EditForm');
    
    Route::get('/logistics/vehicules','Logistics\VehiculesController@index');
    Route::get('/logistics/vehicules/addform','Logistics\VehiculesController@AddForm');
    Route::get('/logistics/vehicules/editform/{lv_id}','Logistics\VehiculesController@EditForm');
    
    Route::get('/logistics/shipmentcompanies','Logistics\ShipCompaniesController@index');
    Route::get('/logistics/shipmentcompanies/addform','Logistics\ShipCompaniesController@AddForm');
    Route::get('/logistics/shipmentcompanies/editform/{sc_id}','Logistics\ShipCompaniesController@EditForm');
 

    Route::get('/shipments/shipmentoperations','Shipment\ShipmentController@index');
    Route::get('/shipments/shipmentoperations/addform','Shipment\ShipmentController@AddForm');
    Route::get('/shipments/shipmentoperations/editform/{so_id}','Shipment\ShipmentController@EditForm');
    
    
    Route::get('/operation/statuses','Shipment\OperationStatusController@index');
    Route::get('/operation/statuses/addform','Shipment\OperationStatusController@AddForm');
    Route::get('/operation/statuses/editform/{os_id}','Shipment\OperationStatusController@EditForm');
    
    
    
    Route::get('/payroll/employeespayroll','PayRoll\PayRollController@EmployeesPayroll');
    Route::get('/payroll/employeespayroll/addform','Shipment\OperationStatusController@AddForm');
    Route::get('/payroll/employeespayroll/editform/{up_id}','Shipment\OperationStatusController@EditForm');
    
    Route::get('/crm/leads','CRM\LeadsController@index');
    Route::get('/crm/leads/addform','CRM\LeadsController@AddForm');
    Route::get('/crm/leads/editform/{cl_id}','CRM\LeadsController@EditForm');
    Route::get('/crm/leads/editactivitryform/{ca_id}','CRM\LeadActivitiesController@EditForm');
    Route::get('/crm/leads/addcontacts/{cl_id}','CRM\ContactsController@AddContactForm');
    Route::get('/crm/leads/addactivity/{cl_id}','CRM\LeadsController@AddLeadActivityForm');
    Route::get('/crm/appointments/editform/{ca_id}','CRM\LeadApptController@EditLeadAppointmentForm');
    Route::get('/crm/leads/addappointment/{cl_id}','CRM\LeadApptController@AddNewAppointmentForm');
    Route::get('/crm/leads/leaditemconfigurations/{ci_id}','CRM\LeadItemsController@LeadItemConfigurations');
    Route::get('/request/ConvertToAccounts/leads/{cl_ids}','CRM\AccountsController@ConvertToAccounts');
    Route::get('/appointments/mycalendar','CRM\LeadApptController@MyCalendar');
    
    Route::get('/leads/status','CRM\LeadsStatusController@index');
    Route::get('/leads/status/addform','CRM\LeadsStatusController@AddForm');
    Route::get('/leads/status/editform/{ls_id}','CRM\LeadsStatusController@EditForm');
   
    Route::get('/crm/activities','CRM\LeadActivitiesController@Activities'); 
    Route::get('/crm/addactivity','CRM\LeadActivitiesController@AddForm');
    //
    Route::get('/crm/clients','CRM\AccountsController@index');
    Route::get('/crm/clients/addform','CRM\AccountsController@AddForm');
    Route::get('/crm/clients/editcontact/{ca_id}','CRM\AccountsController@EditForm');
    
    Route::get('/crm/accounts/deals','CRM\DealsController@index');
    Route::get('/crm/accounts/deals/addform','CRM\DealsController@AddForm');
    Route::get('/crm/accounts/deals/editform/{ad_id}','CRM\DealsController@EditForm');
    
    
    Route::get('/crm/contacts','CRM\ContactsController@index');
    Route::get('/crm/contacts/addform','CRM\ContactsController@AddForm');
    Route::get('/crm/contacts/editcontact/{cc_id}','CRM\ContactsController@EditForm');
    
    
    Route::get('/crm/clientcategories','CRM\ClientsCategoriesController@index');
    Route::get('/crm/clients/addcategory','CRM\ClientsCategoriesController@AddForm');
    Route::get('/crm/clients/editcategory/{cc_id}','CRM\ClientsCategoriesController@EditForm');
   
  
    
    Route::get('/crm/servicecategories','CRM\ServiceCategoriesController@index');
    Route::get('/crm/services/addcategory','CRM\ServiceCategoriesController@AddForm');
    Route::get('/crm/services/editcategory/{sc_id}','CRM\ServiceCategoriesController@EditForm');
    
    
    Route::get('/crm/services','CRM\ServicesController@index');
    Route::get('/crm/services/addform','CRM\ServicesController@AddForm');
    Route::get('/crm/services/editform/{cs_id}','CRM\ServicesController@EditForm');
    
    Route::get('/accounting/defaultaccounts','Accounting\DefaultAccountsController@index');
    
    Route::get('/accounting/openingvoucher','Accounting\AccountingController@OpeningVoucher');
    
    
    Route::get('/accounting/personalizedgroups','Accounting\PersonalizedGroupsController@index');
    Route::get('/accounting/personalizedgroups/addform','Accounting\PersonalizedGroupsController@AddForm');
    Route::get('/accounting/personalizedgroups/editform/{pj_id}','Accounting\PersonalizedGroupsController@EditForm'); 
    
    
    Route::get('/accounting/chartofaccounts','Accounting\ChartAccountsController@index');
    Route::get('/accounting/chartofaccounts/addform','Accounting\ChartAccountsController@AddForm');
    Route::get('/accounting/chartofaccounts/editform/{aa_id}','Accounting\ChartAccountsController@EditForm');
    Route::get('/accounting/accountingjournals','Accounting\AccountingJournalsController@index');
    
    
    Route::get('/banking/financialaccount','Accounting\BankingAccountsController@index');
    Route::get('/banking/accounts/addform','Accounting\BankingAccountsController@AddForm');
    Route::get('/banking/financialaccount/editform/{ba_id}','Accounting\BankingAccountsController@EditForm');
    
    Route::get('/banking/entries/list','Accounting\BankEntriesController@index');
    Route::get('/banking/entries/addform','Accounting\BankEntriesController@AddForm');
    Route::get('/banking/entries/editform/{be_id}','Accounting\BankEntriesController@EditForm');
    
    Route::get('/accounting/vataccounts','Accounting\VatAccountsController@index');
    Route::get('/accounting/vataccounts/addform','Accounting\VatAccountsController@AddForm');
    Route::get('/accounting/vataccounts/editform/{av_id}','Accounting\VatAccountsController@EditForm');
    
    
    
    Route::get('/billing/receipts','Billing\ReceiptsController@index');
    Route::get('/billing/receipts/addform','Billing\ReceiptsController@AddForm');
    Route::get('/billing/receipts/add','Billing\ReceiptsController@AddReceiptFromInvoice');
    Route::get('/billing/receipts/editform/{br_id}','Billing\ReceiptsController@EditForm');
    Route::get('/billing/receipts/editireceipt/{bi_id}/{br_id}','Billing\ReceiptsController@EditIReceiptForm');
    
    Route::get('/billing/vouchers','Billing\PaymentVouchersController@index');
    Route::get('/billing/paymentvoucher/addform','Billing\PaymentVouchersController@AddForm');
    Route::get('/billing/vouchers/editform/{pv_id}','Billing\PaymentVouchersController@EditForm');
    
    
    Route::get('/billing/internaltransfers','Billing\InternalTransfersController@index');
    Route::get('/billing/internaltransfers/addform','Billing\InternalTransfersController@AddForm');
    Route::get('/billing/internaltransfers/editform/{in_id}','Billing\InternalTransfersController@EditForm');
    
 
    Route::get('/billing/journalvouchers','Billing\JournalVouchersController@index');
    Route::get('/billing/journalvouchers/addform','Billing\JournalVouchersController@AddForm');
    Route::get('/billing/journalvouchers/editform/{pj_id}','Billing\JournalVouchersController@EditForm');
    
    
    Route::get('/accounting/accountsbalance','Accounting\AccountingController@AccountBalance');
    
    
    Route::get('/accounting/ledger','Accounting\TransactionsController@Ledger');
    Route::get('/accounting/transaction/addform','Accounting\TransactionsController@AddForm');
    Route::get('/accounting/transactions/editform/{at_id}','Accounting\TransactionsController@EditForm');
    Route::get('/accounting/movements/editform/{tm_id}','Accounting\TransactionsController@EditTransactionMovement');
    Route::get('/accounting/printaccountstatment','Accounting\AccountingController@Printaccountstatment');
    Route::get('/transaction/transactiondetails/{tran_id}/{tm_id}','Accounting\AccountingController@TransactionDetails');
    
    
    Route::get('/billing/paymenttypes','Billing\PaymentTypesController@index');
    
    Route::get('/billing/invoices','Billing\InvoicesController@index');
    Route::get('/billing/invoices/addform','Billing\InvoicesController@AddForm');
    Route::get('/billing/invoices/editform/{bi_id}','Billing\InvoicesController@EditForm');
    Route::get('/billing/ireceipts/editform/{bi_id}','Billing\InvoicesController@EditIReceiptForm');
    Route::get('/billing/invoices/downloadinvoice/{bi_id}','Billing\InvoicesController@DownloadInvoice');
    Route::get('/billing/invoices/downloadreceipt/{br_id}','Billing\ReceiptsController@DownloadReceipt');
    
    Route::get('/srm/suppliercategories','SRM\SuppliersCategoriesController@index');
    Route::get('/srm/suppliers/addcategory','SRM\SuppliersCategoriesController@AddForm');
    Route::get('/srm/suppliers/editcategory/{sc_id}','SRM\SuppliersCategoriesController@EditForm');
    
    Route::get('/srm/supplierstatuses','SRM\SupplierStatusesController@index');
    Route::get('/srm/suppliers/addstatus','SRM\SupplierStatusesController@AddForm');
    Route::get('/srm/suppliers/editstatus/{ss_id}','SRM\SupplierStatusesController@EditForm');
    

    
    Route::get('/srm/suppliers','SRM\SuppliersController@index');
    Route::get('/srm/suppliers/addform','SRM\SuppliersController@AddForm');
    Route::get('/srm/suppliers/edit/{ss_id}','SRM\SuppliersController@EditForm');
    
    
    Route::get('/srm/supplier/bidding','SRM\SupplierBiddingController@index');
    Route::get('/srm/bidding/addform','SRM\SupplierBiddingController@AddForm');
    Route::get('/srm/bidding/edit/{sb_id}','SRM\SupplierBiddingController@EditForm');
    
    Route::get('/srm/bidding/quotations','SRM\SupplierQuotationsController@index');
    Route::get('/srm/quotation/addform','SRM\SupplierQuotationsController@AddForm');
    Route::get('/srm/quotation/addserial','SRM\SupplierQuotationsController@AddSerials');
    Route::get('/srm/quotation/editform/{sq_id}','SRM\SupplierQuotationsController@EditForm');
    Route::get('/srm/quotation/view/{sq_id}','SRM\SupplierQuotationsController@ViewPurshaseQuotation');
    
    Route::get('/srm/suppliercontracts','SRM\SupplierContractsController@index');
    Route::get('/srm/suppliercontracts/addform','SRM\SupplierContractsController@AddForm');
    Route::get('/srm/suppliercontracts/editform/{sc_id}','SRM\SupplierContractsController@EditForm');
    
    
    Route::get('/srm/supplierproduct','SRM\SupplierProductsController@index');
    Route::get('/srm/supplierproduct/addform','SRM\SupplierProductsController@AddForm');
    Route::get('/srm/supplierproduct/editform/{sp_id}','SRM\SupplierProductsController@EditForm');
    
    
    Route::get('/administrator/usersteam','Users\UsersTeamController@index');
    Route::get('/administrator/team/addform','Users\UsersTeamController@AddForm');
    Route::get('/administrator/team/editform/{ut_id}','Users\UsersTeamController@EditForm');
    
    Route::get('/sales/orderstatus','Sales\OrderStatusController@index');
    Route::get('/sales/orderstatus/addform','Sales\OrderStatusController@AddForm');
    Route::get('/sales/orderstatus/editform/{os_id}','Sales\OrderStatusController@EditForm');
    
    Route::get('/sales/orders','Sales\OrdersController@index');
    Route::get('/sales/orders/addform','Sales\OrdersController@AddForm');
    Route::get('/sales/orders/editform/{os_id}','Sales\OrdersController@EditForm');
    
    
    Route::get('/production/planstatus','Production\PlansStatusController@index');
    Route::get('/production/planstatus/addform','Production\PlansStatusController@AddForm');
    Route::get('/production/planstatus/editform/{ps_id}','Production\PlansStatusController@EditForm');
    
    Route::get('/mrp/billofmaterial','MRP\BillOfMaterialsController@index');
    Route::get('/mrp/billofmaterial/addform','MRP\BillOfMaterialsController@AddForm');
    Route::get('/mrp/billofmaterial/editform/{bm_id}','MRP\BillOfMaterialsController@EditForm');
    
    Route::get('/production/planning','Production\ProductionPlanController@index');
    Route::get('/production/plan/addform','Production\ProductionPlanController@AddForm');
    Route::get('/production/plan/editform/{pp_id}','Production\ProductionPlanController@EditForm');
    
    
    Route::get('/maintenance/jobstatus','Maintenance\JobStatusController@index');
    Route::get('/maintenance/jobstatus/addform','Maintenance\JobStatusController@AddForm');
    Route::get('/maintenance/jobstatus/editform/{js_id}','Maintenance\JobStatusController@EditForm');
    
    Route::get('/maintenance/jobs','Maintenance\JobsController@index');
    Route::get('/maintenance/jobs/addform','Maintenance\JobsController@AddForm');
    Route::get('/maintenance/jobs/editform/{j_id}','Maintenance\JobsController@EditForm');
    Route::get('/maintenance/jobs/printjoborder/{j_id}','Maintenance\JobsController@PrintJobOrder');
    Route::get('/maintenance/jobs/printjobrequest/{j_id}','Maintenance\JobsController@PrintJobRequest');
    
    Route::get('/crm/reports/leads','Reports\CRMReportsController@ListLeads');
    Route::get('/crm/reports/accounts','Reports\CRMReportsController@ListAccounts');
    
    
    Route::get('/crm/contacts/editform/{cc_id}','CRM\ContactsController@EditForm');

    Route::get('/shipment/maptracker','Shipment\ShipmentController@MapTracker');

    Route::get('/request/ExportCSV/{data_type}/{params}','Utilities\ExportController@ExporttoCsv');
    Route::get('/request/print/{data_type}/{params}','Utilities\PrinterController@PrintData');
    
    
    Route::get('/pm/projects/types','PM\ProjectTypesController@index');
    Route::get('/pm/projects/types/addform','PM\ProjectTypesController@AddForm');
    Route::get('/pm/projects/types/editform/{pt_id}','PM\ProjectTypesController@EditForm');
   
    Route::get('/projects/statuses','PM\ProjectStatusesController@index');
    Route::get('/projects/statuses/addform','PM\ProjectStatusesController@AddForm');
    Route::get('/projects/statuses/editform/{ps_id}','PM\ProjectStatusesController@EditForm');
    
    
    
    
    Route::get('/phones/lines','Phones\PhoneLinesController@index');
    Route::get('/phones/lines/addform','Phones\PhoneLinesController@AddForm');
    Route::get('/phones/lines/editform/{pl_id}','Phones\PhoneLinesController@EditForm');
    
    Route::get('/phones/units','Phones\PhoneUnitsController@index');
    Route::get('/phones/units/addform','Phones\PhoneUnitsController@AddForm');
    Route::get('/phones/units/editform/{pu_id}','Phones\PhoneUnitsController@EditForm');
    
    Route::get('/phones/transactions','Phones\PhoneTransactionsController@index');
    Route::get('/phones/transactions/addform','Phones\PhoneTransactionsController@AddForm');
    Route::get('/phones/transactions/editform/{pt_id}','Phones\PhoneTransactionsController@EditForm');
    
    
    
    Route::get('/shipment/orderstatus','Shipment\SOrderStatusController@index');
    Route::get('/shipment/orderstatus/addform','Shipment\SOrderStatusController@AddForm');
    Route::get('/shipment/orderstatus/editform/{ss_id}','Shipment\SOrderStatusController@EditForm');
    
    Route::get('/shipment/packingprices','Shipment\PackingPricesController@index');
    Route::get('/shipment/packingprices/addform','Shipment\PackingPricesController@AddForm');
    Route::get('/shipment/packingprices/editform/{cp_id}','Shipment\PackingPricesController@EditForm');
    
    
    Route::get('/shipment/orders','Shipment\SOrdersController@index');
    Route::get('/shipment/orders/addform','Shipment\SOrdersController@AddForm');
    Route::get('/shipment/orders/editform/{so_id}','Shipment\SOrdersController@EditForm');
    
    
    Route::get('/costcenters','CostCenter\CostCenterController@index');
    Route::get('/costcenters/addform','CostCenter\CostCenterController@AddForm');
    Route::get('/costcenters/editform/{ac_id}','CostCenter\CostCenterController@EditForm');
    
});