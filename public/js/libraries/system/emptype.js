/**
 * 
 */

$(function(){
	emptype_module.displayListEmpType();
	$(".LstEmpTypeGrid").on('click',"a[id*=EDIT_EMPTYPE_]",emptype_module.DisplayEditEmpTypeForm);
	$(".LstEmpTypeGrid").on('click',"a[id*=DELETE_EMPTYPE_]",emptype_module.DeleteEmpTypeData);
})