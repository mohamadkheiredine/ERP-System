/**
 * 
 */
$(function(){
	$('button[name=btn_search]').on('click',timesheet_module.DisplayListTransportationEmployees);
	$('input[name=te_date]').datepicker({
   	 clearDates : true,
	 format: 'yyyy-mm',
	 todayHighlight: false,
	 orientation: "bottom left",
	 templates: {
		 leftArrow: '<i class="la la-angle-left"></i>',
		 rightArrow: '<i class="la la-angle-right"></i>'
	 }});
	$('.quickactions').on('click',function(){
		var action_type = $(this).data('action_type');
		switch(action_type)
		{
			case "EXPORT_AS_CSV":
			{
				timesheet_module.ExportAsCsvTransportationEmployees();
			}
			break;
			case "PRINT":
			{
				timesheet_module.PrintTransportationEmployees();
			}
			break;
		}
	})
})