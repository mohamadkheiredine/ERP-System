<?php

use Illuminate\Support\Facades\Route;


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

Route::post('/request/dashboard/getstockbyproducts','Dashboard\DashboardController@GetStockByProducts');
Route::post('/request/dashboard/getstockbywarehouses','Dashboard\DashboardController@GetStockByWarehouse');
Route::post('/request/dashboard/getsellingproducts','Dashboard\DashboardController@GetTopSellingProducts');

Route::post('/request/dashboard/getstockbycategories','Dashboard\DashboardController@GetStockByCategories');

Route::post('/request/users/saveaccsettings','Users\UsersController@SaveAccSettings');

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

Route::get('/request/zones/displaylist','WareHouses\WarehouseZonesController@DisplayList');
Route::post('/request/zones/savezoneinfo','WareHouses\WarehouseZonesController@SaveWarehouseZoneInfo');
Route::delete('/request/zones/deletezoneinfo','WareHouses\WarehouseZonesController@DeleteWarehouseZoneInfo');


Route::get('/request/floors/displaylist','WareHouses\WarehouseFloorsController@DisplayList');
Route::post('/request/floors/savefloorinfo','WareHouses\WarehouseFloorsController@SaveWarehouseFloorInfo');
Route::delete('/request/floors/deletefloorinfo','WareHouses\WarehouseFloorsController@DeleteWarehouseFloorInfo');


Route::get('/request/assets/listlocations','Assets\AssetLocationsController@DisplayList');
Route::post('/request/assets/savelocationinfo','Assets\AssetLocationsController@SaveAssetLocationInfo');
Route::delete('/request/assets/deletelocationinfo','Assets\AssetLocationsController@DeleteAssetLocationInfo');

Route::get('/request/assets/listcategories','Assets\AssetCategoriesController@DisplayList');
Route::post('/request/assets/savecategoryinfo','Assets\AssetCategoriesController@SaveAssetCategoryInfo');
Route::delete('/request/assets/deletecategoryinfo','Assets\AssetCategoriesController@DeleteAssetCategoryInfo');

Route::get('/request/assets/displaylist','Assets\AssetsController@DisplayList');
Route::post('/request/assets/saveinfo','Assets\AssetsController@SaveAssetInfo');
Route::delete('/request/assets/deleteinfo','Assets\AssetsController@DeleteAssetInfo');

Route::get('/request/assets/displaylistdep','Assets\AssetDepreciationController@DisplayList');
Route::post('/request/assets/savedepinfo','Assets\AssetDepreciationController@SaveAssetDepreciationInfo');
Route::delete('/request/assets/deletedepinfo','Assets\AssetDepreciationController@DeleteAssetDepreciationInfo');


Route::get('/request/assets/displaylisttransfers','Assets\AssetTransfersController@DisplayList');
Route::post('/request/assets/savetransferinfo','Assets\AssetTransfersController@SaveAssetTransferInfo');
Route::delete('/request/assets/deletetransferinfo','Assets\AssetTransfersController@DeleteAssetTransferInfo');

Route::post('/request/products/displaylistcategory','Inventory\ProductCategoriesController@DisplayList');
Route::post('/request/products/savecategoryinfo','Inventory\ProductCategoriesController@SaveProductCategoryInfo');
Route::post('/request/products/deletecategoryinfo','Inventory\ProductCategoriesController@DeleteProductCategoryInfo');

Route::post('/request/products/displaylist','Inventory\ProductsController@DisplayList');
Route::post('/request/products/saveproductinfo','Inventory\ProductsController@SaveProductInfo');
Route::post('/request/products/deleteproductinfo','Inventory\ProductsController@DeleteProductInfo');
Route::post('/request/products/displayliststocks','Inventory\ProductsController@DisplayListStocks');
Route::post('/request/products/displayliststockmovements','Inventory\ProductsController@DisplayListStockMovements');
Route::post('/request/products/addstock','Inventory\ProductStocksController@AddStock');
Route::put('/request/products/stocktransfer','Inventory\ProductStocksController@StockTransfer');
Route::post('/request/products/displaymetricsection','Inventory\ProductsController@DisplayMetricSection');
Route::post('/request/products/generatebarcode','Inventory\ProductsController@GenerateBarCode');
Route::post('/request/products/duplicateproducts','Inventory\ProductsController@Duplicateproducts');
Route::post('/request/products/getzonesdropdown','Inventory\ProductsController@GetZonesDropdown');
Route::post('/request/products/getfloorsdropdown','Inventory\ProductsController@GetFloorsDropdown');
Route::get('/request/products/downloadtemplate','Inventory\ProductsController@DownloadTemplate');
Route::post('/request/products/uploadlistproducts','Inventory\ProductsController@Uploadlistproducts');
Route::post('/request/movements/additems','Inventory\ProductStockTransferController@AddTransferItems');

Route::post('/request/productcategories/displaylistitems','Inventory\ProductCategoriesController@DisplayListItems');

Route::post('/request/stock/getproductinfo','Inventory\ProductStocksController@GetProductinfo');
Route::post('/request/stocktransfer/generatetransfervoucher','Inventory\ProductStocksController@GenerateTransferVoucher');


Route::post('/request/displayliststock','Inventory\ProductStocksController@DisplayList');
Route::post('/request/savestockinfo','Inventory\ProductStocksController@SaveProductStockInfo');
Route::post('/request/deletestock','Inventory\ProductStocksController@DeleteStockInfo');

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
Route::post('/request/customers/download-template', 'Inventory\CustomersController@DownloadCsvTemplate');
Route::post('/request/customers/importlistcustomers', 'Inventory\CustomersController@ImportListCustomers');

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


Route::get('/request/payroll/displaylistdedben','PayRoll\PayrollsDedBenController@DisplayList');
Route::post('/request/payroll/savededbeninfo','PayRoll\PayrollsDedBenController@SaveDedBenInfo');
Route::delete('/request/payroll/deletededbeninfo','PayRoll\PayrollsDedBenController@DeleteDedBenInfo');


Route::get('/request/payroll/displaylistperiods','PayRoll\PayRollsPeriodController@DisplayList');
Route::post('/request/payroll/saveperiodinfo','PayRoll\PayRollsPeriodController@SavePayRollPeriodInfo');
Route::delete('/request/payroll/deleteperiodinfo','PayRoll\PayRollsPeriodController@DeletePayRollPeriodInfo');


Route::get('/request/payroll/displaylisttaxbrackets','PayRoll\PayrollsTaxBracketsController@DisplayList');
Route::post('/request/payroll/savebracketsinfo','PayRoll\PayrollsTaxBracketsController@SavePayRollBracketInfo');
Route::delete('/request/payroll/deletebracketinfo','PayRoll\PayrollsTaxBracketsController@DeletePayRollPeriodInfo');


Route::post('/request/timesheet/displaylisttimesheet','Timesheet\TimeSheetController@DisplayListTimesheet');
Route::post('/request/timesheet/checkincheckout','Timesheet\TimeSheetController@CheckInCheckOutAttendance');
Route::post('/request/timesheet/admintimesheet','Timesheet\TimeSheetController@DisplaySelectedTimesheet');
Route::post('/request/timesheet/saveadmintimesheet','Timesheet\TimeSheetController@SaveAdminTimesheet');
Route::post('/request/timesheet/displaylistonlineemployees','Timesheet\TimeSheetController@DisplayListOnlineemployees');
Route::post('/request/timesheet/displaylisttransportationemployees','Timesheet\TimeSheetController@DisplaylistTransportationEmployees');
Route::post('/request/timesheet/displaylistholidayemployees','Timesheet\TimeSheetController@Displaylistholidayemployees');
Route::post('/request/timesheet/displaylistsalaries','Timesheet\TimeSheetController@DisplayListSalaries');


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
Route::delete('/request/shipment/operations/deleteorderinfo','Shipment\ShipmentController@DeleteOrderShipmentInfo');
Route::post('/request/shipment/operations/displaylistorders','Shipment\ShipmentController@Displaylistorders');
Route::post('/request/shipment/operations/addorder','Shipment\ShipmentController@LinkOperationOrder');
Route::post('/request/operations/downloadpackinglist','Shipment\ShipmentController@DownloadPackingList');


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
Route::post('/request/leads/quickaddlead','CRM\LeadsController@QuickAddLead');
Route::post('/request/leads/addleadresult','CRM\LeadsController@Addleadresult');
Route::post('/request/deals/generatecontract','CRM\DealsController@GenerateAndDownloadContract');
Route::get('/request/leads/displaylistleadresults','CRM\LeadsController@GetDisplayListLeadResults');
Route::post('/request/leads/checkleadexistbymobile','CRM\LeadsController@CheckLeadExistByMobile');
Route::get('/request/callcenter/displaylistappointments','CallCenter\AppointmentsController@DisplayListApp');
Route::post('/request/leads/saveappointmentinfo','CallCenter\AppointmentsController@SaveAppointmentInfo');
Route::delete('/request/callcenter/deleteleadapp','CallCenter\AppointmentsController@DeleteAppointmentInfo');
Route::get('/request/callcenter/listappointmentsbydate','CallCenter\AppointmentsController@ListAppointmentsByDate');
Route::get('/request/callcenter/displayclosuresalesmanapp','CallCenter\AppointmentsController@DisplayClosureSalesmanApp');
Route::get('/request/callcenter/downloadclosuresalesapp','CallCenter\AppointmentsController@DownloadClosureSalesApp');
Route::get('/request/deals/getdealinfo','CRM\DealsController@GetDealInfo');
Route::get('/request/callcenter/getappointmentinformation','CallCenter\AppointmentsController@GetAppointmentInformation');
Route::post('/request/appointments/generateappointmentsreport','CallCenter\AppointmentsController@GenerateAppointmentsReport');
Route::get('/request/crm/getleadinfo','CRM\LeadsController@GetLeadInfo');

Route::get('/generate-pdf', [App\Http\Controllers\CRM\DealsController::class, 'generatePDF']);

Route::post('/request/leadstatus/displaylist','CRM\LeadsStatusController@DisplayList');
Route::post('/request/leadstatus/savestatusinfo','CRM\LeadsStatusController@SaveStatusInfo');
Route::post('/request/leadstatus/deletestatusinfo','CRM\LeadsStatusController@DeleteStatusInfo');

Route::post('/request/activities/displaylist','CRM\LeadActivitiesController@DisplayList');


Route::get('/request/appresult/displaylist','CRM\LeadAppResultsController@DisplayList');
Route::post('/request/appresult/saveresultinfo','CRM\LeadAppResultsController@SaveAppResultInfo');
Route::delete('/request/appresult/deleteresultinfo','CRM\LeadAppResultsController@DeleteAppResultInfo');


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
Route::get('/request/accounting/downloadtemplate', 'Accounting\AccountingController@downloadStatementToExcel');

Route::get('/request/appointments/displaylistreportcallback','CallCenter\AppointmentsController@DisplayListCallbackReport');


Route::post('/request/clients/displaylistcategory','CRM\ClientsCategoriesController@DisplayList');
Route::post('/request/clients/savecategoryinfo','CRM\ClientsCategoriesController@SaveClientCategoryInfo');
Route::post('/request/clients/deletecategoryinfo','CRM\ClientsCategoriesController@DeleteClientCategoryInfo');


Route::post('/request/deals/displaylistdeals','CRM\DealsController@DisplayList');
Route::post('/request/deals/savedealinfo','CRM\DealsController@SaveDealsInfo');
Route::post('/request/deals/deletedealinfo','CRM\DealsController@DeleteDealsInfo');
Route::get('/request/deals/getproductinfo','CRM\DealsController@GetProductInfo');


Route::post('/request/clients/displaylist','CRM\AccountsController@DisplayList');
Route::post('/request/clients/saveaccountinfo','CRM\AccountsController@SaveAccountInfo');
Route::post('/request/clients/deleteaccountinfo','CRM\AccountsController@DeleteAccountInfo');
Route::put('/request/leads/converttoaccounts','CRM\AccountsController@ConvertLeadtoAccount');
Route::get('/request/account/getaccountinfobycode','CRM\AccountsController@GetAccountInfoByCode');
Route::post('/request/account/generatedealpaymentspreview','CRM\DealsController@GenerateDealPaymentsPreview');
Route::post('/request/clients/getregionarea','CRM\AccountsController@GetRegionArea');
Route::post('/request/leads/getregionarea','CRM\LeadsController@GetRegionArea');

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
Route::get('/request/billing/getaccountinfo','Billing\InvoicesController@GetAccountInfo');
Route::get('/request/billing/getproductdata','Billing\InvoicesController@GetProductDataInfo');
Route::get('/request/bills/getpaymentinfo','Billing\InvoicesController@GetPaymentBillsInfo');
Route::post('/request/billing/savebillinfo','Billing\InvoicesController@SaveInvoicePayment');
Route::get('/request/bills/downloadbillsreport','Billing\InvoicePaymentsController@CSVDownloadBillsReport');
Route::get('/request/bills/getlistbillresults','Billing\InvoicePaymentsController@GetListBillResult');
Route::post('/request/bills/savecallbillresult','Billing\InvoicePaymentsController@SaveBillResultInfo');
Route::post('/request/bills/getregionarea','Billing\InvoicePaymentsController@GetRegionArea');

Route::get('/request/billing/getcompanysupplier','Billing\InvoicesController@GetCompanySupplier');

Route::post('/request/billing/linkinvoiceitems','Billing\InvoicesController@LinkInvoiceItems');

Route::post('/request/billing/generatecode','Utilities\ConfigurationController@GenerateVoucherCode');


Route::post('/request/billing/displaylistinttransfers','Billing\InternalTransfersController@DisplayList');
Route::post('/request/billing/saveinttransferinfo','Billing\InternalTransfersController@SaveINInfo');
Route::post('/request/billing/deleteinttransferinfo','Billing\InternalTransfersController@DeleteINInfo');

Route::get('/request/voucher/getselectedvoucher','Billing\PaymentVouchersController@GetSelectedVoucher');


Route::get('/request/billing/displaylistbills','Billing\InvoicePaymentsController@DisplayList');
Route::put('/request/billing/savebillsinfo','Billing\InvoicePaymentsController@SavePaymentBillInfo');
Route::delete('/request/billing/deletebillsinfo','Billing\InvoicePaymentsController@DeleteBillInfo');


Route::get('/request/billing/displaylisttemplateitems','Billing\InvoiceTemplatesController@DisplayListTemplateItems');
Route::get('/request/billing/displaylistinvoicetemplates','Billing\InvoiceTemplatesController@DisplayList');
Route::post('/request/billing/saveinvoicetemplateinfo','Billing\InvoiceTemplatesController@SaveTemplateInfo');
Route::put('/request/billing/saveinvtemplateitem','Billing\InvoiceTemplatesController@SaveInvoiceTemplateItemInfo');
Route::delete('/request/billing/deleteinvoicetemplateinfo','Billing\InvoiceTemplatesController@DeleteTemplateInfo');
Route::delete('/request/request/billing/deletetemplateitem','Billing\InvoiceTemplatesController@DeleteTemplateInvoiceItem');


Route::post('/request/journalvouchers/displaylist','Billing\JournalVouchersController@DisplayList');
Route::post('/request/journalvouchers/savejvoucherinfo','Billing\JournalVouchersController@SaveJournalVoucherInfo');
Route::post('/request/journalvouchers/deletejvoucher','Billing\JournalVouchersController@DeleteJournalVoucherInfo');


Route::post('/request/vouchers/displaylistextensions','Billing\PaymentVouchersController@DisplayListExtensions');
Route::post('/request/vouchers/displayextensionrow','Billing\PaymentVouchersController@DisplayNewExtensionRow');
Route::post('/request/vouchers/viewextensionrow','Billing\PaymentVouchersController@ViewExtensionRow');
Route::post('/request/vouchers/saveextensionrow','Billing\PaymentVouchersController@SaveExtensionRow');


Route::post('/request/receipts/displaylist','Billing\ReceiptsController@DisplayList');
Route::post('/request/receipts/displayactivelist','Billing\ReceiptsController@DisplayActiveList');
Route::post('/request/billing/savereceiptinfo','Billing\ReceiptsController@SaveReceiptInfo');
Route::post('/request/billing/deletereceiptinfo','Billing\ReceiptsController@DeleteReceiptInfo');
Route::post('/request/billing/generatereceiptcode','Billing\ReceiptsController@GenerateReceiptcode');
Route::get('/request/receipts/getselectedreceipt','Billing\ReceiptsController@GetSelectedReceipt');

Route::post('/request/billing/displaylistpayments','Billing\PaymentVouchersController@DisplayList');
Route::post('/request/billing/displaylistonepagerpayments','Billing\PaymentVouchersController@DisplayListOnepage');
Route::post('/request/billing/savepayvoucherinfo','Billing\PaymentVouchersController@SavePaymentVoucherInfo');
Route::post('/request/billing/deletepayvoucherinfo','Billing\PaymentVouchersController@DeleteVoucherInfo');
Route::get('/request/billing/getvouchercode','Billing\PaymentVouchersController@GenerateVoucherCode');


Route::get('/request/billing/displaylistrecurring','Billing\RecurringInvoicesController@DisplayList');
Route::post('/request/billing/saverecurringinvoice','Billing\RecurringInvoicesController@SaveRecurringInvoice');
Route::delete('/request/billing/deleterecurringinvoice','Billing\RecurringInvoicesController@DeleteRecurringInvoiceInfo');


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
Route::post('/request/suppliers/download-template', 'SRM\SuppliersController@DownloadCsvTemplate');
Route::post('/request/suppliers/importlistsuppliers', 'SRM\SuppliersController@ImportListSuppliers');

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


Route::get('/request/payrollsperiod/displaylist','PayRoll\PayRollsPeriodController@DisplayList');
Route::post('/request/payrollsperiod/saveinfo','PayRoll\PayRollsPeriodController@SavePayRollPeriodInfo');
Route::delete('/request/payrollsperiod/deleteinfo','PayRoll\PayRollsPeriodController@DeletePayRollPeriodInfo');



Route::get('/request/salarydetails/displaylist','PayRoll\SalaryDetailsController@DisplayList');
Route::post('/request/salarydetails/saveinfo','PayRoll\SalaryDetailsController@SaveSalaryDetailsInfo');
Route::delete('/request/salarydetails/deleteinfo','PayRoll\SalaryDetailsController@DeleteSalaryDetailsInfo');
Route::get('/request/salarydetails/getemployeeinfo','PayRoll\SalaryDetailsController@GetEmployeeInfo');
Route::post('/request/salarydetails/generatepayrolltransaction','PayRoll\SalaryDetailsController@GeneratePayRollTransaction');
Route::post('/request/salarydetails/generateallrecords','PayRoll\SalaryDetailsController@GenerateAllRecords');


Route::post('/request/ptransactions/payemployeepayroll','PayRoll\PayRollsTransactionsController@PayEmployeePayRoll');
Route::get('/ request/ptransactions/displaylist','PayRoll\PayRollsTransactionsController@DisplayList');


Route::post('/request/displaylistorderstatus','Sales\OrderStatusController@DisplayList');
Route::post('/request/saveorderstatusinfo','Sales\OrderStatusController@SaveOrderStatusInfo');
Route::post('/request/deleteorderstatus','Sales\OrderStatusController@DeleteOrderStatusInfo');

Route::post('/request/orders/displaylist','Sales\OrdersController@DisplayList');
Route::post('/request/orders/displaylistproducts','Sales\OrdersController@DisplayListProducts');
Route::post('/request/orders/deleteOrderProduct', 'Sales\OrdersController@DeleteOrderProduct');
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


Route::post('/request/projects/displaylistroles','PM\ProjectRolesController@DisplayList');
Route::post('/request/projects/saveroleinfo','PM\ProjectRolesController@SaveInfo');
Route::post('/request/projects/deleteroleinfo','PM\ProjectRolesController@DeleteProjectRole');

Route::get('/request/projects/listteams','PM\ProjectsController@DisplayListProjectTeams');
Route::get('/request/projects/displaylist','PM\ProjectsController@DisplayList');
Route::post('/request/projects/saveinfo','PM\ProjectsController@SaveInfo');
Route::delete('/request/projects/deleteinfo','PM\ProjectsController@DeleteProjectInfo');

Route::get('/request/projects/listmmilestones','PM\ProjectsController@DisplayListProjectMilestones');
Route::get('/request/projects/listphases','PM\ProjectsController@DisplayListProjectPhases');
Route::get('/request/projects/listjobs','PM\ProjectsController@DisplayListProjectJobs');
Route::get('/request/projects/listtasks','PM\ProjectsController@DisplayListProjectTasks');
Route::put('/request/projects/linkprojectteam','PM\ProjectsController@LinkProjectTeam');


Route::get('/request/projects/displaylistphases','PM\ProjectPhasesController@DisplayList');
Route::post('/request/projects/savephasesinfo','PM\ProjectPhasesController@Saveinfo');
Route::delete('/request/projects/deletephasesinfo','PM\ProjectPhasesController@DeleteData');
Route::get('/request/projects/generatephasecode','PM\ProjectPhasesController@GeneratePhaseCode');


Route::get('/request/projects/displaylistjobs','PM\ProjectJobsController@DisplayList');
Route::post('/request/projects/savejobsinfo','PM\ProjectJobsController@Saveinfo');
Route::delete('/request/projects/deletejobinfo','PM\ProjectJobsController@DeleteData');
Route::get('/request/projects/generatejobcode','PM\ProjectJobsController@GenerateJobsCode');


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



Route::post('/request/displaylistsorderstatus','Shipment\SOrderStatusController@DisplayList');
Route::post('/request/shipping/savesorderstatusinfo','Shipment\SOrderStatusController@SaveOrderStatusInfo');
Route::delete('/request/deletesorderstatus','Shipment\SOrderStatusController@DeleteOrderStatusInfo');


Route::post('/request/packing/displaylist','Shipment\PackingPricesController@DisplayList');
Route::post('/request/packing/savepackingprice','Shipment\PackingPricesController@SavePackingPricesInfo');
Route::delete('/request/packing/deletepackingprice','Shipment\PackingPricesController@DeletePackingPricesInfo');


Route::post('/payroll/employeespayroll/displaylist','PayRoll\PayRollController@DisplayList');
Route::post('/payroll/employeespayroll/savepackingprice','PayRoll\PayRollController@SavePayRollInfo');
Route::delete('/payroll/employeespayroll/deletepackingprice','PayRoll\PayRollController@DeletePayRollInfo');

Route::post('/request/sorders/displaylist','Shipment\SOrdersController@DisplayList');
Route::post('/request/sorders/saveorderinfo','Shipment\SOrdersController@SaveOrderInfo');
Route::delete('/request/sorders/deleteorderinfo','Shipment\SOrdersController@DeleteOrderInfo');
Route::post('/request/sorders/savepackingcategory','Shipment\SOrdersController@SavePackingCategory');
Route::post('/request/sorders/displaylistcategories','Shipment\SOrdersController@DisplayListCategories');
Route::post('/request/orders/getpackingprice','Shipment\SOrdersController@GetPackingPrice');
Route::post('/request/sorders/payorder','Shipment\SOrdersController@PayOrder');
Route::post('/request/sorders/deleteordercategory','Shipment\SOrdersController@DeleteOrderCategory');



Route::get('/payroll/employees/displaylist','PayRoll\EmployeesController@DisplayList');
Route::post('/payroll/employees/saveinfo','PayRoll\EmployeesController@SaveInfo');
Route::delete('/payroll/employees/deleteemployee','PayRoll\EmployeesController@DeleteRecord');



Route::post('/request/costcenters/displaylistcategories','CostCenter\CostCenterCategoriesController@DisplayList');
Route::post('/request/costcenters/savecategoryinfo','CostCenter\CostCenterCategoriesController@SaveCostCenterCategoryInfo');
Route::delete('/request/costcenters/deletecategoryinfo','CostCenter\CostCenterCategoriesController@DeleteCostCenterCategoryInformation');



Route::post('/request/costcenters/displaylist','CostCenter\CostCenterController@DisplayList');
Route::post('/request/costcenters/saveinfo','CostCenter\CostCenterController@SaveCostCenterInfo');
Route::delete('/request/costcenters/deleteinfo','CostCenter\CostCenterController@DeleteCostCenterInformation');



Route::post('/request/inboundcall/displaylist','CallCenter\InboundController@DisplayList');
Route::post('/request/inboundcall/saveinfo','CallCenter\InboundController@SaveInboundCallInfo');
Route::delete('/request/inboundcall/deleteinfo','CallCenter\InboundController@DeleteInboundCallInformation');
Route::post('/request/inboundcall/savemv','CallCenter\InboundController@SaveMaintenanceVoucherInfo');
Route::post('/request/inboundcall/generateanddownloadlist','CallCenter\InboundController@GenerateAndDownloadList');
Route::get('/request/call/getlistcallresults','CallCenter\InboundController@GetListCallResul');
Route::post('/request/inboundcall/savecallresult','CallCenter\InboundController@SaveCallResultInfo');
Route::get('/request/mvoucher/getnewmaintenancenumber','CallCenter\InboundController@GetNewMaintenanceNumber');
Route::post('/request/inboundcall/addproductstock','CallCenter\InboundController@AddProductStock');
Route::get('/request/inboundcall/getclientinfo','CallCenter\InboundController@GetClientInfo');

Route::get('/request/inboundcall/getresultworkflowinfo','CallCenter\InboundController@GetResultWorkflowinfo');

Route::post('/request/outboundcall/displaylist','CallCenter\OutboundController@DisplayList');
Route::post('/request/outboundcall/saveinfo','CallCenter\OutboundController@SaveOutboundCallInfo');
Route::delete('/request/outboundcall/deleteinfo','CallCenter\OutboundController@DeleteOutboundCallInformation');

Route::get('/request/casestatus/displaylist','CallCenter\CaseStatusController@DisplayList');
Route::post('/request/casestatus/saveinfo','CallCenter\CaseStatusController@SaveCaseStatusInfo');
Route::delete('/request/casestatus/deleteinfo','CallCenter\CaseStatusController@DeleteCaseStatusInfo');

Route::get('/request/maintenancecase/displaylist','CallCenter\MaintenanceCaseController@DisplayList');
Route::post('/request/maintenancecase/saveinfo','CallCenter\MaintenanceCaseController@SaveMaintenanceCaseInfo');
Route::delete('/request/maintenancecase/deleteinfo','CallCenter\MaintenanceCaseController@DeleteMaintenanceCaseInfo');


Route::get('/request/system/displayliststatus','System\SystemStatusController@DisplayList');
Route::post('/request/system/savestatusinfo','System\SystemStatusController@SaveStatusInfo');
Route::delete('/request/system/deletestatusinfo','System\SystemStatusController@DeleteStatusInfo');



Route::get('/request/reports/displayliststockavailability','WareHouses\WarehouseController@DisplayListStockAvailability');
Route::get('/request/reports/displayliststockmovement','WareHouses\WarehouseController@displaylistWarehouseMovement');
Route::get('/request/reports/downloadstockavailability','WareHouses\WarehouseController@DownloadStockAvailability');
Route::get('/request/reports/downloadstockmovements','WareHouses\WarehouseController@DownloadStockMovements');



Route::get('/request/expenses/listcategories','Expenses\ExpenseCategoriesController@DisplayList');
Route::post('/request/expenses/savecategoryinfo','Expenses\ExpenseCategoriesController@SaveExpenseCategoryInfo');
Route::delete('/request/expenses/deletecategoryinfo','Expenses\ExpenseCategoriesController@DeleteExpensesCategoryInfo');

Route::get('/request/expenses/displaylist','Expenses\ExpensesController@DisplayList');
Route::post('/request/expenses/saveexpenseinfo','Expenses\ExpensesController@SaveExpenseInfo');
Route::delete('/request/expenses/deleteexpenseinfo','Expenses\ExpensesController@DeleteExpensesInfo');


Route::get('/request/stores/displaylist','Sales\StoresController@DisplayList');
Route::post('/request/stores/saveinfo','Sales\StoresController@SaveStoreInfo');
Route::delete('/request/stores/deletestoreinfo','Sales\StoresController@DeleteStoreInfo');
Route::get('/request/stores/getlistwarehouses','Sales\StoresController@GetListWarehouses');

Route::get('/request/terminals/displaylist','Sales\TerminalsController@DisplayList');
Route::post('/request/terminals/saveinfo','Sales\TerminalsController@SaveTerminalInfo');
Route::delete('/request/terminals/deleteterminalinfo','Sales\TerminalsController@DeleteTerminalInfo');

Route::get('/request/category/displaylistcategories', 'Fnb\Category\FnbCategoryController@DisplayList');
Route::post('/request/category/saveinfo', 'Fnb\Category\FnbCategoryController@SaveCategoryInfo');
Route::delete('/request/category/deletecategoryinfo', 'Fnb\Category\FnbCategoryController@DeleteCategoryInfo');

Route::get('/request/fnbcategories/displaylistitems', 'Fnb\Category\FnbCategoryController@DisplayListItems');
Route::post('/request/fnbcategories/saveiteminfo', 'Fnb\Category\FnbCategoryController@saveItem');
