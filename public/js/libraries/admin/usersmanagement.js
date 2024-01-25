/**
 *
 */

$(function(){
	users_module.DisplayListUsers();
	
	$("#LstUsers").on("click","a[id*=EDIT_USER_]",users_module.EditUserInfo);
	$("#LstUsers").on("click","a[id*=DELETE_USER_]",users_module.DeleteUserInfo);
	
	$('input[name=general_search]').on('keyup',function(){
		//$('#UsersPagination').twbsPagination('destroy');
		$('input[name=page_number]').val(1);
		users_module.DisplayListUsers();
	});
})