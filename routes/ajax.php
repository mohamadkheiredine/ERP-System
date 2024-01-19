<?php
Route::post('/request/license/savelicenseinfo','IndexController@GenerateLicenseFile');
Route::post('/request/login','Auth\LoginController@Login');

Route::post('ajaxsaveConfiguration', 'Utilities\ConfigurationController@SaveConfiguration'); 


Route::post('/request/dashboard/getdailysales','Dashboard\DashboardController@GetDailySales');
Route::post('/request/dashboard/services/getdailysales','Dashboard\DashboardController@GetDailyServicesSales');
Route::post('/request/dashboard/getstockproducts','Dashboard\DashboardController@GetStockProducts');
Route::post('/request/dashboard/getinboundsupplier','Dashboard\DashboardController@GetInboundSupplier');
Route::post('/request/dashboard/getoutboundorders','Dashboard\DashboardController@GetOutboundOrders');
Route::post('/request/dashboard/getoutboundinvoices','Dashboard\DashboardController@GetOutboundInvoices');
Route::post('/request/dashboard/getservicesinvoicepercentage','Dashboard\DashboardController@GetServicesInvoicePercentage');
Route::post('/request/dashboard/services/servicespiechart','Dashboard\DashboardController@GetServicesPieChart');
Route::post('/request/dashboard/displaylistaccountgroup','Dashboard\DashboardController@Displaylistaccounttotals');

Route::post('/request/displayusersManagement','Users\UsersController@DisplayList');
Route::post('/request/users/saveuserinfo','Users\UsersController@SaveUsersInfo');
Route::post('/request/users/deleteuserinfo','Users\UsersController@DeleteUserInfo');


Route::post('request/roles/displaylist','Roles\RolesController@DisplayListRoles');
Route::post('request/roles/saveinfo','Roles\RolesController@SaveRoleInfo');
Route::post('request/roles/deleterole','Roles\RolesController@DeleteRoleInfo');


Route::post('/request/users/displaylistteam','Users\UsersTeamController@DisplayList');
Route::post('/request/users/saveteaminfo','Users\UsersTeamController@SaveUsersTeamInfo');
Route::post('/request/users/deleteteaminfo','Users\UsersTeamController@DeleteUserTeamInfo');


Route::post('/request/myprofile/displayprofiletabs','Users\UsersController@Displayprofiletabs');
Route::post('/request/profile/savemyprofileinfo','Users\UsersController@SaveMainProfileInfo');
Route::post('/request/profile/uploadimageprofile','Users\UsersController@UploadImageProfile');
Route::post('/request/profile/changeprofilepassword','Users\UsersController@ChangeProfilePassword');

Route::post('/request/displaywarehousemanagement','WareHouses\WareHouseController@DisplayList');
Route::post('/request/SaveWareHouse','WareHouses\WareHouseController@SaveWareHouse');
Route::post('/request/DeleteWareHouse','WareHouses\WareHouseController@DeleteWarehouseInfo');
Route::post('/request/warehouse/displaydimensions','WareHouses\WareHouseController@Displaydimensions');
Route::post('/request/warehouse/displaysettingstabs','WareHouses\WareHouseController@DisplaySettingsTabs');
Route::post('/request/warehouse/savewarehousesettings','WareHouses\WareHouseController@Savewarehousesettings');
Route::post('/request/warehouse/SaveWarehouseZone','WareHouses\WareHouseController@SaveWarehouseZone');
Route::post('/request/warehouse/deletezone','WareHouses\WareHouseController@DeleteWarehouseZone');
Route::post('/request/WareHouse/addemployee','WareHouses\WareHouseController@AddWarehouseEmployee');
Route::post('/request/warehouse/removeemployee','WareHouses\WareHouseController@RemoveWarehouseEmployee');


Route::post('/request/products/displaylistcategory','Inventory\ProductCategoriesController@DisplayList');
Route::post('/request/products/savecategoryinfo','Inventory\ProductCategoriesController@SaveProductCategoryInfo');
Route::post('/request/products/deletecategoryinfo','Inventory\ProductCategoriesController@DeleteProductCategoryInfo');

Route::post('/request/products/displaylist','Inventory\ProductsController@DisplayList');
Route::post('/request/products/saveproductinfo','Inventory\ProductsController@SaveProductInfo');
Route::post('/request/products/deleteproductinfo','Inventory\ProductsController@DeleteProductInfo');
Route::post('/request/products/displayliststocks','Inventory\ProductsController@DisplayListStocks');
Route::post('/request/products/displayliststockmovements','Inventory\ProductsController@DisplayListStockMovements');
Route::post('/request/products/addstock','Inventory\ProductStocksController@AddStock');
Route::post('/request/products/stocktransfer','Inventory\ProductStocksController@StockTransfer');
Route::post('/request/products/displaymetricsection','Inventory\ProductsController@DisplayMetricSection');
Route::post('/request/products/generatebarcode','Inventory\ProductsController@GenerateBarCode');
Route::post('/request/products/duplicateproducts','Inventory\ProductsController@Duplicateproducts');
Route::post('/request/products/getzonesdropdown','Inventory\ProductsController@GetZonesDropdown');
Route::post('/request/products/getfloorsdropdown','Inventory\ProductsController@GetFloorsDropdown');
Route::get('/request/products/downloadtemplate','Inventory\ProductsController@DownloadTemplate');

Route::post('/request/productcategories/displaylistitems','Inventory\ProductCategoriesController@DisplayListItems');

Route::post('/request/stock/getproductinfo','Inventory\ProductStocksController@GetProductinfo');


Route::post('/request/displayliststock','Inventory\ProductStocksController@DisplayList');
Route::post('/request/savestockinfo','Inventory\ProductStocksController@SaveProductStockInfo');
Route::post('/request/deletestock','Inventory\ProductStocksController@DeleteStockData');

Route::post('/request/displaylistvendors','Inventory\VendorsController@DisplayList');
Route::post('/request/savevendorinfo','Inventory\VendorsController@SaveVendorInfo');
Route::post('/request/savemainvendorinfo','Inventory\VendorsController@SaveMainVendorInfo');
Route::post('/request/deletevendorinfo','Inventory\VendorsController@DeleteVendorInfo');

Route::post('/request/exchangerate/displaylist','Accounting\ExchangeRatesController@DisplayList');
Route::post('/request/saveexchangerate','Accounting\ExchangeRatesController@SaveExchangeRateInfo');
Route::post('/request/deleteexchangerate','Accounting\ExchangeRatesController@DeleteExchangeRateInfo');
Route::post('/request/general/getexchangerate','Accounting\ExchangeRatesController@GetExchangeRate');



Route::post('/request/customers/displaylist','Inventory\CustomersController@DisplayList');
Route::post('/request/savecustomerinfo','Inventory\CustomersController@SaveCustomerInfo');
Route::post('/request/savemaincustomerinfo','Inventory\CustomersController@SaveMainCustomerInfo');
Route::post('/request/deletecustomerinfo','Inventory\CustomersController@DeleteCustomerInfo');
Route::post('/request/customers/saveaccaccounting','Inventory\CustomersController@SaveAccAccounting');

Route::post('/request/stockmovements/displaylist','Inventory\ProductStockTransferController@DisplayList');
Route::post('/request/transferstockoperation','Inventory\ProductStockTransferController@TransferStockOperation');


Route::post('/request/departments/displaylist','System\DepartmentsController@DisplayList');
Route::post('/request/departments/savedepartmentinfo','System\DepartmentsController@SaveDepartmentInfo');
Route::post('/request/departments/deletedepartmentinfo','System\DepartmentsController@DeleteDepartmentInfo');


Route::post('/request/jobtitles/displaylist','System\JobTitlesController@DisplayList');
Route::post('/request/jobtitles/savejobtitlesinfo','System\JobTitlesController@SaveJobTitleInfo');
Route::post('/request/jobtitles/deletejobtitlesinfo','System\JobTitlesController@DeleteJobTitleInfo');


Route::post('/request/jobroles/displaylist','System\JobRolesController@DisplayList');
Route::post('/request/jobroles/savejobrolesinfo','System\JobRolesController@SaveJobRoleInfo');
Route::post('/request/jobroles/deletejobrolesinfo','System\JobRolesController@DeleteJobRoleInfo');


Route::post('/request/employmenttype/displaylist','System\EmploymentTypeController@DisplayList');
Route::post('/request/employmenttype/saveemptypeinfo','System\EmploymentTypeController@SaveEmpTypeInfo');
Route::post('/request/employmenttype/deleteemptypeinfo','System\EmploymentTypeController@DeleteEmpTypeInfo');

Route::post('/request/timesheet/listholidays','Timesheet\HolidaysController@DisplayList');
Route::post('/request/timesheet/saveholidayinfo','Timesheet\HolidaysController@SaveHolidayInfo');
Route::post('/request/timesheet/deleteholidayinfo','Timesheet\HolidaysController@DeleteHolidayInfo');
Route::post('/request/timesheet/sendholidayrequest','Timesheet\HolidayRequestsController@SaveHolidayRequest');
Route::post('/request/timesheet/listholidayrequests','Timesheet\HolidayRequestsController@Displaylistholidayrequests');
Route::post('/request/timesheet/changerequeststatus','Timesheet\HolidayRequestsController@SaveChangeRequestStatus');

Route::post('/request/payroll/displaylistpayroll','PayRoll\PayRollController@DisplayListPayRoll');
Route::post('/request/payroll/generatemonthpayroll','PayRoll\PayRollController@GenerateMonthPayRoll');

Route::post('/request/timesheet/displaylisttimesheet','Timesheet\TimeSheetController@DisplayListTimesheet');
Route::post('/request/timesheet/checkincheckout','Timesheet\TimeSheetController@CheckInCheckOutAttendance');
Route::post('/request/timesheet/admintimesheet','Timesheet\TimeSheetController@DisplaySelectedTimesheet');
Route::post('/request/timesheet/saveadmintimesheet','Timesheet\TimeSheetController@SaveAdminTimesheet');
Route::post('/request/timesheet/displaylistonlineemployees','Timesheet\TimeSheetController@DisplayListOnlineemployees');
Route::post('/request/timesheet/displaylisttransportationemployees','Timesheet\TimeSheetController@DisplaylistTransportationEmployees');
Route::post('/request/timesheet/displaylistholidayemployees','Timesheet\TimeSheetController@Displaylistholidayemployees');


Route::post('/request/timesheet/displaylistdaytypes','Timesheet\DayTypesController@DisplayList');
Route::post('/request/timesheet/savedaytypeinfo','Timesheet\DayTypesController@SaveDayTypeInfo');
Route::post('/request/timesheet/deletedaytype','Timesheet\DayTypesController@DeleteDayTypeInfo');

Route::post('/request/companies/listcompanies','System\CompaniesController@DisplayList');
Route::post('/request/companies/savecompanyinfo','System\CompaniesController@SaveCompanyInfo');
Route::post('/request/companies/deletecompanyinfo','System\CompaniesController@DeleteCompanyInfo');

Route::post('/request/logistics/listvehicules','Logistics\VehiculesController@DisplayList');
Route::post('/request/logistics/savevehiculeinfo','Logistics\VehiculesController@SaveVehiculesInfo');
Route::post('/request/logistics/deletevehiculeinfo','Logistics\VehiculesController@DeleteVehiculeInfo');

Route::post('/request/shipment/operations/displaylist','Shipment\ShipmentController@DisplayList');
Route::post('/request/shipment/operations/displayformtype','Shipment\ShipmentController@DisplayFormType');
Route::post('/request/shipment/operations/displayproducts','Shipment\ShipmentController@DisplayOperationProducts');
Route::post('/request/shipment/operations/saveinfo','Shipment\ShipmentController@SaveShipmentOperationInfo');
Route::post('/request/shipment/operations/deleteinfo','Shipment\ShipmentController@DeleteShipmentInfo');


Route::post('/request/operationstatuses/displaylist','Shipment\OperationStatusController@DisplayList');
Route::post('/request/operationstatuses/savestatusinfo','Shipment\OperationStatusController@SaveStatusInfo');
Route::post('/request/operationstatuses/deletestatusinfo','Shipment\OperationStatusController@DeleteStatusInfo');

Route::post('/request/leads/displaylist','CRM\LeadsController@DisplayList');
Route::post('/request/leads/saveleadinfo','CRM\LeadsController@SaveLeadInfo');
Route::post('/request/leads/deleteleadinfo','CRM\LeadsController@DeleteLeadInfo');
Route::post('/request/leads/displaynotestab','CRM\LeadNotesController@LeadNotesManager');
Route::post('/request/leads/displaylistnotes','CRM\LeadNotesController@DisplayListNotes');
Route::post('/request/leads/displayleadcontacts','CRM\ContactsController@LeadContactsManager');
Route::post('/request/leads/saveleadnote','CRM\LeadNotesController@SaveLeadNoteInfo');
Route::post('/request/leads/displayfilestab','CRM\LeadFilesController@LeadFilesManager');
Route::post('/request/leads/uploadleadfile','CRM\LeadFilesController@UploadLeadFile');
Route::post('/request/leads/deleteleadfile','CRM\LeadFilesController@DeleteLeadFile');
Route::post('/request/leads/savecontactsinfo','CRM\ContactsController@SaveContactInfo');
Route::post('/request/leads/displayactivitiestab','CRM\LeadActivitiesController@DisplayLeadActivityTab');
Route::post('/request/leads/displayleadlogstab','CRM\CRMLogsController@DisplayLeadTabsTab');
Route::post('/request/leads/saveleadactivity','CRM\LeadActivitiesController@SaveLeadActivityInfo');
Route::post('/request/leads/deleteleadactivity','CRM\LeadActivitiesController@DeleteLeadActivity');
Route::post('/request/leads/displayappointmentstab','CRM\LeadApptController@DisplayLeadApptTab');
Route::post('/request/leads/deleteleadappointment','CRM\LeadApptController@DeleteAppointmentInfo');
Route::post('/request/leads/saveleadappointment','CRM\LeadApptController@SaveAppointmentInfo');
Route::post('/request/leads/insertleaditems','CRM\LeadItemsController@InsertLeadItems');
Route::post('/request/leads/saveleaditeminfo','CRM\LeadItemsController@SaveLeadItemInfo');
Route::post('/request/leads/displayleadservicestab','CRM\LeadItemsController@DisplayLeadServicestab');
Route::post('/request/leads/deleteleadserviceinfo','CRM\LeadItemsController@DeleteLeadServiceinfo');

Route::post('/request/leadstatus/displaylist','CRM\LeadsStatusController@DisplayList');
Route::post('/request/leadstatus/savestatusinfo','CRM\LeadsStatusController@SaveStatusInfo');
Route::post('/request/leadstatus/deletestatusinfo','CRM\LeadsStatusController@DeleteStatusInfo');

Route::post('/request/activities/displaylist','CRM\LeadActivitiesController@DisplayList');


Route::post('/request/leads/changestatus','CRM\LeadsController@ChangeLeadStatus');
Route::post('/request/leads/assignto','CRM\LeadsController@LeadAssignTo');

Route::post('/request/contacts/deletecontactinfo','CRM\ContactsController@DeleteContactInfo');
Route::post('/request/contacts/displaylist','CRM\ContactsController@DisplayListContacts');


Route::post('/request/accounting/displaychartaccounts','Accounting\ChartAccountsController@DisplayList');
Route::post('/request/accounting/savenewaccountrecord','Accounting\ChartAccountsController@SaveAccountInfo');
Route::post('/request/accounting/deleteaccountrecord','Accounting\ChartAccountsController@DeleteAccountInfo');


Route::post('/request/accounting/displaylistaccountstatment','Accounting\AccountingController@Displaylistaccountstatment');
Route::post('/request/accounting/displaylistaccountstotals','Accounting\AccountingController@Displaylistaccounttotals');
Route::post('/request/accounting/showtransactionaccountdetails','Accounting\AccountingController@ShowTransactionAccountDetails');


Route::post('/request/clients/displaylistcategory','CRM\ClientsCategoriesController@DisplayList');
Route::post('/request/clients/savecategoryinfo','CRM\ClientsCategoriesController@SaveClientCategoryInfo');
Route::post('/request/clients/deletecategoryinfo','CRM\ClientsCategoriesController@DeleteClientCategoryInfo');


Route::post('/request/deals/displaylistdeals','CRM\DealsController@DisplayList');
Route::post('/request/deals/savedealinfo','CRM\DealsController@SaveDealsInfo');
Route::post('/request/deals/deletedealinfo','CRM\DealsController@DeleteDealsInfo');


Route::post('/request/clients/displaylist','CRM\AccountsController@DisplayList');
Route::post('/request/clients/saveaccountinfo','CRM\AccountsController@SaveAccountInfo');
Route::post('/request/clients/deleteaccountinfo','CRM\AccountsController@DeleteAccountInfo');
Route::post('/request/leads/converttoaccounts','CRM\AccountsController@ConvertAccounts');

Route::post('/request/services/displaylistcategory','CRM\ServiceCategoriesController@DisplayList');
Route::post('/request/services/savecategoryinfo','CRM\ServiceCategoriesController@SaveServiceCategoryInfo');
Route::post('/request/services/deletecategoryinfo','CRM\ServiceCategoriesController@DeleteServiceCategoryInfo');


Route::post('/request/services/displaylist','CRM\ServicesController@DisplayList');
Route::post('/request/services/listpaymenttypes','CRM\ServicesController@ListPaymentTypes');
Route::post('/request/services/saveserviceinfo','CRM\ServicesController@SaveServiceInfo');
Route::post('/request/services/deleteserviceinfo','CRM\ServicesController@DeleteServiceInfo');
Route::post('/request/services/savepaymenttype','CRM\ServicesController@SavePaymentType');
Route::post('/request/services/getpaymenttypeinfo','CRM\ServicesController@GetPaymentTypeInfo');
Route::post('/request/services/deletepaymenttype','CRM\ServicesController@DeletePaymentType');


Route::post('/request/general/getdropdown/{key}','Utilities\HtmlController@GetDropdown');
Route::post('/request/cache/generatecache/{key}','Utilities\CacheController@GenerateCache');
Route::post('/request/upload/{key}','Utilities\UploaderController@UploadFile');

Route::post('/request/accounting/importchartaccount','Accounting\ChartAccountsController@ImportChartAccount');
Route::post('/request/accounting/displaylistjournals','Accounting\AccountingJournalsController@DisplayList');
Route::post('/request/accounting/changejournalstatus','Accounting\AccountingJournalsController@Changejournalstatus');

Route::post('/request/banking/displaylistaccounts','Accounting\BankingAccountsController@DisplayList');
Route::post('/request/banking/saveaccountinfo','Accounting\BankingAccountsController@SaveAccountInfo');
Route::post('/request/banking/deleteaccountinfo','Accounting\BankingAccountsController@DeleteAccountInfo');


Route::post('/request/banking/displaylistentries','Accounting\BankEntriesController@displaylistEntries');
Route::post('/request/banking/saveentryinfo','Accounting\BankEntriesController@SaveEntryInfo');
Route::post('/request/banking/deleteentryinfo','Accounting\BankEntriesController@DeleteEntryInfo');
Route::post('/request/banking/getbankcurrency','Accounting\BankingAccountsController@GetBankCurrency');


Route::post('/request/accounting/displaylistvataccounts','Accounting\VatAccountsController@DisplayList');
Route::post('/request/accounting/savevataccountinfo','Accounting\VatAccountsController@SaveVatAccountsInfo');
Route::post('/request/accounting/deletevataccountinfo','Accounting\VatAccountsController@DeleteVatAccountInfo');

Route::post('/request/accounting/displaydefaultaccounts','Accounting\DefaultAccountsController@DisplayList');
Route::post('/request/accounting/savedefaultaccounts','Accounting\DefaultAccountsController@SaveDefaultAccounts');

Route::post('/request/accounting/displaylistpersonalizedgroups','Accounting\PersonalizedGroupsController@DisplayList');
Route::post('/request/accounting/savepersonalizedgroupsinfo','Accounting\PersonalizedGroupsController@SavePersonalizedGroupInfo');
Route::post('/request/accounting/deletepersonalizedgroupsinfo','Accounting\PersonalizedGroupsController@DeletePersonalizedGroupInfo');


Route::post('/request/accounting/deletetransactioninfo','Accounting\TransactionsController@DeleteTransactionInfo');
Route::post('/request/accounting/deletemovementinfo','Accounting\TransactionsController@DeleteMovementInfo');
Route::post('/request/accounting/savetransactioninfo','Accounting\TransactionsController@SaveTransactionInfo');
Route::post('/request/accounting/displaylistmovements','Accounting\TransactionsController@Displaylistmovements');
Route::post('/request/accounting/displaylisttransactionmovements','Accounting\TransactionsController@DisplayListTransactionmovements');
Route::post('/request/accounting/displaylistemptytransactions','Accounting\TransactionsController@DisplayListEmptyTransactions');
Route::post('/request/accounting/addnewmovementrows','Accounting\TransactionsController@AddNewMovementRows');
Route::post('/request/accounting/displayeditmovementrow','Accounting\TransactionsController@DisplayEditMovementRow');
Route::post('/request/accounting/savemovementrowinfo','Accounting\TransactionsController@SaveMovementRowInfo');
Route::post('/request/accounting/displaylistopeningvouchers','Accounting\AccountingController@DisplayListOpeningVouchers');
Route::post('/request/accounting/addnewopeningrows','Accounting\AccountingController@AddNewTransMovementRow');
Route::post('/request/accounting/savetransactionovinfo','Accounting\AccountingController@SaveTransactionovInfo');
Route::post('/request/accounting/displayeditovmovementrow','Accounting\AccountingController@DisplayEditOVMovementrow');
Route::post('/request/accounting/savemovementrowovinfo','Accounting\AccountingController@SaveMovementRowovInfo');
Route::post('/request/accounting/generatelatestaccount','Accounting\ChartAccountsController@Generatelatestaccount');
Route::post('/request/accounting/generateyearconfiguration','Accounting\AccountingController@GenerateYearConfiguration');

Route::post('/request/accounting/displaylistaccountbalance','Accounting\AccountingController@DisplayListAccountBalance');

Route::post('/request/srm/displaylistcategory','SRM\SuppliersCategoriesController@DisplayList');
Route::post('/request/srm/savecategoryinfo','SRM\SuppliersCategoriesController@SaveSupplierCategoryInfo');
Route::post('/request/srm/deletecategoryinfo','SRM\SuppliersCategoriesController@DeleteSupplierCategoryInfo');

Route::post('/request/srm/displayliststatus','SRM\SupplierStatusesController@DisplayList');
Route::post('/request/srm/savestatusinfo','SRM\SupplierStatusesController@SaveSupplierStatusInfo');
Route::post('/request/srm/deletestatusinfo','SRM\SupplierStatusesController@DeleteSupplierStatusInfo');
Route::post('/request/srm/findproductbybarcode','SRM\SupplierQuotationsController@FindProductByBarcode');


Route::post('/request/billing/displaylistinvoices','Billing\InvoicesController@DisplayListInvoices');
Route::post('/request/billing/saveinvoiceinfo','Billing\InvoicesController@SaveInvoiceInfo');
Route::post('/request/billing/deleteinvoiceinfo','Billing\InvoicesController@DeleteInvoiceInfo');
Route::post('/request/billing/displaylistproductsinvoice','Billing\InvoicesController@DisplayListProductsInvoice');
Route::post('/request/billing/displaylistpaymentsinvoice','Billing\InvoicesController@DisplayListPaymentsInvoice');
Route::post('/request/billing/insertinvoiceitems','Billing\InvoicesController@InsertInvoiceItems');
Route::post('/request/billing/convertinvoicetoofficial','Billing\InvoicesController@ConvertInvoiceToOfficial');
Route::post('/request/billing/revertinvoicedraft','Billing\InvoicesController@RevertInvoiceDraft');
Route::post('/request/billing/generatereceipts','Billing\ReceiptsController@GenerateReceipts');
Route::post('/request/billing/payreceipt','Billing\ReceiptsController@PayReceipt');
Route::post('/request/billing/insertinvoiceservice','Billing\InvoicesController@InsertInvoiceServices');
Route::post('/request/billing/deleteinvoiceitems','Billing\InvoicesController@DeleteInvoiceItems');
Route::post('/request/billing/getinvoiceitem','Billing\InvoicesController@GetInvoiceItemInfo');
Route::post('/request/billing/savesplitpayments','Billing\InvoicesController@SaveSplitPayments');

Route::post('/request/billing/generatecode','Utilities\ConfigurationController@GenerateVoucherCode');


Route::post('/request/billing/displaylistinttransfers','Billing\InternalTransfersController@DisplayList');
Route::post('/request/billing/saveinttransferinfo','Billing\InternalTransfersController@SaveINInfo');
Route::post('/request/billing/deleteinttransferinfo','Billing\InternalTransfersController@DeleteINInfo');


Route::post('/request/journalvouchers/displaylist','Billing\JournalVouchersController@DisplayList');
Route::post('/request/journalvouchers/savejvoucherinfo','Billing\JournalVouchersController@SaveJournalVoucherInfo');
Route::post('/request/journalvouchers/deletejvoucher','Billing\JournalVouchersController@DeleteJournalVoucherInfo');


Route::post('/request/vouchers/displaylistextensions','Billing\PaymentVouchersController@DisplayListExtensions');
Route::post('/request/vouchers/displayextensionrow','Billing\PaymentVouchersController@DisplayNewExtensionRow');
Route::post('/request/vouchers/viewextensionrow','Billing\PaymentVouchersController@ViewExtensionRow');
Route::post('/request/vouchers/saveextensionrow','Billing\PaymentVouchersController@SaveExtensionRow');


Route::post('/request/receipts/displaylist','Billing\ReceiptsController@DisplayList');
Route::post('/request/billing/savereceiptinfo','Billing\ReceiptsController@SaveReceiptInfo');
Route::post('/request/billing/deletereceiptinfo','Billing\ReceiptsController@DeleteReceiptInfo');

Route::post('/request/billing/displaylistpayments','Billing\PaymentVouchersController@DisplayList');
Route::post('/request/billing/savepayvoucherinfo','Billing\PaymentVouchersController@SavePaymentVoucherInfo');
Route::post('/request/billing/deletepayvoucherinfo','Billing\PaymentVouchersController@DeleteVoucherInfo');


Route::post('/request/creditnotes/displaylist','Billing\CreditNotesController@DisplayList');
Route::post('/request/creditnotes/savecvinfo','Billing\CreditNotesController@SaveCNInfo');
Route::post('/request/creditnotes/deletecninfo','Billing\CreditNotesController@DeleteCNInfo');


Route::post('/request/debitnotes/displaylist','Billing\DebitNotesController@DisplayList');
Route::post('/request/debitnotes/savedninfo','Billing\DebitNotesController@SaveDebitNote');
Route::post('/request/debitnotes/deletedninfo','Billing\DebitNotesController@DeleteDebitNoteInfo');


Route::post('/request/billing/savepaymenttypes','Billing\PaymentTypesController@SavePaymentTypes');

Route::post('/request/billing/displaylistreceiptsinvoice','Billing\ReceiptsController@DisplayListReceiptsInvoice');

Route::post('/request/srm/displaylistsuppliers','SRM\SuppliersController@DisplayList');
Route::post('/request/srm/savesupplierinfo','SRM\SuppliersController@SaveSupplierInfo');
Route::post('/request/srm/deletesupplierinfo','SRM\SuppliersController@DeleteSupplierInfo');
Route::post('/request/suppliers/saveaccaccounting','SRM\SuppliersController@SaveAccAccounting');


Route::post('/request/accounting/saveaccaccounting','Accounting\VendorsController@SaveAccAccounting');

Route::post('/request/srm/displaylistsupcontracts','SRM\SupplierContractsController@DisplayList');
Route::post('/request/srm/savecontractinfo','SRM\SupplierContractsController@SaveSupplierContractInfo');
Route::post('/request/srm/deletecontractinfo','SRM\SupplierContractsController@DeleteSupplierContractInfo');
Route::post('/request/srm/displaylistcontractproducts','SRM\SupplierContractsController@DisplayListContractProducts');
Route::post('/request/srm/deletecontractproducts','SRM\SupplierContractsController@Deletecontractproduct');
Route::post('/request/srm/addproductcontract','SRM\SupplierContractsController@AddRawproductcontract');
Route::post('/request/srm/displaylistbiddingitems','SRM\SupplierBiddingController@DisplayListBiddingItems');
Route::post('/request/srm/savebiditemsinfo','SRM\SupplierBiddingController@SaveBidItemsInfo');
Route::post('/request/srm/deletebiddingiteminfo','SRM\SupplierBiddingController@DeleteBiddingItemsinfo');


Route::post('/request/srm/displaylistbidding','SRM\SupplierBiddingController@DisplayList');
Route::post('/request/srm/savebiddinginfo','SRM\SupplierBiddingController@SaveSupplierBiddingInfo');
Route::post('/request/srm/deletebiddinginfo','SRM\SupplierBiddingController@DeleteSupplierBiddingInfo');

Route::post('/request/srm/displaylistquotations','SRM\SupplierQuotationsController@DisplayList');
Route::post('/request/srm/savequotationinfo','SRM\SupplierQuotationsController@SaveSupplierQuotationInfo');
Route::post('/request/srm/deletequotationinfo','SRM\SupplierQuotationsController@DeleteSupplierQuotationInfo');
Route::post('/request/srm/approvequotation','SRM\SupplierQuotationsController@Approvequotation');


Route::post('/request/displaylistorderstatus','Sales\OrderStatusController@DisplayList');
Route::post('/request/saveorderstatusinfo','Sales\OrderStatusController@SaveOrderStatusInfo');
Route::post('/request/deleteorderstatus','Sales\OrderStatusController@DeleteOrderStatusInfo');

Route::post('/request/orders/displaylist','Sales\OrdersController@DisplayList');
Route::post('/request/orders/displaylistproducts','Sales\OrdersController@DisplayListProducts');
Route::post('/request/orders/saveproduct','Sales\OrdersController@AddOrderProduct');
Route::post('/request/orders/saveinfo','Sales\OrdersController@SaveOrdersInfo');
Route::post('/request/orders/deleteinfo','Sales\OrdersController@DeleteOrderInfo');
Route::post('/request/orders/getproductprice','Sales\OrdersController@GetProductPrice');
Route::post('/request/orders/payorder','Sales\OrdersController@PayOrder');
Route::post('/request/orders/getstockinformation','Sales\OrdersController@GetStockInformation');
Route::post('/request/orders/validatestockprice','Sales\OrdersController@ValidateStockPrice');


Route::post('/request/planstatus/displaylist','Production\PlansStatusController@DisplayList');
Route::post('/request/planstatus/savestatusinfo','Production\PlansStatusController@SaveStatusInfo');
Route::post('/request/planstatus/deletestatusinfo','Production\PlansStatusController@DeleteStatusInfo');

Route::post('/request/bom/displaylistbom','MRP\BillOfMaterialsController@DisplayListBom');
Route::post('/request/bom/deletebominfo','MRP\BillOfMaterialsController@DeleteBomInfo');
Route::post('/request/bom/displayproductinfo','MRP\BillOfMaterialsController@DisplayProductInfo');
Route::post('/request/bom/savebominfo','MRP\BillOfMaterialsController@SaveBomInfo');
Route::post('/request/bom/savebomitems','MRP\BillOfMaterialsController@SaveBomItems');
Route::post('/request/bom/displaylistitems','MRP\BillOfMaterialsController@Displaylistitems');
Route::post('/request/mrp/calculatetotalprice','MRP\BillOfMaterialsController@CalculateTotalPrice');
Route::post('/request/bom/deletebomitem','MRP\BillOfMaterialsController@DeleteBOMitem');


Route::post('/request/productionplan/displaylist','Production\ProductionPlanController@DisplayList');
Route::post('/request/productionplan/saveplaninfo','Production\ProductionPlanController@SaveProductionPlanInfo');
Route::post('/request/productionplan/deleteplaninfo','Production\ProductionPlanController@DeleteProductionPlan');
Route::post('/request/productionplan/displaylisproducts','Production\ProductionPlanController@DisplayLisproducts');
Route::post('/request/productionplan/deleteproductinfo','Production\ProductionPlanController@DeleteProductInfo');
Route::post('/request/productionplan/saveplaniteminfo','Production\ProductionPlanController@SavePlanItemInfo');
Route::post('/request/productionplan/assignplanto','Production\ProductionPlanController@AssignPlanTo');
Route::post('/request/productionplan/planapproval','Production\ProductionPlanController@ProductionPlanApproval');
Route::post('/request/productionplan/startproduction','Production\ProductionPlanController@StartProductionPlan');
Route::post('/request/productionplan/pauseproduction','Production\ProductionPlanController@PauseProductionPlan');
Route::post('/request/productionplan/blockproduction','Production\ProductionPlanController@BlockProductionPlan');
Route::post('/request/productionplan/createqualitycheck','Production\ProductionPlanController@CreateQualityCheck');
Route::post('/request/plan/editqualitycheck','Production\ProductionPlanController@EditQualityCheck');

Route::post('/request/qualitycheck/displaylist','Production\QualityCheckController@DisplayList');


Route::post('/request/displaylistjobstatus','Maintenance\JobStatusController@DisplayList');
Route::post('/request/savejobstatusinfo','Maintenance\JobStatusController@SaveJobStatusInfo');
Route::post('/request/deletejobstatus','Maintenance\JobStatusController@DeleteJobStatusInfo');

Route::post('/request/jobs/displaylist','Maintenance\JobsController@DisplayList');
Route::post('/request/savejobinfo','Maintenance\JobsController@SaveJobDataInfo');
Route::post('/request/deletejobinfo','Maintenance\JobsController@DeleteJobDataInfo');
Route::post('/request/jobs/displaylistitems','Maintenance\JobsController@DisplayListItems');
Route::post('/request/jobs/displayitemsdropdown','Maintenance\JobsController@Displayitemsdropdown');
Route::post('/request/jobs/getserviceprice','Maintenance\JobsController@GetServicePrice');
Route::post('/request/jobs/getproductlog','Maintenance\JobsController@GetProductLog');
Route::post('/request/jobs/payorder','Maintenance\JobsController@PayJobOrder');




Route::post('/request/projects/displayliststatuses','PM\ProjectStatusesController@DisplayList');
Route::post('/request/projects/savestatusinfo','PM\ProjectStatusesController@Saveinfo');
Route::post('/request/projects/deletestatusinfo','PM\ProjectStatusesController@DeleteData');


Route::post('/request/projects/displaylisttypes','PM\ProjectTypesController@DisplayList');
Route::post('/request/projects/savetypeinfo','PM\ProjectTypesController@Saveinfo');
Route::post('/request/projects/deletetypeinfo','PM\ProjectTypesController@DeleteData');


Route::post('/request/lines/displaylist','Phones\PhoneLinesController@DisplayList');
Route::post('/phones/lines/savelineinfo','Phones\PhoneLinesController@SaveLineInfo');
Route::post('/phones/lines/deleteline','Phones\PhoneLinesController@DeleteLineInfo');

Route::post('/request/units/displaylist','Phones\PhoneUnitsController@DisplayList');
Route::post('/phones/units/saveunitinfo','Phones\PhoneUnitsController@SaveUnitsInfo');
Route::post('/phones/units/deleteunit','Phones\PhoneUnitsController@DeletePhoneUnitsInfo');

Route::post('/phones/transactions','Phones\PhoneTransactionsController@DisplayList');
Route::post('/phones/transactions/addform','Phones\PhoneTransactionsController@SaveTransactionInfo');
Route::post('/phones/transactions/editform/{pt_id}','Phones\PhoneTransactionsController@DeleteTransactionInfo');

Route::post('/crm/reports/leads','Reports\CRMReportsController@DisplayLeadReports');
Route::post('/crm/reports/accounts','Reports\CRMReportsController@DisplayAccountsReport');