/**
 * 
 */
personalizedgroups_module = {
		displayListPersonalizedgroups : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistpersonalizedgroups",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstPersonalizedGroups').html(response.display);
					$.pg_datatable = $('.m_datatable').mDatatable({
						
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
					
					$("a[id*=EDIT_GROUP_]").on('click',personalizedgroups_module.EditPersGroupInfo);
					$("a[id*=DELETE_GROUP_]").on('click',personalizedgroups_module.DeletePersGroupData);
		        }
		    });
	},
	SavePersGroupInfo : function(){
		return personalizedgroups_module.SavePersGroupSubmitHandler();
	},
	SavePersGroupSubmitHandler : function(){
		 var PersGroupForm = $('#FORM_SAVE_GROUP');
         var error3 = $('.alert-danger', PersGroupForm);
         var success3 = $('.alert-success', PersGroupForm);

         PersGroupForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 pg_group_code : {
            		 required: true
            	 },
            	 pg_group_label : {
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
    	        const pg_group_comment  = $.editor.getData();
    	         
    	        var str_params = $("#FORM_SAVE_GROUP").serialize();
    	        str_params = str_params + "&pg_group_comment=" + pg_group_comment;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/accounting/savepersonalizedgroupsinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/accounting/personalizedgroups";
    	              }
    	            }
    	        });
             }

         });
	},
	DeletePersGroupData : function(){
		 var pg_id = $(this).data('pg_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={pg_id : pg_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/accounting/deletepersonalizedgroupsinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.pg_datatable.destroy();
			            	  personalizedgroups_module.displayListPersonalizedgroups();
			              }
			            }
			        });
			}
		});
	},
	EditPersGroupInfo : function(){
		var pg_id = $(this).data('pg_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/accounting/personalizedgroups/editform/" + pg_id;
	}
};