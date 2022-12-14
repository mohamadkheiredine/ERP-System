/**
 * 
 */

$(function(){
	jobroles_module.displayListJobRoles();
	$(".LstJobRolesGrid").on('click',"a[id*=EDIT_JOB_ROLE_]",jobroles_module.DisplayEditJobRoleForm);
	$(".LstJobRolesGrid").on('click',"a[id*=DELETE_JOB_ROLE_]",jobroles_module.DeleteJobRoleData);
})