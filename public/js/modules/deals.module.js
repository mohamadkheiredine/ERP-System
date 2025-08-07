/**
 *
 */

deals_module = {
	displayListDeals : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var ad_account 		= $('select[name=ad_account]').val();
            var page_number 		= $('input[name=page_number]').val();
            $.ajax
            ({
                url : base_url + "/request/deals/displaylistdeals",
                data : { _token : _token , ad_account : ad_account , page_number : page_number },
                method : 'post',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                        $('#LstAccountDeals').html(response.display);
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
                                 $('#DealsPagination').twbsPagination({
                                    totalPages: response.total_pages,
                                    visiblePages: 7,
                                    onPageClick: function (event, page) {
                                         $('input[name=page_number]').val(page);
                                         deals_module.displayListDeals();
                                    }
                                });
                         }
                }
            });
	},
        CalculateRemainingAmount : function(){
            var ad_deal_amount 	= $('input[name=ad_deal_amount]').val();
            var ad_down_payment 	= $('input[name=ad_down_payment]').val();

            let remaining_payment = ad_deal_amount - ad_down_payment;
           $('input[name=ad_remaining_payment]').val(remaining_payment);
        },
        ChangeContractType : function(){
            var contract_type = $(this).val();
            if(contract_type == 1)
            {
                $('.DownPaymentHolder').css({display : "none"});
                $('.NumberofPaymentHolder').css({display : "none"});
                $('.RemainingPaymentHolder').css({display : "none"});
                $('#AD_NBR_OF_PAYMENT').val(1);
                $('.LabelBill').html("Date of Payment <span class='required'> * </span>");
            }
            else
            {
                $('.DownPaymentHolder').css({display : ""});
                $('.NumberofPaymentHolder').css({display : ""});
                $('.RemainingPaymentHolder').css({display : ""});
                $('.LabelBill').html("First Bill Date <span class='required'> * </span>")
            }
        },
        getAccountDealInfo : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var ad_account_code 	= $('#AD_ACCOUNT_CODE').val();
            if(ad_account_code != '')
            {
                $.ajax
                ({
                    url : base_url + "/request/account/getaccountinfobycode",
                    data : { _token : _token , ad_account_code : ad_account_code },
                    method : 'get',
                    dataType : "json",
                    success : function(response){
                            $('#AD_CLIENT_NAME').val(response.account_info.ca_account_name);
                            $('#FK_ACCOUNT_ID').val(response.account_info.ca_id);
                            $('#ad_deal_types').html(response.account_info.ct_contract_type);
                    }
                });
            }
        },
        GenerateContractPayment : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var ad_deal_amount 		= $('#AD_DEAL_AMOUNT').val();
            var ad_id 		= $('input[name=ad_id]').val();
            var ad_down_payment 		= $('#AD_DOWN_PAYMENT').val();
            var ad_nbr_of_payment 		= $('#AD_NBR_OF_PAYMENT').val();
            var ad_first_bill_date 		= $('#AD_FIRST_BILL_DATE').val();
             $.ajax
            ({
                url : base_url + "/request/account/generatedealpaymentspreview",
                data : { _token : _token , ad_id : ad_id , ad_first_bill_date : ad_first_bill_date  , ad_deal_amount : ad_deal_amount ,  ad_down_payment : ad_down_payment , ad_nbr_of_payment : ad_nbr_of_payment },
                method : 'post',
                dataType : "json",
                success : function(response){
                     $('#LstPaymentStatments').html(response.display);
                     $('.BillsCom').html(response.billscoms);
                }
            });
        },
        QuickActionDeals : function(){
            let action_type = $(this).data('action_type');
            switch(action_type)
            {
                case "DOWNLOAD_CONTRACT":
                {
                 deals_module.GenerateAndDownloadContract();
                }
                break;
            }
        },
        GenerateAndDownloadContract : function(){
            var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    var ad_id 		= $('input[name=ad_id]').val();
             $.ajax
		    ({
		        url : base_url + "/request/deals/generatecontract",
		        data : { _token : _token , ad_id : ad_id },
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
                                filename = "downloaded_file.pdf";
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
        AssignProductDeal : function(){
            let selected_product = $("#P_PRODUCT_DEAL").val();
            	var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
             $.ajax
		    ({
		        url : base_url + "/request/deals/getproductinfo",
		        data : { _token : _token , product_id : selected_product },
	            method : 'get',
	            dataType : "json",
	            beforeSend : function(){
	            },
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            let data_table = $("#LstProducts").html();

                            data_table += "<tr>";
                            data_table += "<td></td>";
                            data_table += "<td>" + response.product_info.product_id + "</td>";
                            data_table += "<td>" + response.product_info.reference + "</td>";
                            data_table += "<td>" + response.product_info.p_product_name + "</td>";
                            data_table += "<td>" + response.product_info.p_product_selling_price + " <b>" +  response.product_info.currency_code + "</b></td>";
                            data_table += "<td></td>";
                            data_table += "</tr>";

                            $("#LstProducts").html(data_table);
                        }


                        //selected_product

                       let deals = $("input[name=deals]").val();
                       if(deals == "")
                           deals = selected_product;
                       else
                           deals = deals + "," + selected_product;

                       $("input[name=deals]").val(deals);
                    }
                })

        },
	FilterDeals : function(){
		deals_module.displayListDeals();
	},
	SaveAndContinueDealsInfo : function(){
		return deals_module.SaveAndContinueDealsSubmitHandler();
	},
        SaveAndContinueDealsSubmitHandler : function(){
            	 var DealForm = $('#FORM_SAVE_DEALS');
         var error3 = $('.alert-danger', DealForm);
         var success3 = $('.alert-success', DealForm);

         DealForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 fk_account_id : {
            		 required: true
            	 },
            	 fk_contact_id : {
                     required: true
            	 },
            	 fk_lead_id : {
            		 required: true,
            	 },
            	 ad_deal_code : {
	               required: true
	             },
	             ad_deal_title : {
	               required: true,
	               minlength: 5
	             },
	             ad_deal_amount : {
	            	 number : true
	             },
                     ad_down_payment : {
                       number : true
                     },
                     ad_nbr_of_payments : {
                         number : true,
                         required : true
                     },
	             ad_deal_probability : {
	            	 number : true
	             },
	             ad_deal_probability : {
	            	 number : true
	             },
	             ad_expected_revenue : {
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

    	        var str_params = $("#FORM_SAVE_DEALS").serialize();
    	        const ad_deal_description  	= $.desc_editor.getData();
    	        const ad_next_step	  		= $.nextstep_editor.getData();
    	        str_params = str_params + "&ad_deal_description=" + ad_deal_description + "&ad_next_step=" + ad_next_step;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/deals/savedealinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/crm/accounts/deals/editform/" + response.ad_id;
    	              }
    	            }
    	        });
             }
         });
        },
	SaveDealsInfo : function(){
		return deals_module.SaveDealsSubmitHandler();
	},
	SaveDealsSubmitHandler : function(){
		 var DealForm = $('#FORM_SAVE_DEALS');
         var error3 = $('.alert-danger', DealForm);
         var success3 = $('.alert-success', DealForm);

         DealForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 fk_account_id : {
            		 required: true
            	 },
            	 fk_contact_id : {
                     required: true
            	 },
            	 fk_lead_id : {
            		 required: true,
            	 },
            	 ad_deal_code : {
	               required: true
	             },
	             ad_deal_title : {
	               required: true,
	               minlength: 5
	             },
	             ad_deal_amount : {
	            	 number : true
	             },
                     ad_down_payment : {
                       number : true
                     },
                     ad_nbr_of_payments : {
                         number : true,
                         required : true
                     },
	             ad_deal_probability : {
	            	 number : true
	             },
	             ad_deal_probability : {
	            	 number : true
	             },
	             ad_expected_revenue : {
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

    	        var str_params = $("#FORM_SAVE_DEALS").serialize();
    	        const ad_deal_description  	= $.desc_editor.getData();
    	        const ad_next_step	  		= $.nextstep_editor.getData();
    	        str_params = str_params + "&ad_deal_description=" + ad_deal_description + "&ad_next_step=" + ad_next_step;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/deals/savedealinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/crm/accounts/deals";
    	              }
                      else {
                          bootbox.alert(response.error_msg);
                      }
    	            }
    	        });
             }

         });
	},
	DeleteDealData : function(){
		 var ad_id = $(this).data('ad_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={ad_id : ad_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/deals/deletedealinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  deals_module.displayListDeals();
			              }
			            }
			        });
			}
		});
	},
	EditDealInfo : function(){
		var ad_id = $(this).data('ad_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/crm/accounts/deals/editform/" + ad_id;
	}
};
