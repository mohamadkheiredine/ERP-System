    <?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::group(['middleware' => ['cors']], function() {
    Route::post('/request/api/login','Api\UsersController@LoginPOS');
    Route::post('/request/api/logout','Api\UsersController@LogoutPOS');
    Route::post('/request/api/getprofileinfo','Api\UsersController@GetUserInfo');
    Route::post('/request/api/saveprofileinfo','Api\UsersController@SetmyprofileInfo');
    Route::post('/request/api/changeprofilepassword','Api\UsersController@ChangeprofilePassword');
    
    Route::post('/request/api/getlistcurrency','Api\GeneralController@getlistcurrency');
    Route::post('/request/api/getlistchartofaccounts','Api\GeneralController@GetListChartofAccounts');
    Route::post('/request/api/getdefaultaccounts','Api\GeneralController@GetDefaultAccounts');
    
    Route::get('/request/api/getlistproducts','Api\ProductsController@GetListProducts');
    Route::post('/request/api/getproductsstock','Api\ProductsController@GetProductsStock');
    Route::get('/request/api/getproductinfo','Api\ProductsController@GetProductInfo');
    Route::post('/request/api/searchproductbyuid','Api\ProductsController@SearchProductByUID');
    Route::post('/request/api/saveproductinfo','Api\ProductsController@SaveProductInfo');
    Route::post('/request/api/generatebarcode','Api\ProductsController@GenerateBarCode');
    Route::post('/request/api/searchproductbyid','Api\ProductsController@SearchProductById');
    Route::get('/request/api/getproductcategories','Api\ProductsController@GetProductCategories');
    Route::get('/request/api/getcategoryinfo','Api\ProductsController@GetCategoryInfo');
    Route::delete('/request/api/deletecategory','Api\ProductsController@DeleteCategoryInfo');
    Route::delete('/request/api/deleteproductinfo','Api\ProductsController@DeleteProductInfo');
    
    Route::post('/request/api/getproductstockinfo','Api\ProductsController@GetProductStockinfo');
    
    
    Route::get('/request/api/products/getlistcategories','Api\ProductsController@GetListCategories');
    Route::post('/request/api/saveproductcategoryinfo','Api\ProductsController@SaveCategoryInfo');
    
    
    Route::post('/request/api/order/addproduct','Api\OrdersController@AddProductToOrder');
    Route::get('/request/api/general/listpaymenttypes','Api\GeneralController@GetListPaymentTypes');
    
    
    Route::post('/request/api/getnumbers','Api\PhoneLinesController@GetListofNumbers');
    Route::post('/request/api/getunitpackages','Api\PhoneLinesController@Getunitpackages');
    
    
    Route::get('/request/api/listcustomers','Api\CustomersController@GetListCustomers');
    Route::get('/request/api/getcustomerinfo','Api\CustomersController@GetCustomerInfo');
    Route::post('/request/api/savecustomer','Api\CustomersController@SaveCustomerInfo');
    Route::post('/request/api/deletecustomers','Api\CustomersController@DeleteCustomer');
    Route::get('/request/api/searchcustomer','Api\CustomersController@SearchCustomer');
    
    
    Route::get('/request/api/listsuppliers','Api\SuppliersController@GetListSuppliers');
    Route::get('/request/api/getsupplierinfo','Api\SuppliersController@GetSupplierInfo');
    Route::delete('/request/api/deletesupplier','Api\SuppliersController@DeleteSupplier');
    Route::post('/request/api/savesupplier','Api\SuppliersController@SaveSupplierInfo');
    
    Route::post('/request/api/listvendors','Api\VendorsController@GetListVendors');
    Route::post('/request/api/getvendorinfo','Api\VendorsController@GetVendorInfo');
    Route::post('/request/api/savevendorinfo','Api\VendorsController@SaveVendorInfo');
    Route::post('/request/api/deletevendors','Api\VendorsController@DeleteVendorInfo');
    
    Route::post('/request/api/createposorder','Api\OrdersController@CreatePOSOrder');
    Route::post('/request/api/createorderrestaurant','Api\OrdersController@CreateOrderRestaurant');
    Route::post('/request/api/pos/splitorderpayment','Api\OrdersController@SplitOrderPayment');
    Route::get('/request/api/searchorderinfo','Api\OrdersController@SearchOrderInfo');
    Route::get('/request/api/getlistorders','Api\OrdersController@GetlistOrders');
    Route::get('/request/api/getorderinfo','Api\OrdersController@GetOrderInfo');
    
    Route::post('/request/api/exportorderstoexcel','Api\OrdersController@ExportListOrdersToExcel');
    
    
//});