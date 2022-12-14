/**
 * 
 */

emptype_module = {
		displayListEmpType : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var page_number = $('input[name=page_number]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/employmenttype/displaylist",
		        data : { _token : _token , page_number : page_number },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstEmpTypeGrid').html(response.display);
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
	SaveEmpTypeInfo : function(){
		return emptype_module.SaveEmpTypeInfoSubmitHandler();
	},
	SaveEmpTypeInfoSubmitHandler : function(){
		 var EmpTypeForm = $('#FORM_SAVE_EMPTYPE');
         var error3 = $('.alert-danger', EmpTypeForm);
         var success3 = $('.alert-success', EmpTypeForm);

         EmpTypeForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 et_type : {
                     required: true
                   },
                   et_min_working_hours : {
                     required: true
                   },
                   et_order : {
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
    	        
 
    	        var str_params = $("#FORM_SAVE_EMPTYPE").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/employmenttype/saveemptypeinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/system/employmenttype";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteEmpTypeData : function(){
		 var et_id = $(this).data('et_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={et_id : et_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/employmenttype/deleteemptypeinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  emptype_module.displayListEmpType();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditEmpTypeForm : function(){
		var et_id = $(this).data('et_id');
	    var base_url = $("#BASE_URL").val(); 
	    window.location.href = base_url + "/system/employmenttype/editform/" + et_id;
	}	
};