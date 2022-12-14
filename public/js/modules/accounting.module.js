/**
 * 
 */

accounting_module = {
		DisplayOpeningJournal : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var at_id 					= $('input[name=at_id]').val();
			var fisical_year = $("input[name=fisical_year]").val();
			var at_transaction_date 	= $('input[name=at_transaction_date]').val();  
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistopeningvouchers",
		        data : { _token : _token , at_id : at_id , at_transaction_date : at_transaction_date , fisical_year : fisical_year},
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstOpeningMovements').html(response.display);
		        	if(response.is_error == 1)
	        		{
	        	 	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
	        		}
		        	$("a[id*=EDIT_MOV_]").on('click',accounting_module.EditMovementInfo);
		        	$("a[id*=DELETE_MOV_]").on('click',accounting_module.DeleteMovementInfo);
		        }
		    });
		},
		SaveOpeningVoucherInfo : function(){ 
			return accounting_module.SaveOVSubmitHandler();
		},
		SaveOVSubmitHandler : function(){
			 var TransForm = $('#FORM_SAVE_OPENING_VOUCHER'); 
	         var error3 = $('.alert-danger', TransForm);
	         var success3 = $('.alert-success', TransForm);

	         TransForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 at_transaction_date : {
	            		 required: true
	            	 },
	            	 fk_acc_journal_id : {
	                     required: true
	                   },
	                   at_accounting_doc : {
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
	    	        var str_params = $("#FORM_SAVE_OPENING_VOUCHER").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/accounting/savetransactionovinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/accounting/openingvoucher";
	    	              }
	    	              else
    	            	  {
	    	            	  $("#SUCCESS_MSG").css({display : "none"});
	    	            	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
	    	            	  accounting_module.DisplayListMovements();
    	            	  }
	    	            }
	    	        });
	             }

	         });
		},
		AddNewTranMovementRow : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			$.ajax
		    ({
		        url : base_url + "/request/accounting/addnewopeningrows",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		        	$("#TRANS_MOVEMENTS").append(response.display);
		        	$(".row-select").select2();
		    		$("a.RemoveMov").on('click',accounting_module.RemoveCurrentMovementRow);
		        }
		    });
		},
		EditMovementInfo : function(){
			var $this = $(this);
			var tm_id = $(this).data('tm_id');
			var _token 					= $('input[name=_token]').val();
		    var base_url = $("#BASE_URL").val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displayeditovmovementrow",
		        data : { _token : _token , tm_id : tm_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$this.parents('tr').html(response.display); 
		        	$("#BTN_SAVE_ROW").on('click',accounting_module.SaveOpeningVoucherRowInfo);
		        }
		    });
		},
		DeleteMovementInfo : function(){
			
		},
		RemoveCurrentMovementRow : function(){
			
		},
		SaveOpeningVoucherRowInfo : function(){
			var $this = $(this);
			var at_id 					= $("input[name=at_id]").val();
			var tm_id 					= $this.parents('tr').find("input[name=tm_id]").val(); 
			var tm_sub_ledger_account 	= $this.parents('tr').find("select[name=tm_sub_ledger_account]").val();
			var tm_currency_id 	= $this.parents('tr').find("select[name=tm_currency_id]").val();
			var tm_ledger_label	 		= $this.parents('tr').find("input[name=tm_ledger_label]").val();
			var tm_debit	 			= $this.parents('tr').find("input[name=tm_debit]").val();
			var tm_credit	 			= $this.parents('tr').find("input[name=tm_credit]").val();
			var _token 					= $('input[name=_token]').val();
			var base_url 				= $('input[name=base_url]').val();
			var params					= {tm_id : tm_id , at_id : at_id , tm_sub_ledger_account : tm_sub_ledger_account , tm_currency_id : tm_currency_id , tm_ledger_label : tm_ledger_label , tm_debit : tm_debit , tm_credit : tm_credit , _token : _token};
			 $.ajax
 	        ({
 	            url : base_url + "/request/accounting/savemovementrowovinfo",
 	            data : params,
 	            method : 'post',
 	            dataType : "json",
 	            success : function(response){
 	              if(response.is_error == 0)
 	              {
 	            	 accounting_module.DisplayOpeningJournal();
 	            	 $("#ERROR_MSG").css({display : "none"});
 	            	 $("#SUCCESS_MSG").css({display : ""});
 	              }
 	              else
            	  {
            	  $("#SUCCESS_MSG").css({display : "none"});
            	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
            	  accounting_module.DisplayOpeningJournal();
            	  }
 	            }
 	        });
		}
};