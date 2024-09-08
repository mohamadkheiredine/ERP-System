/**
 * 
 */
$(function(){
	operations_module.DisplayListOperations();
	$("#FK_ACCOUNT_ID").on("change",operations_module.DisplayListOperations);
	$("#FK_WAREHOUSE_ID").on("change",operations_module.DisplayListOperations);
        
        $('#LstShipmentOperations').on("click","a[id*=EDIT_SHIPOP_]",operations_module.EditOperationInfo);
        $('#LstShipmentOperations').on("click","a[id*=DELETE_SHIPOP_]",operations_module.DeleteOperationInfo);
})