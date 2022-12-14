/**
 * 
 */
debitnotes_module = {
		DisplayListDebitNotes : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			var dn_account_payable 		= $('select[name=dn_account_payable]').val();
			var dn_account_receivable 	= $('select[name=dn_account_receivable]').val();
			var dn_start_date 	= $('input[name=dn_start_date]').val();
		    var dn_end_date 	= $('input[name=dn_end_date]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/debitnotes/displaylist",
		        data : { _token : _token , dn_account_payable : dn_account_payable , dn_account_receivable : dn_account_receivable , dn_start_date : dn_start_date , dn_end_date : dn_end_date },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstDebitNotes').html(response.display);
		        	if(response.total_pages > 0)
	        		{
			        	 $.pagination = $('#DebitNotesPagination').twbsPagination({
	                         totalPages: response.total_pages,
	                         visiblePages: 7,
	                         onPageClick: function (event, page) {
	                              $('input[name=page_number]').val(page);
	                              debitnotes_module.DisplayListDebitNotes();
	                         }
	                     });
	        		
	        		}
					
					$("a[id*=EDIT_DN_]").on('click',debitnotes_module.EditDNInfo);
					$("a[id*=DELETE_DN_]").on('click',debitnotes_module.DeleteDNData);
		        }
		    });
		},
		SaveDebitNoteInfo : function(){
			return debitnotes_module.SaveDebitNoteSubmitHandler();
		},
		SaveDebitNoteSubmitHandler : function(){
			 var DNotesForm = $('#FORM_SAVE_DEBITNOTES');
	         var error3 = $('.alert-danger', DNotesForm);
	         var success3 = $('.alert-success', DNotesForm);

	         DNotesForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 dn_account_sender : {
	            		 required: true
	            	 },
	            	 dn_account_receivable : {
	            		 required: true
	            	 },
	            	 dn_credit_label : {
	            		 required: true,
	            		 minlength:4
	            	 },
	            	 dn_credit_value : {
	            		 required: true,
	            		 number : true
	            	 },
	            	 dn_currency_id : {
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
	    	         
	    	        var str_params = $("#FORM_SAVE_DEBITNOTES").serialize(); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/debitnotes/savedninfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/billing/debitnotes";
	    	              }
	    	            }
	    	        }); 
	             }

	         });
		},
		DeleteDNData : function(){
			 var dn_id = $(this).data('dn_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={dn_id : dn_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/debitnotes/deletedninfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              { 
				            	  debitnotes_module.DisplayListDebitNotes();
				              }
				            }
				        });
				}
			});
		},
		EditDNInfo : function(){
			var dn_id = $(this).data('dn_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/billing/debitnotes/editform/" + dn_id;
		}
};