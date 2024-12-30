mcases_module = {
	DisplayListMaintenanceCases : function(){
	    var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var page_number = $('input[name=page_number]').val();
	    var general_search = $('input[name=general_search]').val();
	    var cc_case_status = $('select[name=cc_case_status]').val();
	    var cc_technician_id = $('select[name=cc_technician_id]').val();
	    var cc_assigned_agent_id = $('select[name=cc_assigned_agent_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/maintenancecase/displaylist",
	        data : { _token : _token , page_number : page_number , general_search : general_search , cc_assigned_agent_id : cc_assigned_agent_id , cc_technician_id : cc_technician_id , cc_case_status : cc_case_status},
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	            $('.LstMaintenanceCases').html(response.display);
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
                	 $.pagination = $('#MaintenanceCasePagination').twbsPagination({
                         totalPages: response.total_pages,
                         visiblePages: 7,
                         onPageClick: function (event, page) {
                              $('input[name=page_number]').val(page);
                              mcases_module.DisplayListMaintenanceCases();
                         }
                     });
            	}
              
	        }
	    });
	    
	},
        getAccountCaseInfo : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var ad_account_code 	= $('#CC_CLIENT_CODE').val();
            $.ajax
            ({
                url : base_url + "/request/account/getaccountinfobycode",
                data : { _token : _token , ad_account_code : ad_account_code },
                method : 'get',
                dataType : "json",
                success : function(response){
                        $('#CA_ACCOUNT_NAME').val(response.account_info.ca_account_name);     
                        $('#CC_CLIENT_ID').val(response.account_info.ca_id); 
                        $('#CA_ACCOUNT_ADDRESS').val(response.account_info.ca_billing_address); 
                }
            });
        },
	SaveMaintenanceCaseInfo : function(){
		return mcases_module.SaveMaintenanceCaseSubmitHandler();
	},
	SaveMaintenanceCaseSubmitHandler : function(){
		 var MaintenanceCaseForm = $('#FORM_SAVE_CASE');
         var error3 = $('.alert-danger', MaintenanceCaseForm);
         var success3 = $('.alert-success', MaintenanceCaseForm);

         MaintenanceCaseForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
                 cc_case_code : {
            		required: true
            	 },
                  cc_case_label : {
            		required: true
            	 },
                  cc_technician_id : {
            		required: true
            	 },
                  cc_case_status : {
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
    	        var str_params = $("#FORM_SAVE_CASE").serialize();
    	        
    	        const cc_case_description = $.case_desc.getData();
    	        const cc_resolution_notes = $.res_notes.getData();
    	        
    	        str_params = str_params + "&cc_case_description=" + cc_case_description + "&cc_resolution_notes=" + cc_resolution_notes;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/maintenancecase/saveinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/callcenter/maintenancecase";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteCaseData : function(){
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
			            url : base_url + "/request/maintenancecase/deleteinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "delete",
			            success : function(response){
			              if(response.is_error == 0)
			              {
                                             mcases_module.DisplayListMaintenanceCases();
			              }
			            }
			        });
			}
		});
	},
	EditCaseInfo : function(){
		var cc_id = $(this).data('cc_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/callcenter/maintenancecase/editform/" + cc_id;
	}
};