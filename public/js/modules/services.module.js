/**
 * 
 */
services_module = {
		displayListServices : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val() 
		    $.ajax
		    ({
		        url : base_url + "/request/services/displaylist",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstServices').html(response.display);
					$.cs_datatable = $('.m_datatable').mDatatable({
						
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
						columns : [
							{
	        					field: "#",
	        			        title: "#", 
	        			        sortable: false,
	        			        width: 40,
	        			        selector: {class: 'm-checkbox--solid m-checkbox--brand'}
	        				},
	        				{
	        					field: 'Id',
	        					title: 'ID',
	        					type: 'number',
	        					width: 4
	        				},
	        				{
	        					field: 'code',
	        					type: 'text',
	        					width: 100,
	        					sortable: true
	        				},
	        				{
	        					field: 'Name',
	        					type: 'text',
	        					width: 250,
	        					sortable: true
	        				},
	        				{
	        					field: "edit",
	        			        title: "edit", 
	        			        sortable: false,
	        			        width: 40
	        				},
	        				{
	        					field: "delete",
	        			        title: "delete", 
	        			        sortable: false,
	        			        width: 40
	        				}
	        			]
						
						// inline and bactch editing(cooming soon)
						// editable: false,
					});
					
					$("a[id*=EDIT_SERVICE_]").on('click',services_module.EditServiceInfo);
					$("a[id*=DELETE_SERVICE_]").on('click',services_module.DeleteServiceData);
		        }
		    });
	},
	DisplayListPaymentTypesAccounting : function() {
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val() 
	    var cs_id 		= $('input[name=cs_id]').val() 
	    $.ajax
	    ({
	        url : base_url + "/request/services/listpaymenttypes",
	        data : { _token : _token  , cs_id : cs_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$(".PaymentTypeAccounting").html(response.display);
	        }
	    });
	},
	SaveServiceInfo : function(){
		return services_module.SaveServiceInfoSubmitHandler();
	},
	SaveServiceInfoSubmitHandler : function(){
		 var ServiceForm = $('#FORM_SAVE_SERVICE');
         var error3 = $('.alert-danger', ServiceForm);
         var success3 = $('.alert-success', ServiceForm);

         ServiceForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 cs_service_title : {
            		 required: true
            	 },
            	 cs_cost_per_hour : {
                     number: true
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
   
    	       var sc_category_description  = $.editor.getData();
    	        
    	    //    data.append("sc_category_description", sc_category_description );
    	        var str_params = $("#FORM_SAVE_SERVICE").serialize();
    	        str_params = str_params + "&sc_category_description=" + sc_category_description;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/services/saveserviceinfo",
    	            data : str_params, 
    	            method : 'post', 
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/crm/services";
    	              }
    	            }
    	        });
             }

         });
	},
	AddNewAccount : function(){
		var account_ref 	= $('input[name=aa_account_ref]').val();
		var parent_account 	= $('select[name=aa_parent_account]').val();
		var account_label 	= $('input[name=aa_account_label]').val();
		var dropdown_name 	= $('input[name=dropdown_name]').val();
			
		var _token 			= $('input[name=_token]').val();
	     var base_url = $('#BASE_URL').val();
		var params = {account_ref : account_ref , parent_account : parent_account , account_label : account_label , _token : _token};
		$.ajax
        ({
            url : base_url + "/request/customers/saveaccaccounting",
            data : params,
            dataType : "Json",
            type : "POST",
            success : function(response){
              if(response.is_error == 0)
              {
            	  let $dropdown = $('select[name=' + dropdown_name + ']');
            	  $dropdown.select2('destroy');
            	  $dropdown.append("<option selected='selected' value='" +  response.aa_id + "'>" + response.accounting_label + "</option>");
            	  $dropdown.select2();
            	  $("#AccountAccounting").modal('toggle');
              }
              else
        	  {
            	  bootbox.alert(response.error_msg);
        	  }
            }
        });
		
	},
	OpenAddNewAccount : function(){
		var dropdown_name 	= $(this).data('dropdown_name');
		$('input[name=dropdown_name]').val(dropdown_name);
		// $("select[name=aa_parent_account]").select2();
		$("#AccountAccounting").modal('toggle');
	},
	DeleteServiceData : function(){
		 var cs_id = $(this).data('cs_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={cs_id : cs_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/services/deleteserviceinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.cs_datatable.destroy();
			            	  services_module.displayListServices();
			              }
			            }
			        });
			}
		});
	},
	SavePaymentType : function(){
		var pt_payment_type 		= $("select[name=pt_payment_type").val();
		var st_account_income_id 	= $("select[name=st_account_income_id").val();
		var st_account_purchase_id 	= $("select[name=st_account_purchase_Id").val();
		
		if(pt_payment_type == '')
		{
			bootbox.alert("Please Select Payment Type");
			return false;
		}
		
		
		if(st_account_income_id == '')
		{
			bootbox.alert("Please Select Incoming Account");
			return false;
		}
		
		if(st_account_purchase_id == '')
		{
			bootbox.alert("Please Select Purchase Account");
			return false;
		}
		
		var cs_id 					= $('input[name=cs_id]').val();
		var st_id 					= $('input[name=st_id]').val();
		var _token 					= $('input[name=_token]').val();
	    var base_url 					= $('input[name=base_url]').val();
        var str_params ={pt_payment_type : pt_payment_type , _token : _token , st_account_income_id : st_account_income_id , st_account_purchase_id : st_account_purchase_id , cs_id : cs_id , st_id : st_id};
        $.ajax
        ({
            url : base_url + "/request/services/savepaymenttype",
            data : str_params,
            dataType : "Json",
            type : "POST",
            success : function(response){
              if(response.is_error == 0)
              { 
            	  services_module.DisplayListPaymentTypesAccounting();
            	  $('input[name=st_id]').val('');
            	  $("select[name=pt_payment_type").val('');
            	  $("select[name=st_account_income_id").val('');
            	  $("select[name=st_account_purchase_Id").val('');
      			  $("#PaymentType").modal('toggle');
              }
            }
        });
        
	
	},
	OpenEditPaymentTypePopup : function() {
		var st_id = $(this).data('st_id');
		var _token 					= $('input[name=_token]').val();
	    var base_url 					= $('input[name=base_url]').val();
        var str_params ={ _token : _token , st_id : st_id};
        $.ajax
        ({
            url : base_url + "/request/services/getpaymenttypeinfo",
            data : str_params,
            dataType : "Json",
            type : "POST",
            success : function(response){
              if(response.is_error == 0)
              {  
          		$("select[name=pt_payment_type").val(response.pt_payment_type);
        		$("select[name=st_account_income_id").val(response.st_account_income_id);
        		$("select[name=st_account_purchase_Id").val(response.st_account_purchase_id);
        		
      			  $("#PaymentType").modal('toggle');
              }
            }
        });
	},
	EditServiceInfo : function(){
		var cs_id = $(this).data('cs_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/crm/services/editform/" + cs_id;
	},
	DeletePaymentTypeData : function(){
		 var st_id = $(this).data('st_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={st_id : st_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/services/deletepaymenttype",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  services_module.DisplayListPaymentTypesAccounting();
			              }
			            }
			        });
			}
		});
	},
	CancelForm : function(){
		 window.history.back();
	}
};