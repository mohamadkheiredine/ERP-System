/**
 *
 */
quotations_module = {
	displayListQuotations : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var page_number = $('input[name=page_number]').val();
		var general_search = $('input[name=general_search]').val();
		var quotation_supplier = $('select[name=quotation_supplier]').val();
	    var quotation_warehouse = $('select[name=quotation_warehouse]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/srm/displaylistquotations",
	        data : { _token : _token , page_number : page_number , quotation_supplier : quotation_supplier , general_search : general_search , quotation_warehouse : quotation_warehouse},
            method : 'post',
            dataType : "json",
	        success : function(response){
	        	$('#LstQuotations').html(response.display);

	        	if(response.total_pages > 0)
        		{
		        	$('#QuotationsPagination').twbsPagination({
	                    totalPages: response.total_pages,
	                    visiblePages: 7,
	                    onPageClick: function (event, page) {
	                         $('input[name=page_number]').val(page);
	                         quotations_module.displayListQuotations();
	                    }
	                });
        		}
	        }
	    });
	},
	GetProductInfoByBarCode : function(){
		// get product information saved by search on barcode
		let $this = $(this);
		$('.SerialNumbers').scannerDetection({
			timeBeforeScanTest: 200, // wait for the next character for upto 200ms
			avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
			preventDefault: false,
			endChar: [13],
			onComplete: function(barcode, qty){
		   		validScan = true;
		    	$(this).val(barcode);
		    	var base_url = $('#BASE_URL').val();
	    	    var _token = $('input[name=_token]').val();
	    	    var params = {_token : _token , barcode : barcode};
    	         $.ajax
    	        ({
    	            url : base_url + "/request/srm/findproductbybarcode",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	            	  	 let use_serial_number = response.use_serial_number;
    	     		        let product_id = response.product_id;
    	     		        $this.parents('tr').find('.PurchasePrice').val(response.selling_price);
    	     		        $this.parents('tr').find('.SellingPrice').val(response.selling_price);
    	     		        $this.parents('tr').find('.WholeSalePrice').val(response.selling_price);
    	     		        $this.parents('tr').find('.VendorPrice').val(response.selling_price);
    	     		        $this.parents('tr').find('.ProductId').val(product_id);
    	     		        $this.parents('tr').find('.CurrencyId').val(response.product_currency);
    	     		        if(use_serial_number == 1)
    	     	        	{
    	     		        	$this.parents('tr').find('.ListSerialNumbers').css({'display' : ''});
    	     	        	}
    	     		        else
    	     		        {
    	     		        	$this.parents('tr').find('.ListSerialNumbers').css({'display' : 'none'});
    	     		        }
    	              }
    	            }
    	        });
			}
		});

	},
	OpenAddNewProduct : function(){

	},
	SaveSupplierQuotationInfo : function(){
		return quotations_module.SaveSupplierQuotationSubmitHandler();
	},
	SaveSupplierQuotationSubmitHandler : function(){
        if($("#BTN_SAVE_QUOTATION").attr('data-disabled'))
            return false;
        $("#BTN_SAVE_QUOTATION").attr('data-disabled','disabled');
		 var QuotationForm = $('#FORM_SAVE_QUOTATION');
         var error3 = $('.alert-danger', QuotationForm);
         var success3 = $('.alert-success', QuotationForm);

         QuotationForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 fk_supplier_id : {
	                 required: true
	               },
	               sq_user_id : {
	                 required: true
	               },
	               sq_currency_id : {
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
    	       // var _token = $('input[name=_token]').val();
    	        var str_params = $("#FORM_SAVE_QUOTATION").serialize();
    	        str_params = str_params;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/srm/savequotationinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
                        $("#BTN_SAVE_QUOTATION").removeAttr('data-disabled');
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/srm/bidding/quotations";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteQuotationData : function(){
		 var sq_id = $(this).data('sq_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={sq_id : sq_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/srm/deletequotationinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.quotation_datatable.destroy();
			            	  quotations_module.displayListQuotations();
			              }
			            }
			        });
			}
		});
	},
	ApproveSupplierQuotation : function(){
		bootbox.confirm("Are you sure you want to Approve this QUotation ?", function(result){
			 var base_url = $('#BASE_URL').val();
		      var _token = $('input[name=_token]').val();
				var sq_id = $("input[name=sq_id]").val();
		        var str_params ={sq_id : sq_id , _token : _token};
		         $.ajax
		        ({
		            url : base_url + "/request/srm/approvequotation",
		            data : str_params,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
		              if(response.is_error == 0)
		              {
		            	  window.location.href = base_url + "/srm/suppliercontracts/editform/" + response.sc_id;
		              }
		            }
		        });
		});
	},
	EditQuotationInfo : function(){
		var sq_id = $(this).data('sq_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/srm/quotation/editform/" + sq_id;
	},
	ViewQuotationData : function(){
		var sq_id = $(this).data('sq_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/srm/quotation/view/" + sq_id;
	},
	CalculateDiscountedPrice : function(){
		var $this = $(this);
		var discount = $this.val();
		var purchase_price = $this.parents('tr').find('.PurchasePrice').val();

		var new_price = purchase_price - ( purchase_price * discount/100);

		$this.parents('tr').find('.SellingPrice').val(new_price);

		let whole_sales = $this.parents('tr').find('.WholeSalePrice').val();
		let vendor_price = $this.parents('tr').find('.VendorPrice').val();

		if(whole_sales > new_price)
			$this.parents('tr').find('.WholeSalePrice').val(new_price);
		if(vendor_price > new_price)
			$this.parents('tr').find('.VendorPrice').val(new_price);
	}
};
