$(function(){
	$("#generalSearch").on('keyup',inboundcalls_module.DisplayListInboundCalls);
	$("select[name=ic_technician_id]").on('change',inboundcalls_module.DisplayListInboundCalls);
	$("select[name=ic_maintenance_type]").on('change',inboundcalls_module.DisplayListInboundCalls);
	$("select[name=ic_archived_call]").on('change',inboundcalls_module.DisplayListInboundCalls);
	$("select[name=ic_result_id]").on('change',inboundcalls_module.DisplayListInboundCalls);
	$("input#IC_CALL_DATE").on('change',inboundcalls_module.DisplayListInboundCalls);
	$(".dropdown-item").on('click',inboundcalls_module.QuickAction);
	$("#BTN_SAVE_MV").on('click',inboundcalls_module.SaveMaintenanceVoucherInfo);
	$("#BTN_SAVE_RESULT").on('click',inboundcalls_module.SaveCallResultInfo);
	$("#CW_RESULT_ID").on('change',inboundcalls_module.DisplayCallBackDate);
    $("#LstInboundCalls").on("click","tr",inboundcalls_module.SelectCallRecord);
	$('#LstInboundCalls').on('click',"a[id*=EDIT_CALL_]",inboundcalls_module.EditInboundCallInfo);
	$('#LstInboundCalls').on('click',"a[id*=DELETE_CALL_]",inboundcalls_module.DeleteInboundCallData);
	$('.LstCallWResults').on('dblclick',".CallResultRow",inboundcalls_module.GetResultRecordInfo);
	$('.LstInboundCalls').on('dblclick',".SelectedRow",inboundcalls_module.OpenCallResultPopup);
    $("#IC_VISIT_PRICE").on("keyup",inboundcalls_module.ShowOrHidePaymentType);
    $("#CP_PRODUCT_ID").on("change",inboundcalls_module.SwitchOtherDropdownForProduct);
    $("#CP_PRODUCT_NAME").on("change",inboundcalls_module.SwitchPOtherDropdownForProduct);
    $("button[name=btn_add_stock]").on("click",inboundcalls_module.AddProductStock);
    $("button[name=btn_close]").on("click",inboundcalls_module.ResetValues);
    $('#IC_PAYMENT_TYPE').val(2).trigger('change.select2');
    $("#CL_AREA").on("change",function(){
        inboundcalls_module.DisplayListInboundCalls();
        inboundcalls_module.getlistofregions();
    });
    $("#CL_REGION").on("change",function(){
        inboundcalls_module.DisplayListInboundCalls();
    });
    $("#REGION_DROPDOWN").on("change","#CL_REGION",function(){
        inboundcalls_module.DisplayListInboundCalls();
    });

    $('#AddMainVoucher').on('hidden.bs.modal', function (e) {
        $('.LstMaintenanceProducts').html("");
        $('input[name=products_stock]').html("");
        $('#FRM_SAVE_VOUCHER input[type=text]').each(function(){
            $(this).val('');
        });
        $('#FRM_SAVE_VOUCHER select').each(function(){
            $(this).val('').trigger('change.select2');
        });
        $('#FRM_SAVE_VOUCHER').resetForm();

        $('.ClientCode').html("");
        $('.ClientName').html("");
        $('.ClientMobile').html("");
    });

    $('#AddMainVoucher').on('show.bs.modal', function (e) {
        var ic_ids = [];
        $(".checkboxes:checked").each(function(){
            var ic_id = $(this).val();
            ic_ids.push(ic_id);
        });
        var str_ic = ic_ids.join(",");

        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        $.ajax
        ({
            url : base_url + "/request/inboundcall/getclientinfo",
            data : { _token : _token , ic_id : str_ic },
            method : 'get',
            dataType : "json",
            success : function(response){
                $('.ClientCode').html(response.client_code);
                $('.ClientName').html(response.client_name);
                $('.ClientMobile').html(response.client_mobile);
            }
        });


    });

    $(".LstMaintenanceProducts").on("click","#DeleteProductCall",function(){

        let p_id = $(this).data('p_id');

       let products_stock =  $('input[name=products_stock]').val();
        products_stock = JSON.parse(products_stock);

        const index = products_stock.findIndex(item => item.p_id === p_id);
        if (index !== -1) {
            products_stock.splice(index, 1);
        }
        $('input[name=products_stock]').val(JSON.stringify(products_stock));
        $(this).parents('tr').remove();
    });

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
