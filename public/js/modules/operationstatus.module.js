/**
 * 
 */

operationstatus_module = {
		displayListOperationStatus : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var page_number = $('input[name=page_number]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/operationstatuses/displaylist",
		        data : { _token : _token , page_number : page_number },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstStatus').html(response.display);
					var datatable = $('.m_datatable').mDatatable({
						
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
					

					$("a[id*=EDIT_STATUS_]").on('click',operationstatus_module.DisplayEditOperationStatusForm);
					$("a[id*=DELETE_STATUS_]").on('click',operationstatus_module.DeleteOperationStatus);
		        }
		    });
		},
	SaveOperationStatusInfo : function(){
		return operationstatus_module.SaveOperationStatusInfoSubmitHandler();
	},
	SaveOperationStatusInfoSubmitHandler : function(){
		 var StatusForm = $('#FORM_SAVE_STATUS');
         var error3 = $('.alert-danger', StatusForm);
         var success3 = $('.alert-success', StatusForm);

         StatusForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 os_status_title : {
                     required: true
                   },
                   os_status_order : {
                     required: true
                   },
                   os_status_color : {
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
    	        
 
    	        var str_params = $("#FORM_SAVE_STATUS").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/operationstatuses/savestatusinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/operation/statuses";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteOperationStatus : function(){
		 var os_id = $(this).data('os_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={os_id : os_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/operationstatuses/deletestatusinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  operationstatus_module.displayListOperationStatus();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditOperationStatusForm : function(){
		var os_id = $(this).data('os_id');
	    var base_url = $("#BASE_URL").val(); 
	    window.location.href = base_url + "/operation/statuses/editform/" + os_id;
	}	
};