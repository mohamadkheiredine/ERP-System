/**
 * 
 */
$(function(){
	 leads_module.DisplayListLeads();
	 $("input[name=general_search]").on("keyup",leads_module.DisplayListLeads);
	 $("select").on("change",leads_module.DisplayListLeads);
	 $(".dropdown-item").on("click",leads_module.QuickActionLead);
	 $("button[name=btn_change_status]").on("click",leads_module.SaveChangeLeadsStatus);
	 $("button[name=btn_assign_lead_to]").on("click",leads_module.SaveAssignLeadTo);

 	$("#LstLeads").on("click","a[id*=EDIT_LEAD_]",leads_module.EditLeadInfo);
 	$("#LstLeads").on("click","a[id*=DELETE_LEAD_]",leads_module.DeleteLeadInfo);
});