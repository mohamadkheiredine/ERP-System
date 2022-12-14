
 $(function(){
	 warehouses_module.DisplayWarehouseSettingsTab();
	 $(".WarehouseDimensions").on("click",function(){
		 $("#TAB").val("warehouse_dimension");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseZones").on("click",function(){
		 $("#TAB").val("warehouse_zones");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseEmployees").on("click",function(){
		 $("#TAB").val("warehouse_employees");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $(".WarehouseLoad").on("click",function(){
		 $("#TAB").val("warehouse_load");
		 warehouses_module.DisplayWarehouseSettingsTab();
	 });
	 $("#WAREHOUSEDIMENSIONS").on("click","#BTN_DRAW_IMAGE",warehouses_module.DrawWarehouseImage);
	 $("button[name=btn_save_employee]").on("click",warehouses_module.AddWarehouseEmployee);
	 $("#m_wizard_warehouse_zones").on("click","#BTN_CREATE_ZONE",warehouses_module.AddNewWarehouseZone);
	 $("#m_wizard_warehouse_zones").on("click","a[id*=DELETE_ZONE_]",warehouses_module.DeleteWarehouseZone);
	 $("#m_wizard_warehouse_employees").on("click","a[id*=DELETE_EMPLOYEE_]",warehouses_module.DeleteWarehouseEmployee);
	// $("#SAVE_SETTINGS").on("click",);
	 var canvas_width = $('#CanvasPage').width();
	 $('canvas').width(canvas_width);
	 $('select').select2();
 })