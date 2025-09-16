inboundcalls_module = {
	DisplayListInboundCalls : function(){
	    var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var page_number = $('input[name=page_number]').val();
	    var general_search = $('input[name=general_search]').val();
	    var ic_archived_call = $('select[name=ic_archived_call]').val();
	    var ic_telemarketing_id = $('select[name=ic_telemarketing_id]').val();
	    var ic_maintenance_type = $('select[name=ic_maintenance_type]').val();
	    var ic_technician_id = $('select[name=ic_technician_id]').val();
	    var ic_result_id = $('select[name=ic_result_id]').val();
	    var ic_call_date = $('input[name=ic_call_date]').val();
            if(ic_maintenance_type.length == 0 || ic_call_date.length == 0)
            {
                return false;
            }

	    $.ajax
	    ({
	        url : base_url + "/request/inboundcall/displaylist",
	        data : { _token : _token ,
                    page_number : page_number ,
                    general_search : general_search ,
                    ic_technician_id : ic_technician_id ,
                ic_result_id : ic_result_id ,
                    ic_telemarketing_id : ic_telemarketing_id,
                    ic_maintenance_type : ic_maintenance_type,
                    ic_archived_call : ic_archived_call,
                    ic_call_date : ic_call_date
                },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	            $('.LstInboundCalls').html(response.display);
                $('.group-checkable').change(function() {
                    var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                    var checked = $(this).prop("checked");
                    $(set).each(function() {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if(response.total_pages > 1)
            	{
                	 $.pagination = $('#InboundCallsPagination').twbsPagination({
                         totalPages: response.total_pages,
                         visiblePages: 7,
                         onPageClick: function (event, page) {
                              $('input[name=page_number]').val(page);
                             inboundcalls_module.DisplayListInboundCalls();
                         }
                     });
            	}


                 $("#tablPendingCalls").tablesorter();

	        }
	    });

	},
    ShowOrHidePaymentType : function(){
        let voucher_price = $("#IC_VISIT_PRICE").val();
        if(voucher_price > 0)
        {
            $('.PaymentTypesDropdown').css({display : "block"});
        }
        else
        {
            $('.PaymentTypesDropdown').css({display : "none"});
        }
    },
    GetResultRecordInfo : function(){
        let cw_id = $(this).data('cw_id');
        let _token 				= $('input[name=_token]').val();
        let base_url = $("#BASE_URL").val();
        $.ajax
        ({
            url : base_url + "/request/inboundcall/getresultworkflowinfo",
            data : {cw_id : cw_id ,_token : _token },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('input[name=cw_id]').val(response.cw_id);
                $('input[name=cw_creation_date]').val(response.cw_creation_date);
                $('select[name=cw_result_id]').val(response.cw_result_id).trigger('change.select2');
                $('select[name=cw_assigned_to]').val(response.cw_assigned_to).trigger('change.select2');
                $('input[name=cw_result_note]').val(response.cw_result_note);

            }
        });
    },
    ResetValues : function(){
        $('.LstMaintenanceProducts').html("");
        $('input[name=products_stock]').html("");
        $('#FRM_SAVE_VOUCHER').resetForm();
    },
    DisplayProductDescriptionInStockTransfer : function(){
        let  base_url 			= $('input[name=base_url]').val();
        let _token 				= $('input[name=_token]').val();
        let p_id 				= $(this).val();

        $.ajax
        ({
            url : base_url + "/request/stock/getproductinfo",
            data : {p_id : p_id ,_token : _token },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.ProductName').html(response.p_product_name);

            }
        });
    },
    SelectCallRecord : function(){
        $('#LstInboundCalls tr').each((index,item) => {
            $(item).find('input[type=checkbox]').removeAttr('checked');
            $(item).removeClass('SelectedRow');
        })
        $(this).find('input[type=checkbox]').attr('checked',true);
        $(this).addClass('SelectedRow');
        inboundcalls_module.DisplayListofCallResult($(this).data("ic_id"));
    },
    DisplayListofCallResult : function(ic_id){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        $.ajax
        ({
            url : base_url + "/request/call/getlistcallresults",
            data : { _token : _token , ic_id : ic_id },
            method : 'get',
            dataType : "json",
            success : function(response){
                $('#LstMainCallResult').html(response.display);
            }
        });
    },
    AddProductStock : function(){
        let  base_url 			= $('input[name=base_url]').val();
        let _token 				= $('input[name=_token]').val();
        let p_id 				= $("#CP_PRODUCT_ID").val();
        let cp_quantity 				= $("input[name=cp_quantity]").val();
        let products_stock 				= $("input[name=products_stock]").val();

        $.ajax
        ({
            url : base_url + "/request/inboundcall/addproductstock",
            data : {p_id : p_id , cp_quantity : cp_quantity , products_stock : products_stock ,_token : _token },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                let products = $('.LstMaintenanceProducts').html();
                $('.LstMaintenanceProducts').html(products + response.display);
                $('input[name=products_stock]').val(JSON.stringify(response.products_stock));
                $("input[name=cp_quantity]").val('1');
                $("#CP_PRODUCT_ID").val(0).trigger('change.select2');
                $('.ProductName').html('');

            }
        });
    },
    QuickAction : function(){
      var action_type = $(this).data('action_type');

      switch(action_type)
      {
          case "ADD_MAINTENANCE_VOUCHER":
          {
               if($(".checkboxes:checked").length == 0)
                {
                        bootbox.alert("Please select a Call to do any action");
                        return false;
                }
              inboundcalls_module.OpenMaintenanceVoucher();
          }
          break;
          case "ADD_RESULT":
          {
               if($(".checkboxes:checked").length == 0)
                {
                        bootbox.alert("Please select a Call to do any action");
                        return false;
                }
              inboundcalls_module.OpenCallResultPopup();
          }
          break;
          case "DOWNLOAD_PDF_REPORT":
          {
              inboundcalls_module.DownloadPDFReport();
          }
          break;
      }
    },
    DownloadPDFReport : function(){
         var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var general_search = $('input[name=general_search]').val();
	    var ic_archived_call = $('select[name=ic_archived_call]').val();
	    var ic_telemarketing_id = $('select[name=ic_telemarketing_id]').val();
	    var ic_maintenance_type = $('select[name=ic_maintenance_type]').val();
	    var ic_technician_id = $('select[name=ic_technician_id]').val();
	    var ic_result_id = $('select[name=ic_result_id]').val();
	    var ic_call_date = $('input[name=ic_call_date]').val();
             $.ajax
            ({
                url : base_url + "/request/inboundcall/generateanddownloadlist",
                data : { _token : _token , general_search : general_search , ic_archived_call : ic_archived_call ,ic_result_id : ic_result_id, ic_telemarketing_id : ic_telemarketing_id , ic_maintenance_type : ic_maintenance_type , ic_technician_id : ic_technician_id , ic_call_date : ic_call_date},
                method : 'post',
                 xhrFields: {
                    responseType: 'blob' // Set the response type to blob
                },
                success: function(blob, status, xhr) {
                    // Get the filename from the Content-Disposition header if available
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    // Fallback filename if none is provided
                    if (!filename) {
                        filename = "reportlistcalls.pdf";
                    }

                    // Create a temporary link element
                    var link = document.createElement('a');
                    var url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = filename;

                    // Append link to the body
                    document.body.appendChild(link);
                    link.click();

                    // Remove the link and revoke the object URL
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                },
                error: function(xhr, status, error) {
                    console.error("File download failed:", error);
                }
        });
    },
    OpenCallResultPopup : function(){
        var ic_ids = [];
        $(".checkboxes:checked").each(function(){
                var ic_id = $(this).val();
                ic_ids.push(ic_id);
        });
        var str_ic = ic_ids.join(",");
        $("input[name=ic_call_ids]").val(str_ic);
        inboundcalls_module.getListofCallResults();
        $('#CallResultManagement').modal('toggle');
    },
    getListofCallResults : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var ic_id 	= $('#IC_CALL_IDS').val();
        $.ajax
        ({
            url : base_url + "/request/call/getlistcallresults",
            data : { _token : _token , ic_id : ic_id },
            method : 'get',
            dataType : "json",
            success : function(response){
                    $('#LstCallWResults').html(response.display);
            }
        });

    },
    DisplayCallBackDate : function(){
        var cw_result_id 	= $('select[name=cw_result_id]').val();
        if(cw_result_id == 1)
        {
            $('.CallBack').css({'display' : ''});
        }
        else
        {
            $('.CallBack').css({'display' : 'none'});
        }
    },
    SaveCallResultInfo : function(){
         return inboundcalls_module.SaveCallResultSubmitHandler();
    },
    SaveCallResultSubmitHandler : function(){
        var ResForm = $('#FRM_SAVE_RESULTS');
        var error3 = $('.alert-danger', ResForm);
        var success3 = $('.alert-success', ResForm);

        ResForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                cw_creation_date : {
                    required : true
                },
                cw_result_id : {
                    required : true
                },
                cw_assigned_to : {
                    required : true,
                },
                 cw_result_note : {
                    required : true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes

            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success3.hide();
                error3.show();
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
               success3.show();
               error3.hide();
               var base_url = $('#BASE_URL').val();
              // var _token = $('input[name=_token]').val();
               var str_params = $("#FRM_SAVE_RESULTS").serialize();

                $.ajax
               ({
                   url : base_url + "/request/inboundcall/savecallresult",
                   data : str_params,
                   method : 'post',
                   dataType : "json",
                   success : function(response){
                     if(response.is_error == 0)
                     {
                          $('#CW_CREATION_DATE').val('');
                          $('#CW_RESULT_NOTE').val('');
                          $('#CW_ASSIGNED_TO').val('').trigger('change.select2');
                          $('#CW_RESULT_ID').val('').trigger('change.select2');
                          inboundcalls_module.DisplayListInboundCalls();
                          inboundcalls_module.getListofCallResults();
                     }
                   }
               });
            }

        });
    },
    OpenMaintenanceVoucher : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        $.ajax
        ({
            url : base_url + "/request/mvoucher/getnewmaintenancenumber",
            data : { _token : _token },
            method : 'get',
            dataType : "json",
            success : function(response){
                var ic_ids = [];
                $(".checkboxes:checked").each(function(){
                    var ic_id = $(this).val();
                    ic_ids.push(ic_id);
                });
                var str_ic = ic_ids.join(",");
                $("input[name=ic_ids]").val(str_ic);
                $("#IC_CALL_INDEX").val(response.maintenance_number);
                $('#AddMainVoucher').modal('toggle');
            }
        });



    },
    getAccountDealInfo : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var ad_account_code 	= $('#IC_CLIENT_CODE').val();
        if(ad_account_code.length > 0)
        {
            $.ajax
            ({
                url : base_url + "/request/account/getaccountinfobycode",
                data : { _token : _token , ad_account_code : ad_account_code },
                method : 'get',
                dataType : "json",
                success : function(response){
                        $('#CA_ACCOUNT_NAME').val(response.account_info.ca_account_name);
                        $('#FK_CUSTOMER_ID').val(response.account_info.ca_id);
                        $('#CA_ACCOUNT_ADDRESS').val(response.account_info.ca_billing_address);
                        if(response.account_info.ad_deal_code != undefined )
                            $('#IC_CONTRACT_CODE').val(response.account_info.ad_deal_code);
                }
            });
        }

    },
    getDealInfo : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var deal_code 	= $('#IC_CONTRACT_CODE').val();
        $.ajax
        ({
            url : base_url + "/request/deals/getdealinfo",
            data : { _token : _token , deal_code : deal_code },
            method : 'get',
            dataType : "json",
            success : function(response){
                    $('#IC_SALES_ID').val(response.deal_info.fk_sales_id).trigger('change');
                    $('#IC_TELEMARKETING_ID').val(response.deal_info.fk_telemarketing_id).trigger('change');
                    $('#IC_BILL_SITUATION').val(response.deal_info.billing_situation);
                    $('#IC_PRODUCT_MACHINE_ID').val(response.deal_info.ad_serial_number);
            }
        });
    },
    SaveMaintenanceVoucherInfo : function(){
            return inboundcalls_module.SaveMaintenanceVoucherSubmitHandler();
    },
    SaveInboundCallInfo : function(){
            return inboundcalls_module.SaveInboundCallSubmitHandler();
    },
    SaveMaintenanceVoucherSubmitHandler : function(){
        var MVForm = $('#FRM_SAVE_VOUCHER');
        var error3 = $('.alert-danger', MVForm);
        var success3 = $('.alert-success', MVForm);

        MVForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                ic_resolution_date : {
                    required : true
                },
                ic_doc_number : {
                    required : true
                },
                ic_comission : {
                    required : true,
                    number:true
                },
            },

            messages: { // custom messages for radio buttons and checkboxes

            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success3.hide();
                error3.show();
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
               success3.show();
               error3.hide();
               var base_url = $('#BASE_URL').val();
              // var _token = $('input[name=_token]').val();
               var str_params = $("#FRM_SAVE_VOUCHER").serialize();

                $.ajax
               ({
                   url : base_url + "/request/inboundcall/savemv",
                   data : str_params,
                   method : 'post',
                   dataType : "json",
                   success : function(response){
                     if(response.is_error == 0)
                     {
                            $('input[name=ic_resolution_date]').val('');
                            $('input[name=ic_doc_number]').val('');
                            $('input[name=ic_call_index]').val('');
                            $('input[name=ic_comission]').val('');
                            $('input[name=ic_visit_price]').val('')
                            $('select[name=ic_currency_id]').val('').trigger('change.select2');
                            $('select[name=ic_payment_type]').val('').trigger('change.select2');
                            $('select[name=ic_payment_type]').val('').trigger('change.select2');
                            $('select[name=cp_product_id]').val('').trigger('change.select2');
                            $('#FRM_SAVE_VOUCHER').resetForm();
                            $('.LstMaintenanceProducts').html('');
                           $('#AddMainVoucher').modal('toggle');
                     }
                   }
               });
            }

        });
    },
    SaveInboundCallSubmitHandler : function(){
             var InboundCallForm = $('#FORM_SAVE_INBOUND');
        var error3 = $('.alert-danger', InboundCallForm);
        var success3 = $('.alert-success', InboundCallForm);

        InboundCallForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {

            },

            messages: { // custom messages for radio buttons and checkboxes

            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent(".input-group").length > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.radio-list').length > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').length > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').length > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').length > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                success3.hide();
                error3.show();
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
               success3.show();
               error3.hide();
               var base_url = $('#BASE_URL').val();
              // var _token = $('input[name=_token]').val();
               var str_params = $("#FORM_SAVE_INBOUND").serialize();

               const ic_call_outcome = $.outcome_desc.getData();
               const ic_notes = $.notes_desc.getData();
               const ic_item_problem = $.itemprob_desc.getData();

               str_params = str_params + "&ic_call_outcome=" + ic_call_outcome;
               str_params = str_params + "&ic_notes=" + ic_notes;
               str_params = str_params + "&ic_item_problem=" + ic_item_problem;
                $.ajax
               ({
                   url : base_url + "/request/inboundcall/saveinfo",
                   data : str_params,
                   method : 'post',
                   dataType : "json",
                   success : function(response){
                     if(response.is_error == 0)
                     {
                        window.location.href = base_url + "/callcenter/inboundcall";
                     }
                   }
               });
            }

        });
    },
    DeleteInboundCallData : function(){
             var ic_id = $(this).data('ic_id');
            bootbox.confirm("Are you sure you want to delete ?", function(result){
                    //result
                    if(result == true)
                    {
                          var base_url = $('#BASE_URL').val();
                          var _token = $('input[name=_token]').val();
                            var str_params ={ic_id : ic_id , _token : _token};
                             $.ajax
                            ({
                                url : base_url + "/request/inboundcall/deleteinfo",
                                data : str_params,
                                dataType : "Json",
                                type : "delete",
                                success : function(response){
                                  if(response.is_error == 0)
                                  {
                                        inboundcalls_module.DisplayListInboundCalls();
                                  }
                                }
                            });
                    }
            });
    },
    EditInboundCallInfo : function(){
            var ic_id = $(this).data('ic_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/callcenter/inboundcall/editform/" + ic_id;
    }
};
