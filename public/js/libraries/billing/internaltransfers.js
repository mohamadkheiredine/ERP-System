/**
 * 
 */
$(function(){

	var fisical_year = getCookie('fisical_year');
	$('input[name=fisical_year]').val(fisical_year);
	$('select').select2();
	inttransfers_module.DisplayListIntTransfers();
	new tempusDominus.TempusDominus(document.getElementById('IN_START_DATE'),{
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
	new tempusDominus.TempusDominus(document.getElementById('IN_END_DATE'),{
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
	 $('select').on("change",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 inttransfers_module.DisplayListIntTransfers();
	 });
	 
	 $('input[name=general_search]').on("keyup",function(){
		 $('input[name=page_number]').val(1);
		 if( $.pagination != null)
			 $.pagination.twbsPagination('destroy');
		 inttransfers_module.DisplayListIntTransfers();
	 });
})