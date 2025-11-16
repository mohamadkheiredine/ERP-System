bills_module = {
	DisplayListBills : function(){
	    var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var page_number = $('input[name=page_number]').val();
	    var general_search = $('input[name=general_search]').val();
	    var pi_start_date = $('input[name=pi_start_date]').val();
	    var pi_end_date = $('input[name=pi_end_date]').val();
	    var pi_upto_date = $('input[name=pi_upto_date]').val();
	    var ip_payment_status = $('select[name=ip_payment_status]').val();
	    var bill_region = $('select[name=bill_region]').val();
	    var bill_area = $('select[name=bill_area]').val();
        if( ( pi_start_date != '' &&  pi_end_date != '' ) || pi_upto_date != '' )
        {
            $.ajax
            ({
                url : base_url + "/request/billing/displaylistbills",
                data : { _token : _token , page_number : page_number , bill_area : bill_area , bill_region : bill_region , general_search : general_search , ip_payment_status : ip_payment_status , pi_start_date : pi_start_date , pi_end_date : pi_end_date , pi_upto_date : pi_upto_date  },
                method : 'get',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                    $('#LstBills').html(response.display);
                    $('#total_amount').html(response.total_amount);
                    $('.group-checkable').change(function() {
                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                        var checked = $(this).prop("checked");
                        $(set).each(function() {
                            $(this).prop("checked", checked);
                        });
                        $.uniform.update(set);
                    });

                    if(response.total_pages > 0)
                    {
                        $('input[name=page_number]').val(1);
                        $.pagination = $('#BillsPagination').twbsPagination({
                            totalPages: response.total_pages,
                            visiblePages: 7,
                            onPageClick: function (event, page) {
                                $('input[name=page_number]').val(page);
                                bills_module.DisplayListBills();
                            }
                        });
                    }


                    $("#tableBillsManagement").tablesorter();

                }
            });
        }
	},
    GetRegionArea : function(){
        var base_url 			= $('input[name=base_url]').val();
        var _token	 			= $('input[name=_token]').val();
        var bill_area	 	= $(this).val();
        var params = { _token : _token , bill_area : bill_area };
        $.ajax
        ({
            url : base_url + "/request/bills/getregionarea",
            data : params,
            dataType : "json",
            type : "POST",
            success : function(response){
                $('#REGION_DROPDOWN').html(response.dropdown);
                $('select[name=bill_region]').select2();
                bills_module.DisplayListBills();
            }
        });
    },
    DisplayListofBillResult : function(ip_id){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        $.ajax
        ({
            url : base_url + "/request/bills/getlistbillresults",
            data : { _token : _token , ip_id : ip_id },
            method : 'get',
            dataType : "json",
            success : function(response){
                $('#LstBillsCallResult').html(response.display);
            }
        });
    },
    DisplayListWBillResult : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var ip_id 		= $('input[name=ip_bill_id]').val();
        $.ajax
        ({
            url : base_url + "/request/bills/getlistbillresults",
            data : { _token : _token , ip_id : ip_id },
            method : 'get',
            dataType : "json",
            success : function(response){
                $('#LstCallWResults').html(response.display);
            }
        });
    },
    SelectBillRecord : function(){
        $('#LstBills tr').each((index,item) => {
            $(item).find('input[type=checkbox]').removeAttr('checked');
            $(item).removeClass('SelectedRow');
        })
        $(this).find('input[type=checkbox]').attr('checked',true);
        $(this).addClass('SelectedRow');
        bills_module.DisplayListofBillResult($(this).data("ip_id"));
    },
    QuickActionBills : function(){
        let action_type = $(this).data('action_type');
        switch (action_type)
        {
            case "DOWNLOAD_EXCEL_REPORT":
            {
                bills_module.DownloadExcelReport();
            }
            break;
            case "ADD_RESULT":
            {
                bills_module.OpenCallResultPopup();
            }
            break;
        }
    },
    OpenCallResultPopup : function(){
        var ip_ids = [];
        $(".checkboxes:checked").each(function(){
            var ip_id = $(this).val();
            ip_ids.push(ip_id);
        });
        var str_ip = ip_ids.join(",");
        $("input[name=ip_bill_id]").val(str_ip);
        bills_module.DisplayListWBillResult(str_ip);
        $('#BillResultManagement').modal('toggle');
    },
    DownloadExcelReport : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var general_search = $('input[name=general_search]').val();
        var pi_start_date = $('input[name=pi_start_date]').val();
        var pi_end_date = $('input[name=pi_end_date]').val();
        var pi_upto_date = $('input[name=pi_upto_date]').val();
        var ip_payment_status = $('select[name=ip_payment_status]').val();
        $.ajax({
            url: base_url + "/request/bills/downloadbillsreport",
            method: "GET",
            success: function(data) {

                const blob = new Blob([data]);
                // Create a Blob URL for the binary data
                var blobUrl = window.URL.createObjectURL(blob);
                // Create a temporary anchor element
                var a = document.createElement('a');
                a.href = blobUrl;
                a.download = 'bills-management.csv'; // Set the desired file name

                // Programmatically trigger a click on the anchor to start the download
                document.body.appendChild(a);
                a.click();

                // Clean up resources
                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);
            },
            error: function(xhr, status, error) {
                console.error("Error downloading file:", error);
            }
        });

    },
    DisplayCallBackDate : function(){
        var bw_result_id 	= $('select[name=bw_result_id]').val();
        if(bw_result_id == 1)
        {
            $('.CallBack').css({'display' : ''});
        }
        else
        {
            $('.CallBack').css({'display' : 'none'});
        }
    },
    SaveCallResultInfo : function(){

        return bills_module.SaveCallResultInfoSubmitHandler();
    },
    SaveCallResultInfoSubmitHandler : function(){
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
                    url : base_url + "/request/bills/savecallbillresult",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            $('#BW_CREATION_DATE').val('');
                            $('#BW_RESULT_NOTE').val('');
                            $('#BW_ASSIGNED_TO').val('').trigger('change.select2');
                            $('#BW_RESULT_ID').val('').trigger('change.select2');
                            bills_module.DisplayListBills();
                            bills_module.DisplayListWBillResult();
                        }
                    }
                });
            }

        });
    },
    CalculateRemainingAmount : function(){
        let ip_payment_amount = $("input[name=ip_payment_amount]").val();
        let ip_remaining_amount = $('input[name=ip_remaining_amount]').val();
        let ip_paid_amount = $('input[name=ip_paid_amount]').val();
        let ip_initial_amount = $('input[name=ip_initial_amount]').val();

       // $('input[name=ip_paid_amount]').val(ip_payment_amount);


        let remaining_amount = parseInt(ip_initial_amount) - ( parseInt(ip_paid_amount) + parseInt(ip_payment_amount) );
        console.log('ip_initial_amount',ip_initial_amount);
        console.log('ip_paid_amount',ip_paid_amount);
        console.log('ip_payment_amount',ip_payment_amount);

        $('.PaidAmount').html(parseInt(ip_paid_amount) + parseInt(ip_payment_amount));
        if( remaining_amount >= 0 )
        {
            $('input[name=ip_remaining_amount]').val(remaining_amount);
            $('.RemainingAmount').html(remaining_amount);
        }
        else {
            $('input[name=ip_extra_amount]').val(remaining_amount * (-1));
            $('input[name=ip_remaining_amount]').val(0);
            $('.RemainingAmount').html(0);
        }

    },
    ValidateBillPaymentToPay : function() {
        if ($(this).is(':checked')) {
            console.log('Checkbox is checked');
            let ip_remaining_amount = $('input[name=ip_remaining_amount]').val();
            if(ip_remaining_amount > 0)
            {
                $(this).removeAttr('checked');
                $(this).trigger('click');
            }
        }
    },
        getclientinfo : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var client_code 	= $('#IP_CLIENT_CODE').val();
            if(client_code != '')
            {
                $.ajax
                ({
                    url : base_url + "/request/account/getaccountinfobycode",
                    data : { _token : _token , ad_account_code : client_code },
                    method : 'get',
                    dataType : "json",
                    success : function(response){
                            $('#IP_CLIENT_NAME').val(response.account_info.ca_account_name);
                            $('input[name=ip_client_id]').val(response.account_info.ca_id);
                    }
                });
            }
        },
	SaveBillInfo : function(){
        $('input[name=ip_paid_amount]').val($('.PaidAmount').html());
		return bills_module.SaveBillSubmitHandler();
	},
	SaveBillSubmitHandler : function(){
		 var BillsForm = $('#FRM_SAVE_BILLS');
         var error3 = $('.alert-danger', BillsForm);
         var success3 = $('.alert-success', BillsForm);

         BillsForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 ip_billing_nbr : {
            		required: true
            	 },
                 ip_payment_amount : {
                     number : true,
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
    	        var str_params = $("#FRM_SAVE_BILLS").serialize();
                 $('#BTN_SAVE_BILLS').attr('disabled','disabled');

    	         $.ajax
    	        ({
    	            url : base_url + "/request/billing/savebillsinfo",
    	            data : str_params,
    	            method : 'put',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/billing/bills";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteBillsData : function(){
		 var ip_id = $(this).data('ip_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={ip_id : ip_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/billing/deletebillsinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "delete",
			            success : function(response){
			              if(response.is_error == 0)
			              {
                                        bills_module.DisplayListBills();
			              }
			            }
			        });
			}
		});
	},
	EditBillInfo : function(){
		var ip_id = $(this).data('ip_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/billing/bills/editform/" + ip_id;
	}
};
