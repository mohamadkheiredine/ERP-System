/**
 * 
 */

$(function(){
	phonelines_module.DisplayListPhoneLines();
	$("#generalSearch").on('keyup',function(){
		$('input[name=page_number]').val(1);
		$.pagination.twbsPagination('destroy');
		phonelines_module.DisplayListPhoneLines();
	});
	$(".LstPhoneLinesGrid").on('click',"a[id*=EDIT_LINE_]",phonelines_module.EditPhoneLineInfo);
	$(".LstPhoneLinesGrid").on('click',"a[id*=DELETE_LINE_]",phonelines_module.DeletePhoneLineData);
})