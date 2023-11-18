/**
 * 
 */


$(function(){
	roles_module.displayListRoles();  
	$('#ListRoleGirds').on('click',"a[id*=EDIT_ROLE_]",roles_module.EditRoleInfo);
	$('#ListRoleGirds').on('click',"a[id*=DELETE_ROLE_]",roles_module.DeleteRoleData);

});