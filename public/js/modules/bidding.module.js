/**
 * 
 */

bidding_module = {
		displayListBidding : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			$.ajax
			({
				url : base_url + "/request/srm/displaylistbidding",
				data : { _token : _token },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstBidding').html(response.display);
					$.bidding_datatable = $('.m_datatable').mDatatable({
						// layout definition
						layout: {
							theme: 'default', // datatable theme
							class: '', // custom wrapper class
							scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
							// height: 450, // datatable's body's fixed height
							footer: false // display/hide footer
						},
						
						// column sorting
						sortable: true,
						
						pagination: true,
						
						search: {
							input: $('#generalSearch')
						},
						
						// inline and bactch editing(cooming soon)
						// editable: false,
					});
					
					$("a[id*=EDIT_BIDDING_]").on('click',bidding_module.EditBiddingInfo);
					$("a[id*=DELETE_BIDDING_]").on('click',bidding_module.DeleteBiddingData);
				}
			});
		},
		DisplayListBiddingProducts : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
		    var sb_id 		= $('input[name=sb_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/srm/displaylistbiddingitems",
		        data : { _token : _token , sb_id : sb_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#BiddingProducts').html(response.display);
					$.bproducts_datatable = $('.mbp_datatable').mDatatable({
						// layout definition
						layout: {
							theme: 'default', // datatable theme
							class: '', // custom wrapper class
							scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
							// height: 450, // datatable's body's fixed height
							footer: false // display/hide footer
						},
						
						// column sorting
						sortable: true,
						
						pagination: true,
						
						search: {
							input: $('#generalSearch')
						},
						
						// inline and bactch editing(cooming soon)
						// editable: false,
					});
		        }
		    });
		},
		OpenAddProductBidding : function(){
			$('#InserItems').modal('toggle');
		},
		SaveInsertItemsInfo : function(){
	
			return bidding_module.SaveInsertItemsSubmitHandler();
		},
		SaveInsertItemsSubmitHandler : function(){
			var BidItemsForm = $('#FRM_BID_INSERT_ITEMS');
			var error3 = $('.alert-danger', BidItemsForm);
			var success3 = $('.alert-success', BidItemsForm);
			BidItemsForm.validate({
				errorElement: 'span', //default input error message container
				errorClass: 'help-block help-block-error', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				ignore: "", // validate all fields including form hidden input
				rules: {
					bi_item_title : {
						required: true
					},
					bi_item_quanity : {
						required: true
					},
					bi_currency_id : {
						required: true
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
					var str_params = $("#FRM_BID_INSERT_ITEMS").serialize(); 
					$.ajax
					({
						url : base_url + "/request/srm/savebiditemsinfo",
						data : str_params,
						method : 'post',
						dataType : "json",
						success : function(response){
							if(response.is_error == 0)
							{
								bidding_module.DisplayListBiddingProducts();
								$('#InserItems').modal('toggle');
							}
						}
					});
					
					///^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/
				}
				
			});
		},
		SaveSupplierBiddingInfo : function(){
			return bidding_module.SaveSupplierBiddingSubmitHandler();
		},
		SaveSupplierBiddingSubmitHandler : function(){
			 var BiddingForm = $('#FORM_SAVE_BIDDING');
	         var error3 = $('.alert-danger', BiddingForm);
	         var success3 = $('.alert-success', BiddingForm);

	         BiddingForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 sb_bid_title : {
	            		 required: true
	            	 },
	            	 sb_owner_id : {
	            		 required: true
	            	 },
	            	 sb_item_types : {
	            		 required: true
	            	 },
	            	 sb_start_date : {
	            		 required: true
	            	 },
	            	 sb_end_date : {
	            		 required: true
	            	 },
	            	 sb_rfq_issue_date : {
	            		 required: true
	            	 },
	            	 sb_due_rfq_date : {
	            		 required: true
	            	 },
	            	 sb_bid_description : {
	                 required: true
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
	    	        const sb_bid_description  = $.desc_editor.getData();
	    	         
	    	        var str_params = $("#FORM_SAVE_BIDDING").serialize();
	    	        str_params = str_params + "&sb_bid_description=" + sb_bid_description;
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/srm/savebiddinginfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/srm/supplier/bidding";
	    	              }
	    	            }
	    	        });
	    	         
	    	         ///^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/
	             }

	         });
		},
		DeleteBiddingItems : function(){
			var bi_id = $(this).data('bi_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
					var base_url = $('#BASE_URL').val();
					var _token = $('input[name=_token]').val();
					var str_params ={bi_id : bi_id , _token : _token};
					$.ajax
					({
						url : base_url + "/request/srm/deletebiddingiteminfo",
						data : str_params,
						dataType : "Json",
						type : "POST",
						success : function(response){
							if(response.is_error == 0)
							{
								$.bproducts_datatable.destroy();
								bidding_module.DisplayListBiddingProducts();
							}
						}
					});
				}
			});
		},
		DeleteBiddingData : function(){
			 var sb_id = $(this).data('sb_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={sb_id : sb_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/srm/deletebiddinginfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  $.bidding_datatable.destroy();
				            	  bidding_module.displayListBidding();
				              }
				            }
				        });
				}
			});
		},
		EditBiddingInfo : function(){
			var sb_id = $(this).data('sb_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/srm/bidding/edit/" + sb_id;
		}
};