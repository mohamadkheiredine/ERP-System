/**
 * 
 */

roles_module = {
		displayListRoles : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/roles/displaylist",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('#ListRoleGirds').html(response.display);
		            	$.roles_datatable = $('.m_datatable').mDatatable({
						
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
		            	
						$("a[id*=EDIT_ROLE_]").on('click',roles_module.EditRoleInfo);
						$("a[id*=DELETE_ROLE_]").on('click',roles_module.DeleteRoleData);
		        }
		    });
		},
		SaveRoleInfo : function(){
		return roles_module.SaveRoleInfoSubmitHandler();
	},
	SaveRoleInfoSubmitHandler : function(){
		 var RoleForm = $('#FORM_SAVE_ROLE');
         var error3 = $('.alert-danger', RoleForm);
         var success3 = $('.alert-success', RoleForm);

         RoleForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 role_name : {
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
    	        
 
    	        var str_params = $("#FORM_SAVE_ROLE").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/roles/saveinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/administrator/roles";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteRoleData : function(){
		 var role_id = $(this).data('role_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={role_id : role_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/roles/deleterole",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  roles_module.displayListRoles();
			              }
			            }
			        });
			}
		});
	},
	EditRoleInfo : function(){
		var role_id = $(this).data('role_id');
	    var base_url = $("#BASE_URL").val(); 
	    window.location.href = base_url + "/roles/editform/" + role_id;
	}	
};