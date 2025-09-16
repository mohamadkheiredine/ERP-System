/**
 *
 */
vouchers_module = {
	displayListPayments : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var pv_account_payable 		= $('select[name=pv_account_payable]').val();
		var pv_account_receivable 	= $('select[name=pv_account_receivable]').val();
		var pv_start_date 	= $('input[name=pv_start_date]').val();
		var pv_end_date 	= $('input[name=pv_end_date]').val();
		var page_number 	= $('input[name=page_number]').val();
	    var general_search 	= $('input[name=general_search]').val();
		var fisical_year 			= $('input[name=fisical_year]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/billing/displaylistpayments",
	        data : { _token : _token , page_number : page_number ,fisical_year : fisical_year , general_search : general_search ,  pv_account_payable : pv_account_payable , pv_account_receivable : pv_account_receivable , pv_start_date : pv_start_date , pv_end_date : pv_end_date },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstPaymentVouchers').html(response.display);
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
	        		  $.pagination = $('#VouchersPagination').twbsPagination({
	                       totalPages: response.total_pages,
	                       visiblePages: 7,
	                       onPageClick: function (event, page) {
	                            $('input[name=page_number]').val(page);
	                            vouchers_module.displayListPayments();
	                       }
	                   });
        		  }


				$('#LstPaymentVouchers').on('click',"a[id*=EDIT_PV_]",vouchers_module.EditPaymentVoucherInfo);
				$('#LstPaymentVouchers').on('click',"a[id*=DELETE_PV_]",vouchers_module.DeletePaymentVoucherData);
	        }
	    });
	},
    SelectedVoucherRecord : function(){
        $('#LstPaymentVouchers tr').each((index,item) => {
            $(item).find('input[type=checkbox]').removeAttr('checked');
            $(item).removeClass('SelectedRow');
        })
        $(this).find('input[type=checkbox]').attr('checked',true);
        $(this).addClass('SelectedRow');
        $("input[name=pv_ids]").val($(this).find('input[type=checkbox]').val());
    },
        displayListOnepagerPayments : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var pv_account_payable 		= $('select[name=account_payable]').val();
		var pv_account_receivable 	= $('select[name=account_receivable]').val();
		var pv_start_date 	= $('input[name=start_date]').val();
		var pv_end_date 	= $('input[name=end_date]').val();
		var page_number 	= $('input[name=page_number]').val();
	    var general_search 	= $('input[name=general_search]').val();
		var fisical_year 			= $('input[name=fisical_year]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/billing/displaylistonepagerpayments",
	        data : { _token : _token , page_number : page_number ,fisical_year : fisical_year , general_search : general_search ,  pv_account_payable : pv_account_payable , pv_account_receivable : pv_account_receivable , pv_start_date : pv_start_date , pv_end_date : pv_end_date },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstPaymentVouchers').html(response.display);
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
	        		  $.pagination = $('#VouchersPagination').twbsPagination({
	                       totalPages: response.total_pages,
	                       visiblePages: 7,
	                       onPageClick: function (event, page) {
	                            $('input[name=page_number]').val(page);
	                            vouchers_module.displayListOnepagerPayments();
	                       }
	                   });
        		  }


				$('#LstPaymentVouchers').on('click',"a[id*=EDIT_PV_]",vouchers_module.EditPaymentVoucherInfo);
				$('#LstPaymentVouchers').on('click',"a[id*=DELETE_PV_]",vouchers_module.DeletePaymentVoucherData);
	        }
	    });
	},
         GetSelectedVoucherInfo : function(){
                var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var pv_id = $(this).parent().data('pv_id');
        bootbox.confirm('are you sure you want to get this Payment Voucher info ? ',
        function(result) {
            if(result == true)
            {
                 $.ajax
            ({
                url : base_url + "/request/voucher/getselectedvoucher",
                data : { _token : _token , pv_id : pv_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
                success : function(response){
                    $('form[name=frm_save_voucher]').find('input[name=pv_id]').val(response.voucher_obj.pv_id);
                    $('form[name=frm_save_voucher]').find('input[name=pv_code]').val(response.voucher_obj.pv_code);
                    $('form[name=frm_save_voucher]').find('input[name=pv_voucher_label]').val(response.voucher_obj.pv_voucher_label);
                    $('form[name=frm_save_voucher]').find('input[name=pv_creation_date]').val(response.voucher_obj.pv_creation_date);
                    $('form[name=frm_save_voucher]').find('input[name=pv_payment_amount]').val(response.voucher_obj.pv_payment_amount);
                    $('form[name=frm_save_voucher]').find('input[name=pv_exchange_rate]').val(response.voucher_obj.pv_exchange_rate);
                    $('form[name=frm_save_voucher]').find('textarea[name=pv_voucher_description]').val(response.voucher_obj.pv_voucher_description);
                    $('form[name=frm_save_voucher]').find('select[name=pv_account_payable]').val(response.voucher_obj.pv_account_payable).trigger('change');
                    $('form[name=frm_save_voucher]').find('select[name=pv_account_receivable]').val(response.voucher_obj.pv_account_receivable).trigger('change');
                    $('form[name=frm_save_voucher]').find('select[name=pv_currency_id]').val(response.voucher_obj.pv_currency_id).trigger('change');
                    $('form[name=frm_save_voucher]').find('select[name=pv_sec_currency_id]').val(response.voucher_obj.pv_sec_currency_id).trigger('change');
                }
            });
            }

        });
    },
    QuickActionExecution : function(){
      let action_type  = $(this).data('action_type');
      switch(action_type)
      {
          case "PRINT":
          {
              let pv_ids = $('input[name=pv_ids]').val();
              let base_url = $('#BASE_URL').val();
              let url = base_url + "/billing/downloadvoucher/" + pv_ids
              window.open(url, '_blank').focus();

          }
          break;
      }
    },
        CalculateSecondaryAmountValue : function(){
            var pv_payment_amount = $('input[name=pv_payment_amount]').val();
            var pv_exchange_rate = $('input[name=pv_exchange_rate]').val();
            var secondary_currency_amount = pv_payment_amount * pv_exchange_rate;
            $('input[name=pv_amount_secondary_amount]').val(secondary_currency_amount.toFixed(2))
        },
        GenerateVoucherCode : function(){
             var _token 	= $("input[name=_token]").val();
		var base_url 	= $('input[name=base_url]').val();
		$.ajax
		({
                    url : base_url + "/request/billing/getvouchercode",
                    data : { _token : _token},
                    method : 'get',
                    dataType : "json",
                    beforeSend : function(){
                    },
                    success : function(response){
                        $("#PV_CODE").val(response.voucher_code);
                    }
		});
        },
	AddVoucherExtension : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/vouchers/displayextensionrow",
	        data : { _token : _token},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('.LstExtensionVouchers').append(response.display);
	        	$('select.ExtensionAccount').select2();
	       	 	$('select.ExtensionCurrency').select2();
	        }
	    });
	},
	DeleteExtRow : function(){
		$(this).parents('tr').remove();
	},
        QuickAction : function(){
            let action_type = $(this).data('action_type');

            switch(action_type)
            {
                case "DOWNLOAD":
                {
	                            vouchers_module.DownloadPaymentVoucher();

                }
                break;
            }
        },
        DownloadPaymentVoucher : function(){
            var pv_ids = [];
			$(".checkboxes:checked").each(function(){
				var pv_id = $(this).val();
				pv_ids.push(pv_id);
			});
			var str_pv = pv_ids.join(",");
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
                let url = base_url + "/billing/downloadvoucher/" + str_pv;
                window.open(url, '_blank').focus();
        },
	EditExtRow : function(){
		var ve_id = $(this).data('ve_id');
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		$.container_row = $(this).parents('tr.extrow');
		 $.ajax
		    ({
		        url : base_url + "/request/vouchers/viewextensionrow",
		        data : { _token : _token , ve_id : ve_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$.container_row.html(response.display);

					$("button[id*=BTN_SAVE_EXTENSION_]").on('click',vouchers_module.SaveVoucherExtension);
		        }
		    });
	},
	DisplayListExtensions : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var pv_id 		= $('input[name=pv_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/vouchers/displaylistextensions",
	        data : { _token : _token , pv_id : pv_id},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('.LstExtensionVouchers').html(response.display);

				$("a[id*=EDIT_EXTENSION_]").on('click',vouchers_module.EditVoucherExtension);
				$("a[id*=DELETE_EXTENSION_]").on('click',vouchers_module.DeleteVoucherExtension);
	        }
	    });
	},
	SaveVoucherExtension :function(){
		var ve_id = $(this).parents('tr').find('input[name=ve_id]').val();
		var ve_extention_account_id = $(this).parents('tr').find('select[name=ve_extention_account_id]').val();
		var ve_extension_currency 	= $(this).parents('tr').find('select[name=ve_extension_currency]').val();
		var ve_extension_amount 	= $(this).parents('tr').find('input[name=ve_extension_amount]').val();
		var ve_extension_notes 		= $(this).parents('tr').find('input[name=ve_extension_notes]').val();
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();

		var params = { ve_id : ve_id , _token : _token , ve_extention_account_id : ve_extention_account_id , ve_extension_currency : ve_extension_currency , ve_extension_amount : ve_extension_amount , ve_extension_notes : ve_extension_notes };
		 $.ajax
		    ({
		        url : base_url + "/request/vouchers/saveextensionrow",
		        data : params,
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		        	vouchers_module.DisplayListExtensions();
		        }
		    });

	},
	EditVoucherExtension : function(){

	},
	DeleteVoucherExtension : function(){

	},
	SavePaymentVoucherInfo : function(){
		return vouchers_module.SavePaymentVoucherSubmitHandler();
	},
	SavePaymentVoucherSubmitHandler : function(){
		 var VoucherFOrm = $('#FORM_SAVE_VOUCHER');
         var error3 = $('.alert-danger', VoucherFOrm);
         var success3 = $('.alert-success', VoucherFOrm);

         VoucherFOrm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 pv_voucher_label : {
            		 required: true
            	 },
            	 pv_user_id : {
            		 required: true
            	 },
            	 pv_account_payable : {
            		 required: true
            	 },
            	 pv_account_receivable : {
            		 required: true,
            	 },
            	 pv_payment_amount : {
            		 required: true,
            		 number : true
            	 },
            	 pv_currency_id : {
            		 required: true
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

    	        var str_params = $("#FORM_SAVE_VOUCHER").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/billing/savepayvoucherinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/billing/vouchers";
    	              }
    	            }
    	        });
             }

         });
	},
	DeletePaymentVoucherData : function(){
		 var pv_id = $(this).data('pv_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={pv_id : pv_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/billing/deletepayvoucherinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.pv_datatable.destroy();
			            	  vouchers_module.displayListPayments();
			              }
			            }
			        });
			}
		});
	},
	EditPaymentVoucherInfo : function(){
		var pv_id = $(this).data('pv_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/billing/vouchers/editform/" + pv_id;
	}
};
