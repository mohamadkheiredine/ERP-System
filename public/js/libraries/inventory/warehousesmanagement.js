/**
 *
 */

$(function(){
	warehouses_module.DisplayListWareHouses();
	$("#generalSearch").on('keyup',warehouses_module.DisplayListWareHouses)
	$("#LstWarehouses").on('click','a[id*=EDIT_WAREHOUSE_]',warehouses_module.EditWareHouseInformation);
	$("#LstWarehouses").on('click','a[id*=DELETE_WAREHOUSE_]',warehouses_module.DeleteWareHouse);
	$("#LstWarehouses").on('click','a[id*=SETTINGS_WAREHOUSE_]',warehouses_module.WareHouseSettings);
})