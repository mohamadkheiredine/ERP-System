/**
 * 
 */
$(function(){
	$('button[name=btn_search]').on('click',timesheet_module.DisplayListTransportationEmployees);
	 new tempusDominus.TempusDominus(document.getElementById('TE_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true, 
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "L"
			 
		 }
	});
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