/**
 * 
 */

units_module = {
		DisplayListPackageUnits : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    $.ajax
		    ({
		        url : base_url + "/request/units/displaylist",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('#ListUnitsPackage').html(response.display);
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
	SavePackagesInfo : function(){
		return units_module.SavePackagesInfoSubmitHandler();
	},
	SavePackagesInfoSubmitHandler : function(){
		 var PackageForm = $('#FORM_SAVE_UNITS');
         var error3 = $('.alert-danger', PackageForm);
         var success3 = $('.alert-success', PackageForm);

         PackageForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
               pu_unit_label : {
	             required: true
               },
               pu_units : {
            	required: true
               },
               pu_unit_amount : {
                required: true,
                number : true
               },
               pu_currency_id : {
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
    	        var str_params = $("#FORM_SAVE_UNITS").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/phones/units/saveunitinfo",
    	            data : str_params,
    	            method : 'post', 
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/phones/units";
    	              }
    	            }
    	        });
             }

         });
	},
	DeletePhoneUnitsData : function(){
		 var pu_id = $(this).data('pu_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={pu_id : pu_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/phones/units/deleteunit",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  units_module.DisplayListPackageUnits();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditUnitsForm : function(){
		var pu_id = $(this).data('pu_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/phones/units/editform/" + pu_id;
	}
};