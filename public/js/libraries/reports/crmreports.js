/**
 * 
 */
$(function(){
	$('#LEAD_DATE_FROM').datepicker({
		todayHighlight: true,
		orientation: "bottom left",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('#LEAD_DATE_TO').datepicker({
         todayHighlight: true,
         orientation: "bottom left",
         templates: {
             leftArrow: '<i class="la la-angle-left"></i>',
             rightArrow: '<i class="la la-angle-right"></i>'
         }
     });
	 $('.m_selectpicker').selectpicker();
	 $("#BTN_REPORT").on("click",reports_module.DisplayLeadsReport);
	 reports_module.DisplayLeadsReport();
})