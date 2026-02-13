/**
 *
 */

invoices_module = {
		DisplayListInvoices : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var invoice_customer 	= $('#INVOICE_CUSTOMER').val();
			var invoice_client 	= $('select[name=invoice_client]').val();
			var invoice_bank 		= $('#INVOICE_BANK').val();
			var start_date 			= $('input[name=start_date]').val();
			var end_date 			= $('input[name=end_date]').val();
			var page_number 			= $('input[name=page_number]').val();
			var general_search 			= $('input[name=general_search]').val();
			var fisical_year 			= $('input[name=fisical_year]').val();
			$.ajax
			({
				url : base_url + "/request/billing/displaylistinvoices",
				data : { _token : _token , general_search : general_search , invoice_client : invoice_client, fisical_year : fisical_year , invoice_customer : invoice_customer , start_date : start_date , end_date : end_date , invoice_bank : invoice_bank , page_number : page_number },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstInvoices').html(response.display);
					  $('.group-checkable').change(function() {
                          var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                          var checked = $(this).prop("checked");
                          $(set).each(function() {
                              $(this).prop("checked", checked);
                          });
                          $.uniform.update(set);
                      });
                     $.pagination = $('#InvoicesPagination').twbsPagination({
                           totalPages: response.total_pages,
                           visiblePages: 7,
                           onPageClick: function (event, page) {
                                $('input[name=page_number]').val(page);
                                invoices_module.DisplayListInvoices();
                           }
                       });

					$("a[id*=EDIT_INVOICE_]").on('click',invoices_module.EditInvoiceInfo);
					$("a[id*=DELETE_INVOICE_]").on('click',invoices_module.DeleteInvoiceData);
				}
			});
		},
        DisplayListReturnInvoices : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var invoice_customer 	= $('#INVOICE_CUSTOMER').val();
			var invoice_client 	= $('select[name=invoice_client]').val();
			var invoice_bank 		= $('#INVOICE_BANK').val();
			var start_date 			= $('input[name=start_date]').val();
			var end_date 			= $('input[name=end_date]').val();
			var page_number 			= $('input[name=page_number]').val();
			var general_search 			= $('input[name=general_search]').val();
			var fisical_year 			= $('input[name=fisical_year]').val();
			$.ajax
			({
				url : base_url + "/request/billing/displaylistreturninvoices",
				data : { _token : _token , general_search : general_search , invoice_client : invoice_client, fisical_year : fisical_year , invoice_customer : invoice_customer , start_date : start_date , end_date : end_date , invoice_bank : invoice_bank , page_number : page_number },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstInvoices').html(response.display);
					  $('.group-checkable').change(function() {
                          var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                          var checked = $(this).prop("checked");
                          $(set).each(function() {
                              $(this).prop("checked", checked);
                          });
                          $.uniform.update(set);
                      });
                     $.pagination = $('#InvoicesPagination').twbsPagination({
                           totalPages: response.total_pages,
                           visiblePages: 7,
                           onPageClick: function (event, page) {
                                $('input[name=page_number]').val(page);
                                invoices_module.DisplayListReturnInvoices();
                           }
                       });

				}
			});
		},
        SearchInvoiceProductData : function(){
            var base_url 			= $('input[name=base_url]').val();
            var _token 				= $('input[name=_token]').val();
            var ca_client_code 				= $('input[name=ca_client_code]').val();
            var ca_invoice_code 				= $('input[name=ca_invoice_code]').val();
            $.ajax({
                url: base_url + "/request/billing/getinvoiceproducts",
                data: {_token: _token, ca_client_code : ca_client_code , ca_invoice_code : ca_invoice_code},
                method: 'get',
                dataType: "json",
                beforeSend: function () {
                },
                success: function (response) {
                    if(response.is_error == 1)
                    {
                        bootbox.alert(response.error_msg);
                    }
                    else
                    {
                        $('.ProductsForm').html("");
                        $('.ProductsForm').html(response.display);
                    }
                }
            });
        },
        SwitchOtherDropdownForProduct : function(){
            $("#BI_PRODUCT_CODE_ID").val($(this).val()).trigger('change.select2');
            let product_id = $(this).val();
            var base_url = $('#BASE_URL').val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url : base_url + "/request/billing/getproductdata",
                data : { _token : _token , product_id },
                method : 'get',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                    if(response.is_error == 0)
                    {
                        $('input[name=bi_item_price]').val(response.product_data.p_product_selling_price);
                        if(response.product_data.p_use_serialnumber == 1)
                        {
                            $('.SerialNumberHolder').css({display : "block"});
                        }
                        else
                        {
                            $('.SerialNumberHolder').css({display : "none"});
                        }

                    }
                }
            });

        },
        DisplayInternalCompaniesLst : function(){
            if($(this).is(':checked')) {
                $('.InternalCompanies').css({display : "block"});
                $('.SupplierDropdownHolder').css({display : "block"});
                $('.WarehouseDropdownHolder').css({display : "block"});
            } else {
                $('.InternalCompanies').css({display : "none"});
                $('.SupplierDropdownHolder').css({display : "none"});
                $('.WarehouseDropdownHolder').css({display : "none"});
                $('.SupplierDropdown').html('');
                $('.WarehouseDropdown').html('');
            }
        },

    DisplayCompanySupplierDropdown : function(){
        var base_url 			= $('input[name=base_url]').val();
        var _token 				= $('input[name=_token]').val();
        var bi_company_to 	= $('select[name=bi_company_to]').val();
        var internal_supplier_target 	= $('input[name=internal_supplier_target]').val();
        var internal_warehouse_target 	= $('input[name=internal_warehouse_target]').val();
        let params = {
            _token : _token,
            bi_company_to : bi_company_to,
            internal_supplier_target : internal_supplier_target,
            internal_warehouse_target : internal_warehouse_target,
        }

        let url = base_url + "/request/billing/getcompanysupplier";

        $.ajax({
            url : url,
            data : params,
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                 $('.SupplierDropdown').html(response.supplier_dropdown);
                $('.SupplierDropdown').find('select').select2();
                 $('.WarehouseDropdown').html(response.warehouse_dropdown);
                $('.WarehouseDropdown').find('select').select2();
            }
        });


    },
        SwitchPOtherDropdownForProduct : function(){
            $("#BI_PRODUCT_ID").val($(this).val()).trigger('change.select2');
            let product_id = $(this).val();
            var base_url = $('#BASE_URL').val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                url : base_url + "/request/billing/getproductdata",
                data : { _token : _token , product_id },
                method : 'get',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                    if(response.is_error == 0)
                    {
                        $('input[name=bi_item_price]').val(response.product_data.p_product_selling_price);
                        if(response.product_data.p_use_serialnumber == 0)
                        {

                        }
                    }
                }
            });
        },
        GetAccountInformation : function(){
                var base_url        = $('input[name=base_url]').val();
                var _token          = $('input[name=_token]').val();
                var bi_account_number          = $('input[name=bi_account_number]').val();
                            var params = { bi_account_number : bi_account_number , _token : _token };

                            $.ajax
                ({
                    url : base_url + "/request/billing/getaccountinfo",
                    data : params,
                    method : 'get',
                    dataType : "json",
                    beforeSend : function(){
                    },
                    success : function(response){
                                        if(response.is_error == 0)
                                        {
                                            $("#INVOICE_ACCOUNT").val(response.account_info.account_id);
                                            $("#INVOICE_ACCOUNT").attr('value',response.account_info.account_id);
                                            $("#INVOICE_ACCOUNT_ID").val(response.account_info.account_id).trigger('change');
                                        }

                                    }
                                });


        },
		ShowPaymentType : function() {
			var selected = $(this).val();
			var statusvalidate = $(this).find('option:selected').data('validatept');
			if(statusvalidate == 1)
			{
				$('.PaymentType').css({display : 'block'});
			}
			else
			{
				$('.PaymentType').css({display : 'none'});
			}
		},
		DeleteItemFromInvoice : function(){
			var _token 		= $("input[name=_token]").val();
			 var bi_id 		= $("input[name=bi_id]").val();
			 var base_url 	= $('input[name=base_url]').val();
			 var item_id 	= $(this).data('item_id');

			Swal.fire({
				  title: 'Are you sure you want to delete ?',
				  text: "You won't be able to revert this!",
				  icon: '',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.isConfirmed) {
					  	$.ajax
						({
							url : base_url + "/request/billing/deleteinvoiceitems",
							data : { _token : _token , item_id : item_id , bi_id : bi_id },
							method : 'post',
							dataType : "json",
							beforeSend : function(){
							},
							success : function(response){
								invoices_module.DisplayListInvoiceProducts();
							}
						});
				  }
				})

		},
		GenerateInvoiceCode : function(selected_date){
			var _token 		= $("input[name=_token]").val();
			var base_url 	= $('input[name=base_url]').val();
			 var pi_id 	= $('input[name=pi_id]').val();

			 if(pi_id != undefined)
				 return false;
			$.ajax
			({
				url : base_url + "/request/billing/generatecode",
				data : { _token : _token , selected_date : selected_date , type : "invoices" },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$("#BI_INVOICE_REF").val(response.code);
					$("#BI_INVOICE_CODE").val(response.code);
				}
			});

		},
		GetItemInvoiceInfo : function(){
			var _token 		= $("input[name=_token]").val();
			 var bi_id 		= $("input[name=bi_id]").val();
			 var base_url 	= $('input[name=base_url]').val();
			 var item_id 	= $(this).data('item_id');

			 $.ajax
				({
					url : base_url + "/request/billing/getinvoiceitem",
					data : { _token : _token , item_id : item_id , bi_id : bi_id },
					method : 'post',
					dataType : "json",
					beforeSend : function(){
					},
					success : function(response){
						 var item_type = $('select[name=bi_invoice_items_type]').val();
						 console.log(response.item_array);
						 if( item_type == 1 )
						 {
							 $("#InserItems").modal('toggle');
						 }
						 else
						{

							 $("input[name=item_id]").val(response.item_array.id);
							 $("#BI_SERVICE_ID").val(response.item_array.ii_item_id);
							 $("#BI_SERVICE_ID").trigger('change');
							 $("#II_SUPPLIER_ID").val(response.item_array.item_supplier);
							 $("#II_SUPPLIER_ID").trigger('change');
							 $("#InsertServices").find("input[name=item_id]").val(response.item_array.id);
							 $("#II_COST_PRICE").val(response.item_array.item_cost);
							 $("#PI_SERVICE_PRICE").val(response.item_array.item_price);
							 $("#InsertServices").modal('toggle');
						}
					}
				});

		},
		DisplayListInvoiceProducts : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var bi_id 				= $('input[name=bi_id]').val();
			$('#LstProducts').html("<img src='" + base_url + "/images/loader.gif' style='height:75px' />");
			$.ajax
			({
				url : base_url + "/request/billing/displaylistproductsinvoice",
				data : { _token : _token , bi_id : bi_id},
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstProducts').html(response.display);
				}
			});
		},
		DisplayListInvoicePayments : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var bi_id 				= $('input[name=bi_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/billing/displaylistpaymentsinvoice",
		        data : { _token : _token , bi_id : bi_id},
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstPaymentSplits').html(response.display);
		        }
		    });
		},
		CreateRemoveNumberofRows : function(){
			var new_rows 		= $("#NUMBER_PAYMENT").val();

			var existing_rows 	= $('table#LstPayments tr.Invoices').length;

			if(new_rows > existing_rows) // if new row value grater then the existing row we create the remaining number of rows
			{
				for (var i = existing_rows; i <= new_rows - 1; i++) {
					var emptyRow = $('tr.EmptyRow').clone();
					console.log(emptyRow);
					emptyRow.attr('class',"Invoices");
					emptyRow.attr('ID',"PAYMENT_" + i);
					emptyRow.css({display : ""});
					$('table#LstPayments tbody').append(emptyRow);
				}
			}
			else if(new_rows < existing_rows) // if the existing rows is greater then the number added to the field of new row we remove the remaining rows
			{
				for (var i = new_rows; i <= existing_rows - 1; i++) {
					$("#PAYMENT_" + i).remove();
				}
			}
		},
		RemoveCurrentRow : function(){
			$(this).parents('tr').fadeOut('fast',function(){
				$(this).remove();
			})
		},
                EditPaymentInfo : function(){
                    var ip_id = $(this).data('ip_id');
                    $('input[name=ip_id]').val(ip_id);
                   $('#EditBills').modal('toggle'); // Opens the modal
                },
                GetPaymentBillInfo : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var ip_id 				= $('input[name=ip_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/bills/getpaymentinfo",
		        data : { _token : _token , ip_id : ip_id},
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
                            $('input[name=ip_id]').val(response.payment_info.ip_id);
                            $('input[name=ip_billing_nbr]').val(response.payment_info.ip_billing_nbr);
                            $('input[name=ip_billing_date]').val(response.payment_info.ip_billing_date);
                            $('input[name=ip_updated_date]').val(response.payment_info.ip_updated_date);
                            $('input[name=ip_payment_doc]').val(response.payment_info.ip_payment_doc);
                            $('select[name=ip_collector_id]').val(response.payment_info.ip_collector_id).trigger('change');
                            $('select[name=ip_payment_type]').val(response.payment_info.ip_payment_type).trigger('change');
		        }
		    });
		},
		SaveNewRowsInfo : function(){
			var ip_payment_label = $("input[name='ip_payment_label[]']").map(function(){return $(this).val();}).get();
			var ip_payment_percentage = $("input[name='ip_payment_percentage[]']").map(function(){return $(this).val();}).get();
			var ip_payment_type = $("select[name='ip_payment_type[]']").map(function(){return $(this).val();}).get();
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var bi_id 				= $('input[name=bi_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/billing/savesplitpayments",
		        data : { _token : _token , bi_id : bi_id , ip_payment_type : ip_payment_type , ip_payment_percentage : ip_payment_percentage , ip_payment_label : ip_payment_label },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstPaymentSplits').html(response.display);
		        }
		    });

		},
		QuickActions : function(){
			var action_type = $(this).data('action_type');
			switch(action_type)
			{
				case "CONVERT_TO_OFFICIAL" :
				{
					invoices_module.ConvertInvoiceToOfficial();
				}
				break;
				case "REVERT_TO_DRAFT" :
				{
					invoices_module.RevertBacktodraft();
				}
				break;
                case "RETURN_INVOICE" :
				{
					invoices_module.ReturnInvoice();
				}
				break;
				case "PRINT_INVOICE" :
				{
					invoices_module.DownloadpdfInvoice();
				}
				break;
				case "CREATE_RECEIPT" :
				{
					invoices_module.CreateNewReceipt();
				}
				break;
			}
		},
		CreateNewReceipt : function(){
			var bi_id = $('input[name=bi_id]').val();
			var base_url 			= $('input[name=base_url]').val();
			window.location.href = base_url + "/billing/receipts/add?invoice_id=" + bi_id;
		},
		ConvertInvoiceToOfficial : function(){
			var bi_id = $('input[name=bi_id]').val();
			bootbox.confirm("Are you sure you want to Convert this Invoice to Official ?", function(result){
				//result
				if(result == true)
				{

				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={bi_id : bi_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/billing/convertinvoicetoofficial",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	 window.location.reload();
				              }
                              else
                              {
                                  bootbox.alert(response.error_msg);
                              }
				            }
				        });
				}
			});
		},
		PayReceipt : function(){
			var br_id = $(this).data('br_id');
			bootbox.confirm("Are you sure this Payment is Paid ?", function(result){
				//result
				if(result == true)
				{
					var base_url = $('#BASE_URL').val();
				    var _token = $('input[name=_token]').val();
			        var str_params ={br_id : br_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/billing/payreceipt",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  receipts_module.DisplayListInvoiceReceipts();
			              }
			            }
			        });
				}
			});
		},
		EditIReceiptForm : function(){
			var br_id = $(this).data('br_id');
			var bi_id = $("input[name=bi_id]").val();
			var base_url = $('#BASE_URL').val();
			window.location.href = base_url + "/billing/receipts/editireceipt/" + bi_id + "/" + br_id;

		},
		DownloadpdfInvoice : function(){
			var bi_id = $('input[name=bi_id]').val();
			 var base_url = $('#BASE_URL').val();
			var url = base_url + "/billing/invoices/downloadinvoice/" +  bi_id;
			window.open(url, '_blank');
		},
		GenerateReceiptsPayments : function(){
			var bi_id = $('input[name=bi_id]').val();
			 var base_url = $('#BASE_URL').val();
		      var _token = $('input[name=_token]').val();
		        var str_params ={bi_id : bi_id , _token : _token};
		         $.ajax
		        ({
		            url : base_url + "/request/billing/generatereceipts",
		            data : str_params,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
		              if(response.is_error == 0)
		              {
		          		receipts_module.DisplayListInvoiceReceipts();
		              }
		              else
	            	  {
		            	  bootbox.alert(response.error_msg);
	            	  }
		            }
		        });
		},
        ReturnInvoice : function(){
			var bi_id = $('input[name=bi_id]').val();
			 var base_url = $('#BASE_URL').val();
		      var _token = $('input[name=_token]').val();
		        var str_params ={bi_id : bi_id , _token : _token};
		         $.ajax
		        ({
		            url : base_url + "/request/billing/returninvoice",
		            data : str_params,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
                        let bi_id = response.bi_id;
                        let base_url = $('#BASE_URL').val();
                        let url = base_url + "/billing/invoices/returnproductpreview/" + bi_id;
                        window.open(url, '_blank').focus();
                        bootbox.alert(response.error_msg);
		            }
		        });
		},
        SaveReturnInvoiceProductInfo : function(){
            return invoices_module.SaveReturnInvoiceProductSubmitHandler();
        },
        SaveReturnInvoiceProductSubmitHandler : function(){
            var InvoiceReturnForm = $('#FORM_RETURN_INVOICE');

            InvoiceReturnForm.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: {
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
                    //  success3.hide();
                    // error3.show();
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
                    //success3.show();
                    //error3.hide();
                    var base_url = $('#BASE_URL').val();
                    // var _token = $('input[name=_token]').val();

                    var str_params = $("#FORM_RETURN_INVOICE").serialize();
                    $.ajax
                    ({
                        url : base_url + "/request/billing/savereturninvoice",
                        data : str_params,
                        method : 'post',
                        dataType : "json",
                        beforeSend : function(){
                        },
                        success : function(response){
                            if(response.is_error == 1)
                            {
                                bootbox.alert(response.error_msg);
                            }
                            else
                            {
                                let base_url = $('#BASE_URL').val();
                                let url = base_url + "/billing/returninvoices";
                                window.location.href = url;
                            }

                        }
                    });
                }

            });
        },
        SaveReturnInvoiceInfo : function(){
            return invoices_module.SaveReturnInvoiceSubmitHandler();
        },
        SaveReturnInvoiceSubmitHandler : function(){
            var InvoiceReturnForm = $('#FORM_RETURN_STOCK');

            InvoiceReturnForm.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: {
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
                    //  success3.hide();
                    // error3.show();
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
                    //success3.show();
                    //error3.hide();
                    var base_url = $('#BASE_URL').val();
                    // var _token = $('input[name=_token]').val();

                    var str_params = $("#FORM_RETURN_STOCK").serialize();
                    $.ajax
                    ({
                        url : base_url + "/request/billing/savereturninvoice",
                        data : str_params,
                        method : 'post',
                        dataType : "json",
                        beforeSend : function(){
                        },
                        success : function(response){
                            if(response.is_error == 0)
                            {
                                window.close();
                            }
                        }
                    });
                }

            });
        },
		OpenInsertItemsPopup : function(){

			$("#InserItems").modal('toggle');
		},
		OpenInsertServicesPopup : function(){
			$("#InsertServices").modal('toggle');
		},
		EditInvoiceInfo : function(){
			var bi_id = $(this).data('bi_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/billing/invoices/editform/" + bi_id;
		},
		EditReceiptForm : function(){
			var br_id = $(this).data('br_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/billing/ireceipts/editform/" + br_id;
		},
		DeleteInvoiceData : function(){
			 var bi_id = $(this).data('bi_id');
				bootbox.confirm("Are you sure you want to delete Invoice ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={bi_id : bi_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/billing/deleteinvoiceinfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  $.bi_datatable.destroy();
					            	  invoices_module.DisplayListInvoices();
					              }
					            }
					        });
					}
				});
		},
		SavePaymentInvoiceInfo : function(){
			return invoices_module.SavePaymentInvoiceSubmitHandler();
		},
                SavePaymentInvoiceSubmitHandler : function(){
                    var InvoiceBillForm = $('#FRM_SAVE_BILL');

			InvoiceBillForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
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
	               //  success3.hide();
	                // error3.show();
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
	                //success3.show();
	                //error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	       // var _token = $('input[name=_token]').val();

	    	        var str_params = $("#FRM_SAVE_BILL").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/savebillinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  invoices_module.DisplayListInvoicePayments();
	    	            	  $("#EditBills").modal('toggle');
	    	              }
	    	            }
	    	        });
	             }

	         });
                },
		SaveServiceInfo : function(){
			return invoices_module.SaveServiceSubmitHandler();
		},
		SaveServiceSubmitHandler : function(){
			var InvoiceServicesForm = $('#FRM_INVOICE_SERVICES');

			InvoiceServicesForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bi_service_id : {
	                     required: true
	                 },
	                 ii_supplier_id : {
	                     required: true
	                 },
	                 bi_service_price : {
	                     required: true,
	                     number : true
	                 },
	                 ii_cost_price : {
	                	 required: true,
	                     number : true
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
	               //  success3.hide();
	                // error3.show();
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
	                //success3.show();
	                //error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	       // var _token = $('input[name=_token]').val();

	    	        var str_params = $("#FRM_INVOICE_SERVICES").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/insertinvoiceservice",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  invoices_module.DisplayListInvoiceProducts();
	    	            	  $("#InsertServices").modal('toggle');
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
                DisplayProductInfo : function(){
                    var product_id = $("#BI_PRODUCT").val();
                    var base_url = $('#BASE_URL').val();
                    var _token = $('input[name=_token]').val();
                    $.ajax({
                        url : base_url + "/request/billing/getproductdata",
                        data : { _token : _token , product_id },
                        method : 'get',
                        dataType : "json",
                        beforeSend : function(){
                        },
                        success : function(response){
                          if(response.is_error == 0)
                          {
                               $('input[name=bi_item_price]').val(response.product_data.p_product_selling_price);
                          }
                        }
                    });
                },
		RevertBacktodraft : function(){
			 var bi_id = $("input[name=bi_id]").val();
			 var base_url = $('#BASE_URL').val();
  	       	 var _token = $('input[name=_token]').val();
			var params = { _token : _token , bi_id : bi_id };

			$.ajax
	        ({
	            url : base_url + "/request/billing/revertinvoicedraft",
	            data : params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  window.location.reload();
	              }
	            }
	        });

		},
    SaveLinkItem : function(){
			return invoices_module.SaveLinkItemSubmitHandler();
		},
        SaveLinkItemSubmitHandler : function(){
            var InvoiceItemsForm = $('#FORM_LINK_PRODUCT');
            //var error3 = $('.alert-danger', InvoiceItemsForm);
            //var success3 = $('.alert-success', InvoiceItemsForm);

            InvoiceItemsForm.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: {
                    ii_warehouse_id : {
                        required: true
                    },
                    bi_product_id : {
                        required: true
                    },
                    bi_quanity : {
                        required: true,
                        number : true
                    },
                    bi_item_price : {
                        required: true,
                        number : true
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
                    //success3.show();
                    //error3.hide();
                    var base_url = $('#BASE_URL').val();
                    // var _token = $('input[name=_token]').val();

                    var str_params = $("#FORM_LINK_PRODUCT").serialize();
                    $.ajax
                    ({
                        url : base_url + "/request/billing/linkinvoiceitems",
                        data : str_params,
                        method : 'post',
                        dataType : "json",
                        beforeSend : function(){
                        },
                        success : function(response){
                            if(response.is_error == 0)
                            {
                                invoices_module.DisplayListInvoiceProducts();
                            }
                            else
                            {
                                bootbox.alert(response.error_msg);
                            }

                            $("#FORM_LINK_PRODUCT button[type=reset]").trigger('click');
                            $("#FORM_LINK_PRODUCT select").trigger('change.select2');
                            $("#FORM_LINK_PRODUCT input[type=text]").val('');
                            $("#FORM_LINK_PRODUCT input[type=number]").val('0');
                        }
                    });
                }

            });
        },
        SaveItemsInfo : function(){
			return invoices_module.SaveInsertItemsSubmitHandler();
		},
		SaveInsertItemsSubmitHandler : function(){
			 var InvoiceItemsForm = $('#FRM_INVOICE_ITEMS');
	         var error3 = $('.alert-danger', InvoiceItemsForm);
	         var success3 = $('.alert-success', InvoiceItemsForm);

	         InvoiceItemsForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bi_product : {
	                     required: true
	                 },
	                 bi_quanity : {
	                     required: true,
                             number : true
	                 },
	                 bi_item_price : {
	                     required: true,
                             number : true
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
	                //success3.show();
	                //error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	       // var _token = $('input[name=_token]').val();

	    	        var str_params = $("#FRM_INVOICE_ITEMS").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/insertinvoiceitems",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  invoices_module.DisplayListInvoiceProducts();
                              bootbox.alert(response.error_msg,function(){
                                  $("#II_WAREHOUSE_ID").val(0).trigger('change.select2');
                                  $("#BI_PRODUCT").val(0).trigger('change.select2');
                                  $("input[name=ii_product_serial_number]").val('');
                                  $("input[name=bi_quanity]").val('0');
                                  $("input[name=bi_item_price]").val('0');
                                  $('#OrderProductsModel').modal('toggle');
                              });
	    	            	  $("#InserItems").modal('toggle');
	    	              }
                          else
                          {
                              bootbox.alert(response.error_msg);
                          }

                            $("#FRM_INVOICE_ITEMS")[0].reset();
	    	            }
	    	        });
	             }

	         });
		},
		SavenNewInvoiceInfo(){
			return invoices_module.SaveInvoicenNewInfoSubmitHandler();
		},
		SaveInvoiceInfo(){
			return invoices_module.SaveInvoiceInfoSubmitHandler();
		},
		SaveInvoicenNewInfoSubmitHandler : function(){
			var InvoiceForm = $('#FORM_SAVE_INVOICE');
	         var error3 = $('.alert-danger', InvoiceForm);
	         var success3 = $('.alert-success', InvoiceForm);

	         InvoiceForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bi_invoice_ref : {
	                     required: true
	                 },
	                 invoice_account : {
	                     required: true
	                 },
                     bi_exchange_rate : {
                       number : true
                     },
	                 bi_invoice_date : {
	                       required: true
	                 },
	                 bi_invoice_currency : {
	                	required :true
	                 },
                    bi_invoice_items_type : {
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

	                $("#BTN_SAVE_INVOICE").attr('disabled','disabled');
	   	         $("#BTN_SAVE_NEW").attr('disabled','disabled');

	    	        var str_params = $("#FORM_SAVE_INVOICE").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/saveinvoiceinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  window.location.href = base_url + "/billing/invoices/addform";
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		SaveInvoiceInfoSubmitHandler : function(){
			 var InvoiceForm = $('#FORM_SAVE_INVOICE');
	         var error3 = $('.alert-danger', InvoiceForm);
	         var success3 = $('.alert-success', InvoiceForm);
	         InvoiceForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bi_invoice_ref : {
	                     required: true
	                 },
	                 invoice_account : {
	                     required: true
	                 },
	                 bi_invoice_date : {
	                       required: true
	                 },
	                 bi_invoice_currency : {
	                	required :true
	                 },
                     bi_invoice_items_type : {
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

	                $("#BTN_SAVE_INVOICE").attr('disabled','disabled');
		   	         $("#BTN_SAVE_NEW").attr('disabled','disabled');

	    	        var str_params = $("#FORM_SAVE_INVOICE").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/billing/saveinvoiceinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	 if(response.action == "add")
	    	            		 window.location.href = base_url + "/billing/invoices/editform/" + response.bi_id;
	    	            	 else
	    	            		 window.location.href = base_url + "/billing/invoices";
	    	              }
	    	            }
	    	        });
	             }

	         });
		}
};
