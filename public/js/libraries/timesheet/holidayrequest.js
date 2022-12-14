/**
 * 
 */
$(function(){
	holidays_module.displayListHolidayRequests();
	$("#HR_USER_ID").on("click",function(){
		$.hr_datatable.destroy();
		holidays_module.displayListHolidayRequests();
	});
	$("#HR_DEPARTMENT_ID").on("click",function(){
		$.hr_datatable.destroy();
		holidays_module.displayListHolidayRequests();
	});
	$("#HR_REQUEST_STATUS").on("click",function(){
		$.hr_datatable.destroy();
		holidays_module.displayListHolidayRequests();
	});
	$('select').select2();
	$("#CHANGE_REQUEST_STATUS").on("click",holidays_module.OpenApproveDenyPopUp);
	 ClassicEditor
     .create( document.querySelector( '#REQUEST_INFORMATION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $(".quickactions").on("click",holidays_module.QuickActionRequests);
	 $("#BTN_CHANGE_STATUS").on("click",holidays_module.SaveChangingStatus);
	 
})