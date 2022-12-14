/**
 * 
 */
entries_module = {
	displayListEntries : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var bank_id 	= $('select[name=bank_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/banking/displaylistentries",
	        data : { _token : _token , bank_id : bank_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstBankEntries').html(response.display);
				$.be_datatable = $('.m_datatable').mDatatable({
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
				
				$("a[id*=EDIT_ENTRY_]").on('click',entries_module.EditBankEntryInfo);
				$("a[id*=DELETE_ENTRY_]").on('click',entries_module.DeleteBankEntryData);
	        }
	    });
	},
	SaveBankingEntryInfo : function(){
		return entries_module.SaveBankingEntrySubmitHandler();
	},
	SaveBankingEntrySubmitHandler : function(){
		 var EntryForm = $('#FORM_SAVE_ENTRY');
         var error3 = $('.alert-danger', EntryForm);
         var success3 = $('.alert-success', EntryForm);

         EntryForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 be_bank_id : {
            		 required: true
            	 },
            	 be_entry_type : {
            		 required: true
            	 },
            	 be_bank_transfer : {
            		 required: true
            	 },
            	 be_transfer_transmitter : {
            		 required: true
            	 },
            	 be_bank_transfer : {
            		 required: true
            	 },
            	 be_operation_date : {
            		 required :true
            	 },
            	 be_value_date : {
            		 required :true
            	 },
            	 be_entry_amount : {
            		 number : true,
            		 required : true
            	 },
            	 be_entry_label : {
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
    	         
    	        var str_params = $("#FORM_SAVE_ENTRY").serialize(); 
    	         $.ajax
    	        ({
    	            url : base_url + "/request/banking/saveentryinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/banking/entries/list";
    	              }
    	            }
    	        });
    	        
             }

         });
	},
	DeleteBankEntryData : function(){
		 var be_id = $(this).data('be_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={be_id : be_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/banking/deleteentryinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.be_datatable.destroy();
			            	  entries_module.displayListEntries();
			              }
			            }
			        });
			}
		});
	},
	GetBankCurrency : function(){
		 var base_url 		= $('#BASE_URL').val();
		 var _token 		= $('input[name=_token]').val();
	      var bank_id 		= $('select[name=be_bank_id]').val();
	        var str_params ={bank_id : bank_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/banking/getbankcurrency",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $('.AmountCurrency').html(response.currency_code);
	              }
	            }
	        });
	},
	EditBankEntryInfo : function(){
		var be_id = $(this).data('be_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/banking/entries/editform/" + be_id;
	}
};