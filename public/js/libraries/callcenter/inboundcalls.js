$(function(){
	inboundcalls_module.DisplayListInboundCalls();
	$("#generalSearch").on('keyup',inboundcalls_module.DisplayListInboundCalls);
	$("select").on('change',inboundcalls_module.DisplayListInboundCalls);
	$("input").on('change',inboundcalls_module.DisplayListInboundCalls);
	$(".dropdown-item").on('click',inboundcalls_module.QuickAction);
	$("#BTN_SAVE_MV").on('click',inboundcalls_module.SaveMaintenanceVoucherInfo);
	$("#BTN_SAVE_RESULT").on('click',inboundcalls_module.SaveCallResultInfo);
	$("#CW_RESULT_ID").on('change',inboundcalls_module.DisplayCallBackDate);
    $("#LstInboundCalls").on("click","tr",inboundcalls_module.SelectCallRecord);
	$('#LstInboundCalls').on('click',"a[id*=EDIT_CALL_]",inboundcalls_module.EditInboundCallInfo);
	$('#LstInboundCalls').on('click',"a[id*=DELETE_CALL_]",inboundcalls_module.DeleteInboundCallData);

             new tempusDominus.TempusDominus(document.getElementById('IC_CALL_DATE'),{
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
             new tempusDominus.TempusDominus(document.getElementById('IC_RESOLUTION_DATE'),{
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


            new tempusDominus.TempusDominus(document.getElementById('CW_CREATION_DATE'),{
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

           new tempusDominus.TempusDominus(document.getElementById('CW_CALLBACK_DATE'),{
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
})
