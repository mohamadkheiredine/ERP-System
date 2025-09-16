/**
 *
 */

receipts_module = {
		DisplayListInvoiceReceipts : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var bi_id 				= $('input[name=bi_id]').val();
			var page_number 				= $('input[name=page_number]').val();
			$.ajax
			({
				url : base_url + "/request/billing/displaylistreceiptsinvoice",
				data : { _token : _token , bi_id : bi_id},
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstReceipts').html(response.display);
				}
			});
		},
                GenerateReceiptCode : function(){
                    	var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();

                    $.ajax
		    ({
		        url : base_url + "/request/billing/generatereceiptcode",
		        data : { _token : _token  },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('input[name=br_receipt_number]').html(response.receipt_code);
		        }
		    });
                },
		displayListReceipts : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    var page_number = $('input[name=page_number]').val();
		    var search_query = $('input[name=search_query]').val();
		    var start_date	 = $('input[name=start_date]').val();
		    var end_date	 = $('input[name=end_date]').val();
		    var receipt_customer	 = $('#RECEIPT_CUSTOMER').val();
		    var receipt_invoice	 = $('#RECEIPT_INVOICE').val();
			var fisical_year 			= $('input[name=fisical_year]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/receipts/displaylist",
		        data : { _token : _token , page_number : page_number , fisical_year : fisical_year , search_query : search_query , start_date : start_date , end_date : end_date ,receipt_customer : receipt_customer , receipt_invoice : receipt_invoice },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstReceiptsGrid').html(response.display);
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
                                 $.pagination = $('#ReceiptsPagination').twbsPagination({
                                     totalPages: response.total_pages,
                                     visiblePages: 7,
                                     onPageClick: function (event, page) {
                                          $('input[name=page_number]').val(page);
                                          receipts_module.displayListReceipts();
                                     }
                                 });
                        	 }

		        }
		    });
		},
                NewReceipt : function(){
                     $('button[name=btn_reset]').trigger('click');
                                  receipts_module.GenerateReceiptCode();
                },
                GetSelectedReceiptInfo : function(){
                            var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
                    var br_id = $(this).parent().data('br_id');
                    bootbox.confirm('are you sure you want to get this receipt info ? ',
                    function(result) {
                        if(result == true)
                        {
                             $.ajax
                        ({
                            url : base_url + "/request/receipts/getselectedreceipt",
                            data : { _token : _token , br_id : br_id },
                        method : 'get',
                        dataType : "json",
                        beforeSend : function(){
                        },
                            success : function(response){
                                $('form[name=form_save_receipt]').find('input[name=br_id]').val(response.receipt_obj.br_id);
                                $('form[name=form_save_receipt]').find('input[name=br_receipt_number]').val(response.receipt_obj.br_receipt_number);
                                $('form[name=form_save_receipt]').find('input[name=br_receipt_label]').val(response.receipt_obj.br_receipt_label);
                                $('form[name=form_save_receipt]').find('input[name=br_receipt_date]').val(response.receipt_obj.br_receipt_date);
                                $('form[name=form_save_receipt]').find('input[name=br_payment_value]').val(response.receipt_obj.br_payment_value);
                                $('form[name=form_save_receipt]').find('input[name=br_exchange_rate]').val(response.receipt_obj.br_exchange_rate);
                                $('form[name=form_save_receipt]').find('textarea[name=br_receipt_note]').val(response.receipt_obj.br_receipt_note);
                                $('form[name=form_save_receipt]').find('select[name=br_account_id]').val(response.receipt_obj.br_account_id).trigger('change');
                                $('form[name=form_save_receipt]').find('select[name=br_client_id]').val(response.receipt_obj.br_client_id).trigger('change');
                                $('form[name=form_save_receipt]').find('select[name=br_payment_type]').val(response.receipt_obj.br_payment_type).trigger('change');
                                $('form[name=form_save_receipt]').find('select[name=br_receipt_currency]').val(response.receipt_obj.br_receipt_currency).trigger('change');
                                $('form[name=form_save_receipt]').find('select[name=br_second_currency_id]').val(response.receipt_obj.br_second_currency_id).trigger('change');
                            }
                        });
                        }

                    });
                },
                displayListOnePageReceipts : function(){
                    	var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    var page_number = $('input[name=page_number]').val();
		    var search_query = $('input[name=search_query]').val();
		    var start_date	 = $('input[name=start_date]').val();
		    var end_date	 = $('input[name=end_date]').val();
		    var receipt_customer	 = $('#RECEIPT_CUSTOMER').val();
		    var receipt_invoice	 = $('#RECEIPT_INVOICE').val();
			var fisical_year 			= $('input[name=fisical_year]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/receipts/displayactivelist",
		        data : { _token : _token , page_number : page_number , fisical_year : fisical_year , search_query : search_query , start_date : start_date , end_date : end_date ,receipt_customer : receipt_customer , receipt_invoice : receipt_invoice },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstOneReceiptsGrid').html(response.display);
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
                                 $.pagination = $('#ReceiptsPagination').twbsPagination({
                                     totalPages: response.total_pages,
                                     visiblePages: 7,
                                     onPageClick: function (event, page) {
                                          $('input[name=page_number]').val(page);
                                          receipts_module.displayListOnePageReceipts();
                                     }
                                 });
                        	 }

		        }
		    });
                },
		GenerateReceiptCode : function(selected_date){
			var _token 		= $("input[name=_token]").val();
			var base_url 	= $('input[name=base_url]').val();
			 var br_id 		= $('input[name=br_id]').val();


			$.ajax
			({
				url : base_url + "/request/billing/generatecode",
				data : { _token : _token , selected_date : selected_date , type : "receipts" },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$("#BR_RECEIPT_NUMBER").val(response.code);
				}
			});

		},
                QuickSaveReceiptInfo : function(){
			return receipts_module.QuickSaveReceiptSubmitHandler();
		},
		QuickSaveReceiptSubmitHandler : function(){
                    var ReceiptForm = $('#FORM_SAVE_RECEIPT');

			ReceiptForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 br_account_id : {
	                     required: true
	                 },
	                 fk_payment_type : {
	                     required: true
	                 },
	                 br_receipt_label : {
	                     required: true
	                 },
	                 br_payment_value : {
	                	 required: true,
	                     number : true
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
	                 //success3.hide();
	                 //error3.show();
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
			$("#BTN_SAVE_RECEIPT").attr("disabled","disabled");
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FORM_SAVE_RECEIPT").serialize();
	    	         $.ajax({
	    	            url : base_url + "/request/billing/savereceiptinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
                              if(response.is_error == 0)
                              {
                                  $('button[name=btn_reset]').trigger('click');
                                  receipts_module.GenerateReceiptCode();
                              }
	    	            }
	    	        });
	             }

	         });
                },
		SaveReceiptInfo : function(){
			return receipts_module.SaveReceiptSubmitHandler();
		},
		SaveReceiptSubmitHandler : function(){
			var ReceiptForm = $('#FORM_SAVE_RECEIPT');

			ReceiptForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 br_account_id : {
	                     required: true
	                 },
	                 fk_payment_type : {
	                     required: true
	                 },
	                 br_receipt_label : {
	                     required: true
	                 },
	                 br_payment_value : {
	                	 required: true,
	                     number : true
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
	                 //success3.hide();
	                 //error3.show();
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
					 $("#BTN_SAVE_RECEIPT").attr("disabled","disabled");
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FORM_SAVE_RECEIPT").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/savereceiptinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  var invoice_redirect = $('input[name=invoice_redirect]').val();
	    	            	  var invoice_id = $('select[name=fk_invoice_id]').val();

                              window.location.href = base_url + "/billing/receipts";

	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		EditReceiptForm : function(){
			var br_id = $(this).parents('tr').data('br_id');
			 var base_url = $('#BASE_URL').val();
			window,location.href=base_url + "/billing/receipts/editform/" + br_id;
		},
		DeleteReceiptForm : function(){
			var br_id = $(this).parents('tr').data('br_id');
			 var base_url = $('#BASE_URL').val();
		      var _token = $('input[name=_token]').val();


		      Swal.fire({
		    	  title: 'Are you sure you want to delete ?',
		    	  text: "You won't be able to revert this!",
		    	  icon: '',
		    	  showCancelButton: true,
		    	  confirmButtonColor: '#3085d6',
		    	  cancelButtonColor: '#d33',
		    	  confirmButtonText: 'Yes, delete it!'
		    	}).then((result) => {
		    	  if (result.isConfirmed) {
		    		  var str_params ={br_id : br_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/billing/deletereceiptinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
			            	  Swal.fire(
			    		    	      'Deleted!',
			    		    	      'Your file has been deleted.',
			    		    	      'success'
			    		    	    );
			            	  	receipts_module.displayListReceipts();
				              }
				            }
				        });


		    	  }
		    	})

		},
         CalculateSecondaryAmountValue : function(){
            var br_payment_amount = $('input[name=br_payment_value]').val();
            var br_exchange_rate = $('input[name=br_exchange_rate]').val();
            var secondary_currency_amount = br_payment_amount * br_exchange_rate;
            $('input[name=br_amount_secondary_amount]').val(secondary_currency_amount.toFixed(2))
        }
};
