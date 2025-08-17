bills_module = {
	DisplayListBills : function(){
	    var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var page_number = $('input[name=page_number]').val();
	    var general_search = $('input[name=general_search]').val();
	    var pi_start_date = $('input[name=pi_start_date]').val();
	    var pi_end_date = $('input[name=pi_end_date]').val();
	    var pi_upto_date = $('input[name=pi_upto_date]').val();
        if( ( pi_start_date != '' &&  pi_end_date != '' ) || pi_upto_date != '' )
        {
            $.ajax
            ({
                url : base_url + "/request/billing/displaylistbills",
                data : { _token : _token , page_number : page_number , general_search : general_search , pi_start_date : pi_start_date , pi_end_date : pi_end_date , pi_upto_date : pi_upto_date  },
                method : 'get',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                    $('#LstBills').html(response.display);
                    $('.group-checkable').change(function() {
                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                        var checked = $(this).prop("checked");
                        $(set).each(function() {
                            $(this).prop("checked", checked);
                        });
                        $.uniform.update(set);
                    });

                    // if ($('#BillsPagination').data('twbs-pagination')) {
                    //     $('#BillsPagination').twbsPagination('destroy');
                    // }


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

                }
            });
        }


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
