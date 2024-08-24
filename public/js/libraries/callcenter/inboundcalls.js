$(function(){
	inboundcalls_module.DisplayListInboundCalls();
	$("#generalSearch").on('keyup',inboundcalls_module.DisplayListInboundCalls);
	$("select").on('change',inboundcalls_module.DisplayListInboundCalls);

	$('#LstInboundCalls').on('click',"a[id*=EDIT_CALL_]",inboundcalls_module.EditInboundCallInfo);
	$('#LstInboundCalls').on('click',"a[id*=DELETE_CALL_]",inboundcalls_module.DeleteInboundCallData);
})