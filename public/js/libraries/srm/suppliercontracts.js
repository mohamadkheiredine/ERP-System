/**
 * 
 */
$(function(){
	$("select").select2();
	 suppliercontracts_module.DisplayListConracts();
	 
	$("#LstSupplierContracts").on('click',"a[id*=EDIT_CONTRACT_]",suppliercontracts_module.EditContractInfo);
	$("#LstSupplierContracts").on('click',"a[id*=DELETE_CONTRACT_]",suppliercontracts_module.DeleteContractData);
})