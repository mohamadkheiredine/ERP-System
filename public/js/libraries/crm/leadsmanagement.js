/**
 * 
 */
$(function(){
	 leads_module.DisplayListLeads();
	 $("input[name=general_search]").on("keyup",leads_module.DisplayListLeads);
	 $("select[name=cl_sales_id]").on("change",leads_module.DisplayListLeads);
	 $("select[name=lead_status]").on("change",leads_module.DisplayListLeads); 
	 $(".dropdown-item").on("click",leads_module.QuickActionLead);
	 $("button[name=btn_change_status]").on("click",leads_module.SaveChangeLeadsStatus);
	 $("button[name=btn_assign_lead_to]").on("click",leads_module.SaveAssignLeadTo);
	 $("#BTN_ADD_RESULT").on("click",leads_module.SaveAddLeadResult);

 	$("#LstLeads").on("click","a[id*=EDIT_LEAD_]",leads_module.EditLeadInfo);
 	$("#LstLeads").on("click","a[id*=DELETE_LEAD_]",leads_module.DeleteLeadInfo);
 	$("#LstLeads").on("click","tr",leads_module.SelectLeadRecord);
        
        //
        
        new tempusDominus.TempusDominus(document.getElementById('LR_NEXT_DATE'),{
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
			 format : "yyyy-MM-dd"
			 
		 }
	});
        
        
});