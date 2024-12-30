/**
 * 
 */
templates_module = {
        DisplayListInvoiceTemplates : function(){
                var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val()
            var page_number = $('input[name=page_number]').val();
            var general_search = $('input[name=general_search]').val();
            var invoice_customer = $('select[name=invoice_customer]').val(); 
            $.ajax
            ({
                url : base_url + "/request/billing/displaylistinvoicetemplates",
                data : { _token : _token , page_number : page_number , general_search : general_search , invoice_customer : invoice_customer },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
                success : function(response){
                    $('#LstInvoiceTemplates').html(response.display);
            $('.group-checkable').change(function() {
                var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                var checked = $(this).prop("checked");
                $(set).each(function() {
                    $(this).prop("checked", checked);
                });
                $.uniform.update(set);
            }); 
           $.pagination = $('#InvoiceTemplatesPagination').twbsPagination({
                 totalPages: response.total_pages,
                 visiblePages: 7,
                 onPageClick: function (event, page) {
                      $('input[name=page_number]').val(page);
                      templates_module.DisplayListInvoiceTemplates();
                 }
             });
                }
            });
        },
        DisplayListInvoiceTemplatesItems : function(){
            var base_url 	= $('input[name=base_url]').val();
            var _token 		= $('input[name=_token]').val();
            var it_id 		= $('input[name=it_id]').val();
            $.ajax
            ({
                url : base_url + "/request/billing/displaylistinvoicetemplates",
                data : { _token : _token  , it_id : it_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
                success : function(response){
                    $('#LstInvoiceTemplates').html(response.display); 
                }
            });
        },
        AddNewTemplateForm : function(){
                var base_url = $("#BASE_URL").val();
                window.location.href = base_url + "/billing/invoicetemplates/addform";
        },
	SaveInvoiceTemplateInfo : function(){
		return templates_module.SaveInvoiceTemplateInfoSubmitHandler();
	},
	SaveInvoiceTemplateInfoSubmitHandler : function(){
		 var TemplateForm = $('#FORM_SAVE_TEMPLATE');
         var error3 = $('.alert-danger', TemplateForm);
         var success3 = $('.alert-success', TemplateForm);

         TemplateForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 it_total_cost : {
                    required: true,
                    number : true
                  },
                  it_total_price : {
                      required: true,
                    number : true
                  },
                  it_template_label : {
                      required : true
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
    	        
 
    	        var str_params = $("#FORM_SAVE_TEMPLATE").serialize();
                  const it_template_description  	= $.temp_desc.getData();
    	        const it_invoice_note	  		= $.notes_desc.getData();
    	        str_params = str_params + "&it_template_description=" + it_template_description + "&it_invoice_note=" + it_invoice_note;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/billing/saveinvoicetemplateinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/billing/invoicetemplates";
    	              }
    	            }
    	        });
             }

         });
	},
        AddInvoiceTemplateItem : function(){
             var base_url = $('#BASE_URL').val();
             var _token = $('input[name=_token]').val();
             var tl_id = $('input[name=tl_id]').val();
             var tl_item_id = $('select[name=tl_item_id]').val();
             var ti_currency_id = $('select[name=it_invoice_currency]').val();
             $.ajax
            ({
                url : base_url + "/request/billing/saveinvtemplateitem",
                data : {tl_item_id : tl_item_id ,_token : _token , tl_id : tl_id , ti_currency_id : ti_currency_id},
                method : 'PUT',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                  if(response.is_error == 0)
                  {
                     templates_module.DisplayListInvoiceTemplatesItems();
                     $("#items_modal").modal('toggle');
                  }
                }
            });
        },
	DeleteInvoiceTemplateData : function(){
		 var it_id = $(this).data('it_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={it_id : it_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/request/billing/deleteinvoicetemplateinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "DELETE",
			            success : function(response){
			              if(response.is_error == 0)
			              {
                                         templates_module.DisplayListInvoiceTemplates();
			              }
			            }
			        });
			}
		});
	},
	DeleteInvoiceTemplateItem : function(){
		 var ti_id = $(this).data('ti_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={ti_id : ti_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/request/billing/deletetemplateitem",
			            data : str_params,
			            dataType : "Json",
			            type : "DELETE",
			            success : function(response){
			              if(response.is_error == 0)
			              {
                                         templates_module.DisplayListInvoiceTemplatesItems();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditTemplateForm : function(){
		var it_id = $(this).data('it_id');
	    var base_url = $("#BASE_URL").val(); 
	    window.location.href = base_url + "/billing/invoicetemplates/editform/" + it_id;
	},
	CancelForm : function(){
		 window.history.back();
	}
};