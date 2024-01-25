/**
 * 
 */
banking_module = {
	displayListAccounts : function(){
		var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/banking/displaylistaccounts",
	        data : { _token : _token },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstBankAccounts').html(response.display);
	        }
	    });
	},
	SaveBankingAccountInfo : function(){
		return banking_module.SaveBankingAccountSubmitHandler();
	},
	SaveBankingAccountSubmitHandler : function(){
		 var AccountForm = $('#FORM_SAVE_ACCOUNT');
         var error3 = $('.alert-danger', AccountForm);
         var success3 = $('.alert-success', AccountForm);

         AccountForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 cc_category_ref : {
            		 required: true
            	 },
            	 cc_category_name : {
            		 required: true
            	 },
            	 ba_account_type : {
            		 required: true
            	 },
            	 ba_account_currency : {
            		 required: true,
            		 number:true
            	 },
            	 ba_account_country : {
            		 required: true
            	 },
            	 ba_account_number : {
            		 required: true
            	 },
            	 ba_account_iban : {
            		 required: true
            	 },
            	 ba_bank_name : {
                 required: true
               },
               ba_accounting_journal : {
                 required: true
               },
               ba_accounting_account : {
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
    	        const ba_account_comment  = $.account_editor.getData();
    	         
    	        var str_params = $("#FORM_SAVE_ACCOUNT").serialize();
    	        str_params = str_params + "&ba_account_comment=" + ba_account_comment;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/banking/saveaccountinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/banking/financialaccount";
    	              }
    	            }
    	        });
    	         
    	         ///^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/
             }

         });
	},
	DeleteBankingAccountData : function(){
		 var ba_id = $(this).data('ba_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={ba_id : ba_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/banking/deleteaccountinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.ba_datatable.destroy();
			            	  banking_module.displayListAccounts();
			              }
			            }
			        });
			}
		});
	},
	EditBankingAccountInfo : function(){
		var ba_id = $(this).data('ba_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/banking/financialaccount/editform/" + ba_id;
	}
};