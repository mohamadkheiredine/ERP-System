/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#CI_ITEM_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    }).catch( error => {
         console.error( error );
     });
	 $('select').select2();
	 $('#CI_START_DATE').datepicker({
		 startDate :'-1m',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#CI_END_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $("#CI_ITEM_NBR_OF_HOURS").on('keyup',function(){
		 var nbr_of_hours = $("#CI_ITEM_NBR_OF_HOURS").val();
		 var cost_service_per_hour = $("input[name=cost_service_per_hour]").val();
		 var service_cost = nbr_of_hours * cost_service_per_hour;
		 $("#CI_TOTAL_COST").val(service_cost);
		 $("#CI_TOTAL_PRICE").val(service_cost);
	 });
	 
	 $("#BTN_SAVE_LEAD_ITEM").on("click",leads_module.SaveLeadItemsInfo)
})