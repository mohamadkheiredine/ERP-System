/**
 * 
 */
orders_module = {
		DisplayListOrders : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var page_number 		= $('input[name=page_number]').val();
			var general_search 		= $('input[name=general_search]').val();
			var so_order_customer 	= $('select[name=so_order_customer]').val();
			var so_vendor_id 		= $('select[name=so_vendor_id]').val();
			var so_order_warehouse 	= $('select[name=so_order_warehouse]').val();

			$.ajax
			({
				url : base_url + "/request/orders/displaylist",
				data : { _token : _token , so_order_warehouse : so_order_warehouse ,  so_order_customer : so_order_customer , so_vendor_id : so_vendor_id , page_number : page_number , general_search : general_search },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('.LstOrdersBody').html(response.display);
					 $('.group-checkable').change(function() {
                         var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                         var checked = $(this).prop("checked");
                         $(set).each(function() {
                             $(this).prop("checked", checked);
                         });
                         $.uniform.update(set);
                     }); 
					 if(response.total_pages > 0)
					 {
						 $('#SalesOrdersPagination').twbsPagination({
	                         totalPages: response.total_pages,
	                         visiblePages: 7,
	                         onPageClick: function (event, page) {
	                              $('input[name=page_number]').val(page);
	                              orders_module.DisplayListOrders();
	                         }
	                     });
					 }
					
					$("a[id*=EDIT_ORDER_]").on('click',orders_module.EditOrderInfo);
					$("a[id*=DELETE_ORDER_]").on('click',orders_module.DeleteOrderData);
				}
			});
		},
		PayPaymentOrder : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    var so_id 		= $('input[name=so_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/orders/payorder",
		        data : { _token : _token , so_id : so_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	if(response.is_error == 1)
	        		{
		        		bootbox.alert(response.error_msg);
		        		return false;
	        		}
		        	
		        	$("#BTN_ADD_PRODUCT").css({'display' : "none"});
		        	$("#BTN_PAY_ORDER").css({'display' : "none"});
		        }
		    });
		},
		DisplayListOrderProducts : function(){
		var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val();
	    let so_id = $('input[name=so_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/orders/displaylistproducts",
	        data : { _token : _token , so_id : so_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstProducts').html(response.display);
				$.op_datatable = $('.m_datatable').mDatatable({
					// layout definition
					layout: {
						theme: 'default', // datatable theme
						class: '', // custom wrapper class
						scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
						// height: 450, // datatable's body's fixed height
						footer: false // display/hide footer
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
        					field : "ID",
        					title : "Id", 
        			        sortable: true,
        			        width: 40
        					
        				},
        				{
        					field : "barcode",
        					title : "barcode", 
        			        sortable: true,
        			        width: 100
        					
        				},
        				{
        					field : "Product Name",
        					title : "Product Name", 
        			        sortable: true,
        			        width: 150
        					
        				},
        				{
        					field : "Products Item",
        					title : "Products Item", 
        			        sortable: true,
        			        width: 75
        					
        				}
        			],
					// column sorting
					sortable: true,
					
					pagination: true,
					
					search: {
						input: $('#generalSearch')
					},
					
					// inline and bactch editing(cooming soon)
					// editable: false,
				});
	        }
	    });
	},
	OpenAddOrderProductsModal : function(){
		$('#OrderProductsModel').modal('toggle');
	},
	AddOrderProducts : function(){
		return orders_module.AddOrderProductSubmitHandler();
	},
	AddOrderProductSubmitHandler : function(){
		var ProductForm = $('#FRM_ADD_PRODUCTS');
        var error3 = $('.alert-danger', ProductForm);
        var success3 = $('.alert-success', ProductForm);

        ProductForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
            	order_product : {
		       		 required: true
		       	 },
		       	so_product_quantity : {
		       		required : true,
		       		number : true
		       	},
		       	so_product_cost : {
		       		number : true,
		       		required : true
		       	},
		       	so_product_serial : {
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
            	let barecode = $('#SO_PRODUCT_SERIAL').val();
        		if(barecode == '')
        			bootbox.alert({
            			message : 'Please Add A valid SerialNumber' ,
            			 className: 'rubberBand animated'
            		});
               success3.show();
               error3.hide();
               $("#AjaxLoader").css({'display':'block'});
               var base_url = $('#BASE_URL').val();
	   	       // var _token = $('input[name=_token]').val();
	   	        var str_params = $("#FRM_ADD_PRODUCTS").serialize();
	   	        var warehouse_id = $('select[name=fk_warehouse_id]').val();
	   	        str_params = str_params + "&warehouse_id=" + warehouse_id;
	   	        str_params = str_params;
	   	         $.ajax
	   	        ({
	   	            url : base_url + "/request/orders/saveproduct",
	   	            data : str_params,
	   	            method : 'post',
	   	            dataType : "json",
	   	            success : function(response){
	   	              $("#AjaxLoader").css({'display':'none'});
	   	              if(response.is_error == 0)
	   	              {
	            	  	$.op_datatable.destroy();
	            	  	orders_module.DisplayListOrderProducts();
	            		$('#OrderProductsModel').modal('toggle');
	   	              }
	   	              else
   	            	  {
	   	            	  bootbox.alert(response.error_msg,function(){
	   	            		$("#ORDER_PRODUCT").val('0');
	   	            		$("#SO_PRODUCT_COST").val('');
	   	            		$("#SO_PRODUCT_QUANTITY").val('');
	   	            		$('#OrderProductsModel').modal('toggle');
	   	            	  });
	   	            	  
   	            	  }
	   	            }
	   	        });
            }

        });
	},
	SaveOrdersInfo : function(){
		return orders_module.SaveOrdersSubmitHandler();
	},
	SaveOrdersSubmitHandler : function(){
		 var OrderForm = $('#FORM_SAVE_ORDER');
         var error3 = $('.alert-danger', OrderForm);
         var success3 = $('.alert-success', OrderForm);

         OrderForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
        	 so_assign_to : {
        		 required: true
        	 },
        	 so_order_label : {
                 required: true
               },
               so_order_status : {
                 required: true
               },
               so_order_date : {
               required: true
               },
               so_delivery_date : {
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
    	        var str_params = $("#FORM_SAVE_ORDER").serialize();
    	        var so_whole_sale = 0;
    	        if( $("input[name=so_whole_sale]:checked").length == 1 )
    			{
    	        	so_whole_sale = 1;
    			}
    	        
    	        
    	        str_params = str_params + "&so_whole_sale=" + so_whole_sale;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/orders/saveinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/sales/orders";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteOrderData : function(){
		 var so_id = $(this).data('so_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={so_id : so_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/orders/deleteinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.so_datatable.destroy();
			            	  orders_module.DisplayListOrders();
			              }
			            }
			        });
			}
		});
	},
	EditOrderInfo : function(){
		var so_id = $(this).data('so_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/sales/orders/editform/" + so_id;
	},
	getProductPrice : function(){
		var so_id 		= $('input[name=so_id]').val();
		var product_id 	= $('#ORDER_PRODUCT').val();
		var _token 		= $('input[name=_token]').val();
	    var base_url 	= $("#BASE_URL").val(); 
	    var params = { so_id : so_id , product_id : product_id , _token : _token };
	    $("#AjaxLoader").css({'display':'block'});
        $.ajax
        ({
            url : base_url + "/request/orders/getproductprice",
            data : params,
            dataType : "Json",
            type : "POST",
            success : function(response){
              if(response.is_error == 0)
              {
            	 $('input[name=so_product_cost]').val(response.product_price);
            	 $("#AjaxLoader").css({'display':'none'});   
              }
            }
        });
	},
	CheckStockPriceValue : function(){
		var _token 			= $('input[name=_token]').val();
		var product_id 		= $('select[name=order_product]').val();
		var order_currency 	= $('select[name=so_order_currency]').val();
		var stock_price 	= $('input[name=so_product_cost]').val();
	    var base_url 		= $("#BASE_URL").val(); 
	    var params = {product_id : product_id , stock_price : stock_price , _token : _token , order_currency : order_currency };
	    $("#AjaxLoader").css({'display':'block'});
        $.ajax
        ({
            url : base_url + "/request/orders/validatestockprice",
            data : params,
            dataType : "Json",
            type : "POST",
            success : function(response){
              $("#AjaxLoader").css({'display':'none'});
              if(response.is_error == 1)
              {
            	 bootbox.alert(response.error_msg);
            	 return false
              }
            }
        });
	},
	getStockInformation : function(){
		let barecode = $('#SO_PRODUCT_SERIAL').val();
		if(barecode == '')
			return false;
        $("#AjaxLoader").css({'display':'block'});   	
		// get product stock information based on serial number we have
		var base_url 		= $('input[name=base_url]').val();
		var _token 			= $('input[name=_token]').val(); 
		var whole_sales 	= $("#SO_WHOLE_SALE:checked").length; 
		var order_currency 	= $("#SO_ORDER_CURRENCY").val(); 
	    var so_product_serial 	= $("#SO_PRODUCT_SERIAL").val(); 
	    $.ajax
	    ({
	        url : base_url + "/request/orders/getstockinformation",
	        data : { _token : _token , barecode : barecode , whole_sales : whole_sales , order_currency : order_currency },
         method : 'post',
         dataType : "json",
         beforeSend : function(){
         },
	        success : function(response){
	            $("#AjaxLoader").css({'display':'none'});
	        	if(response.is_error == 1)
	     		{
		        		bootbox.alert({
		        			message : response.error_msg ,
		        			 className: 'rubberBand animated'
		        		});
			        	$("#SO_PRODUCT_SERIAL").val('');
		        		return false;
	     		}
	        	$('#STOCK_ID').val(response.stock_id);
	        	$('#SO_PRODUCT_COST').val(response.stock_price);
	        	
	        }
	    });
	}
};