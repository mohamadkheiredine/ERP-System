/**
 * 
 */

contacts_module = {
		DisplayListContacts : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			var contact_lead	 	= $('select[name=contact_lead]').val();
		    var params = { _token : _token , contact_lead : contact_lead };
		    $.ajax
	        ({
	            url : base_url + "/request/contacts/displaylist",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstContacts').html(response.display);
	            	 
	            	 $('.group-checkable').change(function() {
	                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
	                        var checked = $(this).prop("checked");
	                        $(set).each(function() {
	                            $(this).prop("checked", checked);
	                        });
	                        $.uniform.update(set);
	                    });
	            	
	            }
	        });
		},
		SaveLeadContactInfo : function(){
			return contacts_module.SaveLeadContactSubmitHandler();
		},
		SaveLeadContactSubmitHandler : function(){
			 var SaveContactForm = $('#FORM_SAVE_CONTACT');
	         var error3 = $('.alert-danger', SaveContactForm);
	         var success3 = $('.alert-success', SaveContactForm);

	         SaveContactForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_owner_id : {
	                     required: true
	                 }, 
	                 cc_first_name : {
	                     required: true
	                 },
	                 cc_last_name : {
	                	 required: true
	                 },
	                 fk_lead_id : {
	                	 required: true
	                 },
	                 cc_contact_email : {
	                	 required: true
	                 },
	                 cc_contact_phone : {
	                	 required: true
	                 },
	                 cc_contact_mobile : {
	                	 required :true
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
	    	       
	                var FormDataFields = $("form[id=FORM_SAVE_CONTACT]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        $.each($("input[type=file]"), function(i, obj) {
	    	                var name = $(this).attr('name');
	    	                $.each(obj.files,function(j,file){
	    	                        data.append(name, file);
	    	                })
	    	        });

	    	        FormDataFields.find('input,select').each(function(){
	    	                data.append($(this).attr('name'), $(this).val() );
	    	        });
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/savecontactsinfo",
	    	            data : data,
	    	            async: false,
	    	            cache: false,
	    	            method : 'post',
	    	            contentType: false,
	    	            processData: false,
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	 var lead_id = $("select[name=fk_lead_id]").val();
	    	                 window.location.href = base_url + "/crm/leads/editform/" + lead_id;
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
		SaveContactInfo : function(){
			return contacts_module.SaveContactSubmitHandler();
		},
		SaveContactSubmitHandler : function(){
			 var SaveContactForm = $('#FORM_SAVE_CONTACT');
	         var error3 = $('.alert-danger', SaveContactForm);
	         var success3 = $('.alert-success', SaveContactForm);

	         SaveContactForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_owner_id : {
	                     required: true
	                 }, 
	                 cc_first_name : {
	                     required: true
	                 },
	                 cc_last_name : {
	                	 required: true
	                 },
	                 cc_contact_email : {
	                	 required: true
	                 },
	                 cc_contact_phone : {
	                	 required: true
	                 },
	                 cc_contact_mobile : {
	                	 required :true
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
	    	       
	                var FormDataFields = $("form[id=FORM_SAVE_CONTACT]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        $.each($("input[type=file]"), function(i, obj) {
	    	                var name = $(this).attr('name');
	    	                $.each(obj.files,function(j,file){
	    	                        data.append(name, file);
	    	                })
	    	        });

	    	        FormDataFields.find('input,select').each(function(){
	    	                data.append($(this).attr('name'), $(this).val() );
	    	        });
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/savecontactsinfo",
	    	            data : data,
	    	            async: false,
	    	            cache: false,
	    	            method : 'post',
	    	            contentType: false,
	    	            processData: false,
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              { 
	    	                 window.location.href = base_url + "/crm/contacts";
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
		EditContactInfo : function(){
			var cc_id = $(this).data("cc_id");
			 var base_url = $('#BASE_URL').val();
			 window.location.href = base_url + "/crm/contacts/editcontact/" + cc_id;
		},
		DeleteLeadContactInfo : function(){
			var cc_id = $(this).data('cc_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={cc_id : cc_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/contacts/deletecontactinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  leads_module.DisplayLeadContactsTab();
				              }
				            }
				        });
				}
			});
		},
		DeleteContactInfo : function(){
			var cc_id = $(this).data('cc_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={cc_id : cc_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/contacts/deletecontactinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  leads_module.DisplayListContacts();
				              }
				            }
				        });
				}
			});
		}	 
};