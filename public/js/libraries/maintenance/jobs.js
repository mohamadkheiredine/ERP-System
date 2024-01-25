/**
 * 
 */

$(function(){
	jobs_module.DisplayListJobs();
	$('#JOB_STATUS').on('change',jobs_module.DisplayListJobs);
	$('#CUSTOMERS').on('change',jobs_module.DisplayListJobs);
	 $(".quickactions").on("click",jobs_module.QuickActionJobs);
		
	$('#LstMaintenanceJobs').on('click',"a[id*=EDIT_JOB_]",jobs_module.EditJobInfo);
	$('#LstMaintenanceJobs').on('click',"a[id*=DELETE_JOB_]",jobs_module.DeleteJobData);
	
	 new tempusDominus.TempusDominus(document.getElementById('J_DUE_DATE'),{
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
	$('#J_DUE_DATE').on('changeDate', function() {
		jobs_module.DisplayListJobs();
	});
	
})