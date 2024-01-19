/**
 * 
 */

$(function(){
	$('select').select2();
	bom_module.DisplayListBOM();
	$('#LstBom').on('click',"a[id*=EDIT_BOM_]",bom_module.EditBomInfo);
	$('#LstBom').on('click',"a[id*=DELETE_BOM_]",bom_module.DeleteBOMData);
})