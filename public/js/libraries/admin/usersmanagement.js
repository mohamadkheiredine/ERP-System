/**
 *
 */

$(function(){
	users_module.DisplayListUsers();
	$('input[name=general_search]').on('keyup',function(){
		$('#UsersPagination').twbsPagination('destroy');
		$('input[name=page_number]').val(1);
		users_module.DisplayListUsers();
	});
})