$(function(){
	outboundcalls_module.DisplayListOutboundCalls();
	$("#generalSearch").on('keyup',outboundcalls_module.DisplayListOutboundCalls);
	$("select").on('change',outboundcalls_module.DisplayListOutboundCalls);

	$('#LstOutboundCalls').on('click',"a[id*=EDIT_CALL_]",outboundcalls_module.EditOutboundCallInfo);
	$('#LstOutboundCalls').on('click',"a[id*=DELETE_CALL_]",outboundcalls_module.DeleteOutboundCallData);
	$('#BTN_QUICK_LEAD').on('click',outboundcalls_module.SaveQuickLead);
})