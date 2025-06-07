/**
 *
 */
orders_module = {
        DisplayListOrderCategories : function(){
             var base_url 			= $('input[name=base_url]').val();
                var _token 				= $('input[name=_token]').val();
                var order_id 				= $('input[name=order_id]').val();

                let params = { order_id : order_id , _token : _token };

                $.ajax
                ({
                        url : base_url + "/request/sorders/displaylistcategories",
                        data : params,
                        method : 'post',
                        dataType : "json",
                        beforeSend : function(){
                        },
                        success : function(response){
                                $('#LstPackingCategories').html(response.display);
                        }
                });


        },
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
                        url : base_url + "/request/sorders/displaylist",
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
    QuickActionShippingOrders : function(){
        var action_type = $(this).data('action_type');
        if($(".checkboxes:checked").length == 0)
        {
            bootbox.alert("Please select a order to do any action");
            return false;
        }
        switch(action_type)
        {
            case "DOWNLOAD":
            {
                var or_ids = [];
                $(".checkboxes:checked").each(function(){
                    var or_id = $(this).val();
                    or_ids.push(or_id);
                });
                var st_or = or_ids.join(",");

                let base_url = $("#BASE_URL").val();
                url = base_url + "/sorders/downloadinvoice/" + st_or
                window.open(url,'_blank');
                window.open(url);
            }
        }
    },
	OpenAddOrderPackageModal : function(){
		$('#OrderPackageModel').modal('toggle');
	},
	AddOrderPackage : function(){
		return orders_module.AddOrderPackageSubmitHandler();
	},
	AddOrderPackageSubmitHandler : function(){
		var PackingForm = $('#FRM_ADD_PACKING');
        var error3 = $('.alert-danger', PackingForm);
        var success3 = $('.alert-success', PackingForm);

        PackingForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
            	so_product_category : {
		       		 required: true
		       	 },
		       	so_package_weight : {
		       		required : true,
		       		number : true
		       	},
		       	so_package_cost : {
		       		number : true,
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
               $("#AjaxLoader").css({'display':'block'});
               var base_url = $('#BASE_URL').val();
	   	       // var _token = $('input[name=_token]').val();
	   	        var str_params = $("#FRM_ADD_PACKING").serialize();
	   	         $.ajax
	   	        ({
	   	            url : base_url + "/request/sorders/savepackingcategory",
	   	            data : str_params,
	   	            method : 'post',
	   	            dataType : "json",
	   	            success : function(response){
	   	              $("#AjaxLoader").css({'display':'none'});
	   	              if(response.is_error == 0)
	   	              {
	            	  	$.op_datatable.destroy();
	            	  	orders_module.DisplayListOrderProducts();
	            		$('#OrderPackageModel').modal('toggle');
	   	              }
	   	              else
   	            	  {
	   	            	  bootbox.alert(response.error_msg,function(){
	   	            		$("#SO_PRODUCT_CATEGORY").val('0');
	   	            		$("#SO_PACKAGE_WEIGHT").val('');
	   	            		$("#SO_PACKAGE_COST").val('');
	   	            		$('#OrderPackageModel').modal('toggle');
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
               },
               so_customer_payment : {
                   number :true
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
    	            url : base_url + "/request/sorders/saveorderinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/shipment/orders/editform/" + response.so_id ;
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
			            url : base_url + "/request/sorders/deleteorderinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
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
	    window.location.href = base_url + "/shipment/orders/editform/" + so_id;
	},
	getPackingPrice : function(){
		var so_id 		= $('input[name=so_id]').val();
		var so_package_weight 		= $('input[name=so_package_weight]').val();
		var category_id 	= $('#SO_PRODUCT_CATEGORY').val();
		var _token 		= $('input[name=_token]').val();
	    var base_url 	= $("#BASE_URL").val();
	    var params = { so_id : so_id , category_id : category_id , so_package_weight : so_package_weight ,  _token : _token };
	    $("#AjaxLoader").css({'display':'block'});
        $.ajax
        ({
            url : base_url + "/request/orders/getpackingprice",
            data : params,
            dataType : "Json",
            type : "POST",
            success : function(response){
               $("#AjaxLoader").css({'display':'none'});
              if(response.is_error == 0)
              {
            	 $('input[name=so_package_cost]').val(response.package_cost);

              }
              else{
                  bootbox.alert(response.error_msg);
                  $('input[name=so_package_cost]').val('');
              }
            }
        });
	}
};
