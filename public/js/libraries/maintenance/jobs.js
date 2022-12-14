/**
 * 
 */

$(function(){
	jobs_module.DisplayListJobs();
	$('#JOB_STATUS').on('change',jobs_module.DisplayListJobs);
	$('#CUSTOMERS').on('change',jobs_module.DisplayListJobs);
	 $('#J_DUE_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 }).on('changeDate', function(e) {
	       jobs_module.DisplayListJobs();
	    });
	 $(".quickactions").on("click",jobs_module.QuickActionJobs);
})