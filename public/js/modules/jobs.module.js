/**
 * 
 */
jobs_module = {
	DisplayListJobs : function(){
		if($.jj_datatable != null)
		 $.jj_datatable.destroy();
		 
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var job_status 	= $('select[name=job_status]').val();
		var customer_id = $('select[name=customers]').val();
	    var j_due_date 	= $('input[name=j_due_date]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/jobs/displaylist",
	        data : { _token : _token , job_status : job_status , j_due_date : j_due_date , customer_id : customer_id},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstMaintenanceJobs').html(response.display);
				$.jj_datatable = $('.m_datatable').mDatatable({
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
				
				$("a[id*=EDIT_JOB_]").on('click',jobs_module.EditJobInfo);
				$("a[id*=DELETE_JOB_]").on('click',jobs_module.DeleteJobData);
	        }
	    });
	},
	DisplayJobItems : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var job_id 		= $('input[name=j_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/jobs/displaylistitems",
	        data : { _token : _token , job_id : job_id},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	if(response.is_error == 0)
        		{
	        		$('.ProductLog').html(response.display); 
        		}
	        	
	        }
	    });
	},
	PayMaintenanceOrder : function(e){
		e.preventDefault();
		bootbox.confirm("Are you Sure you want to Pay for for this order ?",function(result){
			if(result == true)
			{
				let _token 		= $('input[name=_token]').val();
				let base_url 	= $('input[name=base_url]').val();
				let job_id 		= $('input[name=j_id]').val();
				var params 		= { _token : _token , job_id : job_id };
				var str_params 	= $.param(params);
				
				 $.ajax
			    ({
			        url : base_url + "/request/jobs/payorder",
			        data : { _token : _token , job_id : job_id},
		            method : 'post',
		            dataType : "json",
		            beforeSend : function(){
		            },
			        success : function(response){
			        	if(response.is_error == 0)
		        		{
			        		window.location.href = base_url + "/maintenance/jobs";
		        		}
			        	
			        }
			    });
			}
		});
	},
	QuickActionJobs : function(){
		var action_type = $(this).data('action_type');
		if($(".checkboxes:checked").length == 0)
		{
			bootbox.alert("Please select a Job to do any action");
			return false;
		}
		
		switch(action_type)
		{
			case "PRINT_REQUEST":
			{
				jobs_module.PrintJobRequest();
			}
			break;
			case "PRINT_ORDER":
			{
				jobs_module.PrintJobOrder();
			}
			break;
		}
	},
	PrintJobRequest : function(){
		var j_id = $(".checkboxes:checked").val();
		var base_url 		= $('input[name=base_url]').val();
		var _token 			= $('input[name=_token]').val();
		
		
		window.open(base_url + "/maintenance/jobs/printjobrequest/" + j_id, '_blank');
	},
	PrintJobOrder : function(){
		var j_id = $(".checkboxes:checked").val();
		var base_url 		= $('input[name=base_url]').val();
		var _token 			= $('input[name=_token]').val();
		
		
		window.open(base_url + "/maintenance/jobs/printjoborder/" + j_id, '_blank');
		
	},
	GetPriceResult : function(){
		var base_url 		= $('input[name=base_url]').val();
		var _token 			= $('input[name=_token]').val();
		var job_id 			= $('input[name=j_id]').val();
	    var service_item 	= $('#JM_SERVICE_ITEMS').val();
	    $.ajax
	    ({
	        url : base_url + "/request/jobs/getserviceprice",
	        data : { _token : _token , job_id : job_id , service_item : service_item},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('.ItemCurrency').html(response.service_currency);
	        	 $('input[name=mj_item_cost]').html(response.service_currency);
	        }
	    });
	},
	OpenItemsPopup : function(){
		$("#InserItems").modal('toggle');
	},
	InsertJobItem : function(){
		let lst_items 	= $("#LST_JOB_ITEMS").val();
		let items_array = [];
		if(lst_items.length > 0)
		{
			items_array = JSON.parse( lst_items );
		}
		
		let item_type = $("#MJ_JOB_MAINTENANCE").val();
		let product_id = $("#JM_PRODUCT_ITEMS option:selected").val();
		let product_text = $("#JM_PRODUCT_ITEMS option:selected").text();
		let service_id = $("#JM_SERVICE_ITEMS option:selected").val();
		let service_text = $("#JM_SERVICE_ITEMS option:selected").text();
		let mj_item_barecode 	= $("input[name=mj_item_barecode]").val();
		let mj_item_cost 		= $("input[name=mj_item_cost]").val();
		let bi_quanity 			= $("input[name=bi_quanity]").val();
		let item_info = {};
		
		
		if(service_id != undefined)
		{
			item_info = {
					'item_type' : item_type,
					'service_id' : service_id,
					'mj_item_barecode' : mj_item_barecode,
					'mj_item_cost' : mj_item_cost,
					'bi_quanity' : bi_quanity
			};
		}
		else
		{
			item_info = {
					'item_type' : item_type,
					'product_id' : product_id,
					'mj_item_barecode' : mj_item_barecode,
					'mj_item_cost' : mj_item_cost,
					'bi_quanity' : bi_quanity
			};
		} 
		
		items_array.push(item_info);
		 
		var row = document.createElement("tr");
		if(product_id == null)
		{
			var Idcell = document.createElement("td");
			var IdcellText =  document.createTextNode(service_id);
			Idcell.appendChild(IdcellText);
		    row.appendChild(Idcell);
			var Servicecell = document.createElement("td");
			var ServicecellText =  document.createTextNode(service_text);
			Servicecell.appendChild(ServicecellText);
		    row.appendChild(Servicecell);
		}
		else
		{
			var Idcell = document.createElement("td");
			var IdcellText =  document.createTextNode(product_id);
			Idcell.appendChild(IdcellText);
		    row.appendChild(Idcell);
			var Productcell = document.createElement("td");
			var ProductcellText =  document.createTextNode(product_text);
			Productcell.appendChild(ProductcellText);
		    row.appendChild(Productcell);
		}
		
		var Costcell = document.createElement("td");
		var CostcellText =  document.createTextNode(mj_item_cost);
		Costcell.appendChild(CostcellText);
	    row.appendChild(Costcell);
		let index_record = items_array.length;
		
		var Deletecell = document.createElement("td");
		 Deletecell.innerHTML = "<a href='#' data-index='" + index_record + "'  id='DELECT_RECORD_" + index_record + "' ><i class='fa fa-minus-circle' aria-hidden='true' height='16' ></i></a>";
	    row.appendChild(Deletecell);
	    var tbody = document.getElementById('JOB_ITEMS');
	    tbody.appendChild(row);
		lst_items = JSON.stringify(items_array);
		$("#LST_JOB_ITEMS").val(lst_items);
		// initialize form items
		$('.ItemsDropdown').html("");
		$('#MJ_JOB_MAINTENANCE').val("0");
		$('#MJ_ITEM_BARECODE').val("");
		$('#MJ_ITEM_COST').val("");
		$('input[name=bi_quanity]').val("1");
		$("#InserItems").modal('toggle');
	},
	DeleteItemRecord : function(){
		let index = $(this).data('index');
		let lst_items 	= $("#LST_JOB_ITEMS").val();
		let $this = $(this);
		var r = confirm("Are you sure you want to delete this record ?");
		  if(r)
		  {

				items_array = JSON.parse( lst_items );
				items_array.splice(index - 1, 1);
				lst_items = JSON.stringify(items_array);
				$("#LST_JOB_ITEMS").val(lst_items);
				$this.parents('tr').remove();
		  }
	},
	OpenNewCustomer : function(){
		$('#InsertCustomers').modal('toggle');
	},
	OpenNewVendor : function(){
		$('#InsertVendors').modal('toggle');
	},
	DisplayItemsDropdown : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var mj_job_maintenance 		= $('select[name=mj_job_maintenance]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/jobs/displayitemsdropdown",
	        data : { _token : _token , mj_job_maintenance : mj_job_maintenance},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	 $('.ItemsDropdown').html(response.display);
	        	 $("#JM_PRODUCT_ITEMS").select2();
	        	 $("#JM_SERVICE_ITEMS").select2();
	        }
	    });
	},
	SaveCustomerInfo : function(){
		return jobs_module.SaveCustomerInfoSubmitHandler();
	},
	SaveCustomerInfoSubmitHandler : function(){
		var CustomerForm = $('#FRM_SAVE_CUSTOMER');
        var error3 = $('.alert-danger', CustomerForm);
        var success3 = $('.alert-success', CustomerForm);

        CustomerForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
	        	ic_customer_name : {
	       		 	required: true
	           	 },
	           	ic_customer_email : {
	               email:true
	             },
	             ic_customer_mobile : {
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
    	       
               var FormDataFields = $("form[id=FRM_SAVE_CUSTOMER]");

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
   	            url : base_url + "/request/savemaincustomerinfo",
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
   	            		$('select#J_CUSTOMER_ID').append($('<option>', {value: response.customer_id, text: response.customer_name } ));
   	            		$('form[id=FRM_SAVE_CUSTOMER]').find("input[type=text], input[type=email], input[type=url] textarea select").val("");
   	            		$("#InsertCustomers").modal('toggle');
            		}
   	            }
   	        });
         }
        });
	},
	SaveVendorInfo : function(){
		return jobs_module.SaveVendorInfoSubmitHandler();
	},
	SaveVendorInfoSubmitHandler : function(){
		var VendorForm = $('#FRM_SAVE_VENDOR');
        var error3 = $('.alert-danger', VendorForm);
        var success3 = $('.alert-success', VendorForm);

        VendorForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
	        	iv_vendor_name : {
	       		 	required: true
	           	 },
	           	iv_vendor_email : {
	               email:true
	             },
	             iv_vendor_mobile : {
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
    	       
               var FormDataFields = $("form[id=FRM_SAVE_VENDOR]");

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
   	            url : base_url + "/request/savemainvendorinfo",
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
   	            		$('select#J_VENDOR_ID').append($('<option>', {value: response.vendor_id, text: response.vendor_name } ));
   	            		$('form[id=FRM_SAVE_VENDOR]').find("input[type=text], input[type=email], input[type=url] textarea select").val("");
   	            		$("#InsertVendors").modal('toggle');
            		}
   	            }
   	        });
         }
        });
	},
	SaveJobInfo : function(){
		return jobs_module.SaveJobInfoSubmitHandler();
	},
	SaveJobInfoSubmitHandler : function(){
		 var JobsForm = $('#FORM_SAVE_JOBS');
         var error3 = $('.alert-danger', JobsForm);
         var success3 = $('.alert-success', JobsForm);

         JobsForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 j_job_title : {
            		 required: true
            	 },
            	 j_job_total_cost : {
	               required: true
	             },
	             j_job_status_id : {
	            	 required :true
	             },
	             j_currency_id : {
	            	 required :true
	             },
	             j_user_id : {
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
    	       // var _token = $('input[name=_token]').val();
    	        var str_params = $("#FORM_SAVE_JOBS").serialize();
    	        str_params = str_params;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/savejobinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/maintenance/jobs";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteJobData : function(){
		 var j_id = $(this).data('j_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={j_id : j_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/deletejobinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.jj_datatable.destroy();
			            	  jobs_module.DisplayListJobs();
			              }
			            }
			        });
			}
		});
	},
	EditJobInfo : function(){
		var j_id = $(this).data('j_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/maintenance/jobs/editform/" + j_id;
	}
};