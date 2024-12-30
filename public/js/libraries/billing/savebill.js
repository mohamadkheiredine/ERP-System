$(function(){
	$("#BTN_SAVE_BILLS").on("click",bills_module.SaveBillInfo);
	$("#IP_CLIENT_CODE").on("change",bills_module.getclientinfo);
})