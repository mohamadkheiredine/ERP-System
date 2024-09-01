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
	FilterDeals : function(){
		deals_module.displayListDeals();
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
	               required: true,
	               minlength: 5
	             },
	             ad_deal_title : {
	               required: true,
	               minlength: 5
	             },
	             ad_deal_amount : {
	            	 number : true
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