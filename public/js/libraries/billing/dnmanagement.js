/**
 * 
 */
$(function(){
	$('select').select2();
	debitnotes_module.DisplayListDebitNotes();
	$('input[name=dn_start_date]').datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('input[name=dn_end_date]').datepicker({ 
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('select').on("change",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 debitnotes_module.DisplayListDebitNotes();
	 });
	 $('input[name=start_date]').on("change",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 debitnotes_module.DisplayListDebitNotes();
	 });
	 $('input[name=end_date]').on("change",function(){ 
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 debitnotes_module.DisplayListDebitNotes();
	 });
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 debitnotes_module.DisplayListDebitNotes();
	 });
})