/**
 * 
 */

$(function(){
	departments_module.displayListDepartments();
	$("input[name=general_search]").on('keyup',departments_module.displayListDepartments);
	$(".LstDepartmentsGrid").on('click',"a[id*=EDIT_DEPARTMENT_]",departments_module.DisplayEditDepartmentForm);
	$(".LstDepartmentsGrid").on('click',"a[id*=DELETE_DEPARTMENT_]",departments_module.DeleteDepartmentData);
})