/**
 *
 */
$(function(){
	 leads_module.DisplayListLeads();
	 $("input[name=sheet_number]").on("keyup",leads_module.DisplayListLeads);
	 $("input[name=lead_name]").on("keyup",leads_module.DisplayListLeads);
	 $("input[name=lead_region]").on("keyup",leads_module.DisplayListLeads);
	 $("input[name=referred_by]").on("keyup",leads_module.DisplayListLeads);
	 $("input[name=lead_mobile]").on("keyup",leads_module.DisplayListLeads);
	 $("select[name=cl_sales_id]").on("change",leads_module.DisplayListLeads);
	 $("select[name=cl_lead_result]").on("change",leads_module.DisplayListLeads);
	 $("select[name=cl_area]").on("change",leads_module.DisplayListLeads);
	 $("select[name=lead_status]").on("change",leads_module.DisplayListLeads);
	 $("select[name=cl_lead_types]").on("change",leads_module.DisplayListLeads);
    $("#CL_AREA").on("change",function(){
        leads_module.getlistofregions();
        leads_module.DisplayListLeads();
    });
    $("#CL_REGION").on("change",function(){
        leads_module.DisplayListLeads();
    });

    $("#REGION_DROPDOWN").on("change","#CL_REGION",function(){
        leads_module.DisplayListLeads();
    });
	 $(".dropdown-item").on("click",leads_module.QuickActionLead);
	 $("#btnAddResult").on("click",leads_module.AddCallResult);
	 $("button[name=btn_change_status]").on("click",leads_module.SaveChangeLeadsStatus);
	 $("button[name=btn_assign_lead_to]").on("click",leads_module.SaveAssignLeadTo);
	 $("#BTN_ADD_RESULT").on("click",leads_module.SaveAddLeadResult);
     $("button[name=btn_result_close]").on('click',function(){
         $('#AddResultModel').modal('toggle');
     });
    $("button[name=btn_close_changestatus]").on('click',function(){
        $('#ChangeStatusModel').modal('toggle');
    });
    $("button[name=btn_close_assign]").on('click',function(){
        $('#AssignLeadModel').modal('toggle');
    });

 	$("#LstLeads").on("click","a[id*=EDIT_LEAD_]",leads_module.EditLeadInfo);
 	$("#LstLeads").on("click","a[id*=DELETE_LEAD_]",leads_module.DeleteLeadInfo);
 	$("#LstLeads").on("click","tr",leads_module.SelectLeadRecord);
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
