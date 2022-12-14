/**
 * 
 */

chartaccounts_module = {
		displayListChartAccounts : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			var page_number 		= $('input[name=page_number]').val();
		    var search_query 		= $('input[name=search_query]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaychartaccounts",
		        data : { _token : _token , page_number : page_number , search_query : search_query },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstChartAccounts').html(response.display); 
                       $('.group-checkable').change(function() {
                          var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                          var checked = $(this).prop("checked");
                          $(set).each(function() {
                              $(this).prop("checked", checked);
                          });
                          $.uniform.update(set);
                      }); 
                     $.pagination = $('#AccountsPagination').twbsPagination({
                           totalPages: response.total_pages,
                           visiblePages: 7,
                           onPageClick: function (event, page) {
                                $('input[name=page_number]').val(page);
                                chartaccounts_module.displayListChartAccounts();
                           }
                       });
		        	
					
					$("a[id*=EDIT_ACCOUNT_]").on('click',chartaccounts_module.EditChartAccountInfo);
					$("a[id*=DELETE_ACCOUNT_]").on('click',chartaccounts_module.DeleteAccountData);
		        }
		    });
	},
	GenerateNewAccount : function(){
		var account_id = $(this).data('account_id');
	      var id = $("#AA_SUB_ACCOUNT").val();
	      var _token = $("input[name=_token]").val();
	      var base_url = $("input[name=base_url]").val();
	      var params = { account_id : account_id ,id : id, _token : _token };
	      $.ajax
		    ({
		        url : base_url + "/request/accounting/generatelatestaccount",
		        data :params,
	          method : 'post',
	          dataType : "json", 
		      success : function(response){
		        	 var account_ref = response.account_ref;
		        	 $("#AA_CATEGORY_REF").val(account_ref);
		        	 $("#AA_ACCOUNT").val(account_ref);
		        	 //$("#AA_SUB_ACCOUNT").val(id);
		        	 //$("#AA_SUB_ACCOUNT").trigger('change');
		    }});
	},
	QuickActionChartAccount : function(){
		var action_type = $(this).data('action_type');
		switch(action_type)
		{
			case "EXPORT_AS_CSV":
			{
				
				if($(".checkboxes:checked").length == 0 )
				{
					bootbox.alert("Please select a Chart Account to do any action");
					return false;
				}
				chartaccounts_module.ExportAsCsv();
			}
			break; 
			case "IMPORT":
			{
				$('#ImportModal').modal('toggle');
			}
			break; 
		}
	},
	ImportChartAccount : function(){
		 var base_url = $('#BASE_URL').val();
	       
         var FormDataFields = $("form[id=FRM_IMPORT_ACCOUNTS]");

	        var data = new FormData();
	        var index = 0;

	        $.each($("input[type=file]"), function(i, obj) {
	                var name = $(this).attr('name');
	                $.each(obj.files,function(j,file){
	                        data.append(name, file);
	                })
	        });

	        FormDataFields.find('input,select').each(function(){
	        	var name = $(this).attr('name');
	        	var val = $(this).val();
        		data.append( name, val );
	        	 
	        }); 
	         $.ajax
	        ({
	            url : base_url + "/request/accounting/importchartaccount",
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
	            	  chartaccounts_module.displayListChartAccounts();
	            	  $('#ImportModal').modal('toggle');
	              }
	            }
	        });
		
	},
	SaveAndNewAccountInfo : function(){
		$('input[name=is_save_new]').val(1);
		return chartaccounts_module.SaveAccountInfoSubmitHandler();
	},
	SaveAccountInfo : function(){
		return chartaccounts_module.SaveAccountInfoSubmitHandler();
	},
	SaveAccountInfoSubmitHandler : function(){
		 var AccountForm = $('#FORM_SAVE_ACCOUNT');
         var error3 = $('.alert-danger', AccountForm);
         var success3 = $('.alert-success', AccountForm);

         AccountForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 aa_account_ref	 : {
            		 required: true
            	 },
            	 aa_account  : {
                     required: true
                   },
                   aa_account_label : {
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
    	        

    	        var str_params = $("#FORM_SAVE_ACCOUNT").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/accounting/savenewaccountrecord",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	            	  var is_save_new = $('input[name=is_save_new]').val();
    	            	  if(is_save_new == 1)
	            		  {
    	            	      window.location.href = base_url + "/accounting/chartofaccounts/addform";
	            		  }
    	            	  else 
    	            	  {
    	            	      window.location.href = base_url + "/accounting/chartofaccounts";	
						  }
    	           
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteAccountData : function(){
		 var aa_id = $(this).data('aa_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={aa_id : aa_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/accounting/deleteaccountrecord",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              { 
			            	  chartaccounts_module.displayListChartAccounts();
			              }
			            }
			        });
			}
		});
	},
	EditChartAccountInfo : function(){
		var aa_id = $(this).data('aa_id'); 
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/accounting/chartofaccounts/editform/" + aa_id;
	},
	ExportAsCsv : function(){
		
	}
};