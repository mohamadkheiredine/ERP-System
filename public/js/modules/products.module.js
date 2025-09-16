/**
 *
 */
products_module = {
		DisplayListStock : function(){
			var base_url 		= $('input[name=base_url]').val();
			var _token 			= $('input[name=_token]').val();
			var page_number		= $('input[name=page_number]').val();
			var general_search = $('select[name=general_search]').val();
			var stock_warehouse = $('select[name=stock_warehouse]').val();
			var stock_product	= $('select[name=stock_product]').val();
			var stock_currency 	= $('select[name=stock_currency]').val();
			var list_type 	= $('input[name=list_type]').val();
			$.ajax
			({
				url : base_url + "/request/displayliststock",
				data : { _token : _token , page_number : page_number ,  list_type : list_type , stock_warehouse : stock_warehouse , stock_product : stock_product , stock_currency : stock_currency , general_search : general_search },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#LstProductStocks').html(response.display);
					$('.TotalCost').html(response.total_amount_block);
					$('#StocksPagination').twbsPagination({
	                    totalPages: response.total_pages,
	                    visiblePages: 7,
	                    onPageClick: function (event, page) {
	                         $('input[name=page_number]').val(page);
	                         products_module.DisplayListStock();
	                    }
	                });
                    $('#TopStocksPagination').twbsPagination({
	                    totalPages: response.total_pages,
	                    visiblePages: 7,
	                    onPageClick: function (event, page) {
	                         $('input[name=page_number]').val(page);
	                         products_module.DisplayListStock();
	                    }
	                });
				}
			});
		},
        CalculateTotalPurchaseStock : function(){
            let quantity = $("#STOCK_QUANTITY").val();
            let is_price_stock = $("input[name=is_price_stock]").val();
            let is_selling_price = $("input[name=is_selling_price]").val();
            let total_price_stock = parseFloat(is_price_stock) * parseFloat(quantity);
            let total_selling_price = parseFloat(is_selling_price) * parseFloat(quantity);
            $("#TOTAL_PURCHASE_STOCK").html(total_price_stock);
            $("#TOTAL_SELLING_STOCK").html(total_selling_price);
        },
        AddTransferItems : function(){
            products_module.AddTransferItemsSubmitHandler();
        },
        AddTransferItemsSubmitHandler : function(){
                 var SaveTransferItems = $('#FORM_TRANSFER_ITEMS');
     var error3 = $('.alert-danger', SaveTransferItems);
     var success3 = $('.alert-success', SaveTransferItems);

     SaveTransferItems.validate({
         errorElement: 'span', //default input error message container
         errorClass: 'help-block help-block-error', // default input error message class
         focusInvalid: false, // do not focus the last invalid input
         ignore: "", // validate all fields including form hidden input
         rules: {
             mp_product_id : {
                 required: true
             },
                 mp_movement_quantity : {
                     required : true,
                     number : true
                 },
                 mp_item_notes : {
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

            var FormDataFields = $("form[id=FORM_TRANSFER_ITEMS]");

            var data = new FormData();
            var index = 0;

            FormDataFields.find('input,select,textarea').each(function(){
                    var name = $(this).attr('name');
                    var val = $(this).val();
                    data.append( name, val );

            });
                let list_transfer_items = $('input[name=list_transfer_items]').val();
                data.append( "list_transfer_items", list_transfer_items );

             var name = "warehouse_source";
             var val = $("select[name=warehouse_source]").val();
             data.append( name, val );

             var name = "warehouse_destination";
             var val = $("select[name=warehouse_destination]").val();
             data.append( name, val );

             $.ajax
            ({
                url : base_url + "/request/movements/additems",
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
                         $("#LST_TRANSFER_ITEMS").val(response.lst_items);
                         $("#LstTransferItems").html(response.display);

                         $("#MP_PRODUCT_ID").val(0);
                         $("#MP_MOVEMENT_QUANTITY").val("");
                         $("#MP_ITEM_NOTES").val("");

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
                DisplayProductDescriptionInStockTransfer : function(){
                    let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let p_id 				= $('select[name=mp_product_id]').val();

                        $.ajax
		    ({
		        url : base_url + "/request/stock/getproductinfo",
		        data : {p_id : p_id ,_token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#MP_ITEM_NOTES').val(response.p_product_description);
                    $('#MP_PRODUCT').val(response.p_id).trigger('change.select2');

		        }
		    });
                },
        DisplayPProductDescriptionInStockTransfer : function(){
            let  base_url 			= $('input[name=base_url]').val();
            let _token 				= $('input[name=_token]').val();
            let p_id 				= $('select[name=mp_product]').val();

            $.ajax
            ({
                url : base_url + "/request/stock/getproductinfo",
                data : {p_id : p_id ,_token : _token },
                method : 'post',
                dataType : "json",
                beforeSend : function(){
                },
                success : function(response){
                    $('#MP_PRODUCT_ID').val(response.p_id).trigger('change.select2');

                }
            });
        },
		CalculateDiscountedPrice : function(){
			var $this = $(this);
			var discount = $this.val();
			if(discount =='')
				discount = 0;
			var purchase_price = $('input[name=is_selling_price]').val();

			var new_price = purchase_price - ( purchase_price * discount/100);

			let whole_sales = $('input[name=is_wholesale_price]').val();
			let vendor_price =$('input[name=is_vendor_price]').val();
			if(whole_sales == '' || whole_sales > new_price)
				$('input[name=is_wholesale_price]').val(new_price)
			if(vendor_price == '' || vendor_price > new_price)
				$('input[name=is_vendor_price]').val(new_price)
		},
		DuplicateProduct : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let p_ids = [];

			$('input[name*=ck_pp_]:checked').each(function(){
				let p_id = $(this).val();
				p_ids.push(p_id);
			})

			let params = { _token : _token , p_ids : p_ids };

			$.ajax
		    ({
		        url : base_url + "/request/products/duplicateproducts",
		        data : params,
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

		        	products_module.DisplayListProducts();

		        }
		    })

		},
		GetZonesDropdown : function() {
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let warehouse_id 		= $("#FK_WWAREHOUSE_ID").val();
			let params = { _token : _token , warehouse_id : warehouse_id };


			$.ajax
		    ({
		        url : base_url + "/request/products/getzonesdropdown",
		        data : params,
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

		        	$('.DefaultZone').html(response.dropdown)

		        }
		    });

		},
		GetFloorDropDown : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let zone_id 			= $(this).val();
			let params = { _token : _token , zone_id : zone_id };

			$.ajax
		    ({
		        url : base_url + "/request/products/getfloorsdropdown",
		        data : params,
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

		        	$('.DefaultFloor').html(response.dropdown)

		        }
		    });

		},
		QuickActions : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let action 				= $(this).data('action');
			switch(action)
			{
				case "EXPORT_CSV":
				{

				}
				break;
                case "ADD_SERIALNUMBERS":
                {
                    products_module.ManageStockUnitIds();
                }
                break;
                case "IMPORT_PRODUCTS":
                {
                 // $('#myModal').modal('show');
                }
                break;
				case "DOWNLOAD_TEMPLATE":
				{
					$.ajax({
                                            url: base_url + "/request/products/downloadtemplate?_token=" + _token,
                                            method: "GET",
                                            success: function(data) {

                                                const blob = new Blob([data]);
                                                // Create a Blob URL for the binary data
                                                var blobUrl = window.URL.createObjectURL(blob);
                                                // Create a temporary anchor element
                                                var a = document.createElement('a');
                                                a.href = blobUrl;
                                                a.download = 'products-template.csv'; // Set the desired file name

                                                // Programmatically trigger a click on the anchor to start the download
                                                document.body.appendChild(a);
                                                a.click();

                                                // Clean up resources
                                                window.URL.revokeObjectURL(blobUrl);
                                                document.body.removeChild(a);
                                            },
                                            error: function(xhr, status, error) {
                                                console.error("Error downloading file:", error);
                                            }
                                        });
				}
				break;
			}
		},
                DownloadTransferStock : function(){
                    var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
                     var sm_ids = [];
                    $(".checkboxes:checked").each(function(){
                            var sm_id = $(this).val();
                            sm_ids.push(sm_id);
                    });
                    var str_smIds = sm_ids.join(",");
                    $.ajax
                    ({
                        url : base_url + "/request/stocktransfer/generatetransfervoucher",
                        data : { _token : _token , sm_id : str_smIds },
                        method : 'post',
                         xhrFields: {
                            responseType: 'blob' // Set the response type to blob
                        },
                        success: function(blob, status, xhr) {
                            // Get the filename from the Content-Disposition header if available
                            var filename = "";
                            var disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                var matches = filenameRegex.exec(disposition);
                                if (matches != null && matches[1]) {
                                    filename = matches[1].replace(/['"]/g, '');
                                }
                            }

                            // Fallback filename if none is provided
                            if (!filename) {
                                filename = "stock_transfer_voucher.pdf";
                            }

                            // Create a temporary link element
                            var link = document.createElement('a');
                            var url = window.URL.createObjectURL(blob);
                            link.href = url;
                            link.download = filename;

                            // Append link to the body
                            document.body.appendChild(link);
                            link.click();

                            // Remove the link and revoke the object URL
                            document.body.removeChild(link);
                            window.URL.revokeObjectURL(url);
                        },
                        error: function(xhr, status, error) {
                            console.error("File download failed:", error);
                        }
                });
                },
                ImportProducts : function(e){
                    e.preventDefault();
                    var FormDataFields = $("form[id=FRM_IMPORT_PRODUCTS]");
                    var base_url = $("#BASE_URL").val();
                    var data = new FormData();
                    var index = 0;

                    $.each($("input[type=file]"), function(i, obj) {
                            var name = $(this).attr('name');
                            $.each(obj.files,function(j,file){
                                    data.append(name, file);
                            })
                    });

                    FormDataFields.find('input').each(function(){
                            data.append($(this).attr('name'), $(this).val() );
                    });
                     $.ajax
                    ({
                        url : base_url + "/request/products/uploadlistproducts",
                        data : data,
                        async: false,
                        cache: false,
                        method : 'post',
                        contentType: false,
                        processData: false,
                        dataType : "json",
                        success : function(response){
                          if(response.is_error == 0)
                          {
                             window.location.href = base_url + "/inventory/products";
                          }
                        }
                    });
                },
		GenerateBarCode : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let p_barcode 			= $('input[name=p_bar_code]').val();
			let params = { _token : _token , p_barcode : p_barcode };
			$('input[name=p_bar_code]').attr('disabled','disabled');
			$.ajax
		    ({
		        url : base_url + "/request/products/generatebarcode",
		        data : params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('input[name=p_bar_code]').removeAttr('disabled');
		        	$('input[name=p_barecode_img]').val(response.bar_code_png);
		        	$('input[name=p_barecode]').val(response.p_barcode);
		        	$('.lblbarcode').html(response.p_barcode);
		        	$('img#BARCODE_IMG').attr('src',"data:image/png;base64," + response.bar_code_png );
		        }
		    })

		},
		OpenProductLabels : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
			let  is_id 				= $("input[name=is_id]").val();
			let url =  base_url + "/products/stocks/displaybarodelabels/" + is_id;

			window.open(url,'_blank');
		},
		ChangeCurrencyLabel : function(){
			let currency_text = $("#P_PRODUCT_CURRENCY option:selected").text();
			let textArray = currency_text.split(" - ");
			$('.CurrencyCode').html(textArray[0]);
		},
		DisplayMetricProduct : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var p_product_unit_type	= $("#P_PRODUCT_UNIT_TYPE").val();
			var p_id 				= $("#P_ID").val();
			$.ajax
		    ({
		        url : base_url + "/request/products/displaymetricsection",
		        data : { _token : _token , p_product_unit_type : p_product_unit_type , p_id : p_id},
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$("#ProductSizeInfo").html(response.display);
		        	$("#ProductSizeInfo select").each(function(){
		        		$(this).select2();
		        	})
		        }
		    })
		},
		DisplayProductInfo : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			var p_id 		= $('#P_ID').val();
		    $.ajax
		    ({
		        url : base_url + "/request/stock/getproductinfo",
		        data : { _token : _token , p_id : p_id},
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	let is_id = $('input[name=is_id]').val();
		        	if(is_id == null)
		        	{
			        	$("input[name=is_price_stock]").val(response.p_product_selling_price);
			        	$("select[name=is_stock_currency]").val(response.p_product_currency);
			        	$("input[name=is_selling_price]").val(response.p_product_selling_price);
			        	$("input[name=is_wholesale_price]").val(response.p_product_selling_price);
			        	$("input[name=is_vendor_price]").val(response.p_product_selling_price);
		        	}

		        	let old_quantity = $('input[name=is_quanity]').val();
		        	if(response.stock_has_serial_number == 1)
	        		{
		        		$('.StockSerialNumber').css({'display' : 'none'});
		        		$('.AddItemHolder').css({'display' : ''});
		        		$('input[name=p_id]').attr({'required' : 'required'});
		        		if(old_quantity <= 0)
		        			$('input[name=is_quanity]').val(0);
		        		$('input[name=is_stock_uid]').removeAttr('required');
	        		}
		        	else
	        		{
		        		$('.StockSerialNumber').css({'display' : ''});
		        		$('.AddItemHolder').css({'display' : 'none'});
		        		$('input[name=p_id]').removeAttr('required');

		        		if(old_quantity <= 1)
		        			$('input[name=is_quanity]').val(1);
	        		}

                    products_module.CalculateTotalPurchaseStock();
		        }
		    });
		},
		ManageStockUnitIds : function(){
			let is_id 		= $('input[name=is_id]').val();
			let serial_ids = $('input[name=serial_ids]').val();
			var base_url 	= $('input[name=base_url]').val();
			var myWindow = window.open(base_url + "/stock/addserialnumbers?is_id=" + is_id + "&serial_ids=" + serial_ids, "Serial Numbers", "width=400,height=600");
		},
		DisplayListProducts  : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var page_number 		= $('input[name=page_number]').val();
		var general_search 		= $('input[name=general_search]').val();
		var product_category 	= $('select[name=product_category]').val();
		var product_currency 	= $('select[name=product_currency]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/products/displaylist",
	        data : { _token : _token , page_number : page_number , general_search : general_search , product_category : product_category , product_currency : product_currency},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	            $('#LstProducts').html(response.display);
	            $.pagination = $('#ProductsPagination').twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                         $('input[name=page_number]').val(page);
                         products_module.DisplayListProducts();
                    }
                });
	        	$("a[id*=EDIT_PRODUCT_]").on('click',products_module.EditProductInfo);
	        	$("a[id*=DELETE_PRODUCT_]").on('click',products_module.DeleteProductInfo);
	        }
	    });
	},
	DisplayStock : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var p_id 		= $('input[name=p_id]').val();
		$.ajax
		({
			url : base_url + "/request/products/displayliststocks",
			data : { _token : _token , p_id : p_id},
			method : 'post',
			dataType : "json",
			beforeSend : function(){
			},
			success : function(response){
				$('.LstStocks').html(response.display);
			}
		});
	},
	GetZonesDropDown : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var warehouse_id 		= $('select[name=fk_warehouse_id]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/general/getdropdown/zones",
	        data : { _token : _token , warehouse_id : warehouse_id},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('.WarehouseZonesDropDown').html(response.html);
	            $('select').select2();
	        }
	    });
	},
	DisplayStockMovement : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
		var p_id 		= $('input[name=p_id]').val();
		$.ajax
		({
			url : base_url + "/request/products/displayliststockmovements",
			data : { _token : _token , p_id : p_id},
			method : 'post',
			dataType : "json",
			beforeSend : function(){
			},
			success : function(response){
				$('.LstStockMovement').html(response.display);
			}
		});
	},
	DisplayAllStockMovement : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/stockmovements/displaylist",
	        data : { _token : _token},
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	            $('#LstTransferStocks').html(response.display);

	        }
	    });
	},
	DeleteProductInfo : function(){
		var p_id = $(this).data('p_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
				var base_url = $('#BASE_URL').val();
				var _token = $('input[name=_token]').val();
				var str_params ={p_id : p_id , _token : _token};
				$.ajax
				({
					url : base_url + "/request/products/deleteproductinfo",
					data : str_params,
					dataType : "Json",
					type : "POST",
					success : function(response){
						if(response.is_error == 0)
						{
							products_module.DisplayListProducts();
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
	DeleteStockInfo : function(){
		 var is_id = $(this).data('is_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={is_id : is_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/deletestock",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  products_module.DisplayListStock();
			              }
			            }
			        });
			}
		});
	},
	ProductTransferStock : function(){
		var p_id = $("input[name=p_id]").val();
		var base_url = $("#BASE_URL").val();
		window.location.href = base_url + "/inventory/products/transferstocks/" + p_id;
	},
	EditProductInfo : function(){
		var p_id = $(this).data('p_id');
		var base_url = $("#BASE_URL").val();
		window.location.href = base_url + "/inventory/editproduct/" + p_id;
	},
	EditStockInfo : function(){
		var is_id = $(this).data('is_id');
		var base_url = $("#BASE_URL").val();
		window.location.href = base_url + "/inventory/editstock/" + is_id;
	},
	AddProductStock : function(){
		var p_id = $("input[name=p_id]").val();
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/inventory/AddNewStock/" + p_id;
	},
	ApplyTransferStock : function(){
		return products_module.ApplyTransferStockSubmitHandler();
	},
	ApplyTransferStockSubmitHandler : function(){
		var TransferStockForm = $('#FORM_TRANSFER_SOCKET');
        var error3 = $('.alert-danger', TransferStockForm);
        var success3 = $('.alert-success', TransferStockForm);

        TransferStockForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
            	warehouse_source  : {
            		required: true
            	},
            	warehouse_destination  : {
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

   	        var str_params = $("#FORM_TRANSFER_SOCKET").serialize();
   	         $.ajax
   	        ({
   	            url : base_url + "/request/products/stocktransfer",
   	            data : str_params,
   	            method : 'put',
   	            dataType : "json",
   	            success : function(response){

   	             var main_transfer = $("#MAIN_TRANSFER").val();
   	             var url;
   	             if(main_transfer == 1)
                        {
                               url = base_url + "/inventory/stocktransfer";
                        }
                            else
                        {
                               url = base_url + "/inventory/editproduct/" + p_id;
                        }

   	              if(response.is_error == 0)
   	              {
   	            	 var p_id = $("input[name=p_id]").val();
   	                 window.location.href = url;
   	              }
   	              else{
   	            	  bootbox.alert(response.error_msg);
   	              }
   	            }
   	        });
            }

        });
	},
	CreateProductStock : function(){
		return products_module.CreateProductStockSubmitHandler();
	},
	CreateProductStockSubmitHandler : function(){
		 var StockForm = $('#FORM_SAVE_SOCKET');
	        var error3 = $('.alert-danger', StockForm);
	        var success3 = $('.alert-success', StockForm);

	        StockForm.validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block help-block-error', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "", // validate all fields including form hidden input
	            rules: {
		        	p_id  : {
		        	  required: true
		        	},
		           	fk_warehouse_id  : {
		           	  required: true
		            },
		            is_quanity : {
		              required: true,
			        	number : true
			        },
			        is_discount : {
			        	number : true,
			        	required : true
			        },
			        is_wholesale_price : {
			        	number : true,
			        	required : true
			        },
			        is_vendor_price : {
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
	               var base_url = $('#BASE_URL').val();

	   	        var str_params = $("#FORM_SAVE_SOCKET").serialize();
	   	         $.ajax
	   	        ({
	   	            url : base_url + "/request/savestockinfo",
	   	            data : str_params,
	   	            method : 'post',
	   	            dataType : "json",
	   	            success : function(response){
	   	              if(response.is_error == 0)
	   	              {
	   	            	 var p_id = $("input[name=p_id]").val();
	   	                 window.location.href = base_url + "/inventory/editproduct/" + p_id;
	   	              }
	   	            }
	   	        });
	            }

	        });
	},
	GenerateStockInfo : function(){
		return products_module.GenerateStockSubmitHandler();
	},
	GenerateStockSubmitHandler : function(){
		 var StockForm = $('#FORM_SAVE_SOCKET');
        var error3 = $('.alert-danger', StockForm);
        var success3 = $('.alert-success', StockForm);

        StockForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
	        	p_id  : {
	        	  required: true
	        	},
	           	fk_warehouse_id  : {
	           	  required: true
	            },
	            is_quanity : {
	              required: true
		        },
		        is_price_stock : {
		        	number : true,
		        	required : true
		        }
            },

            messages: { // custom messages for radio buttons and checkboxes
            	serial_ids : "Please Add Serial Numbers related to this Stock Package"
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
               var serial_ids = $('input[name=serial_ids]').val();
               if( serial_ids.length == 0 && $('input[name=serial_ids]').is('required') )
        	   {
            	   bootbox.alert('Please Add all Serial Numbers Releated to this Stock Record');
            	   return false;
        	   }

   	        var str_params = $("#FORM_SAVE_SOCKET").serialize();
   	         $.ajax
   	        ({
   	            url : base_url + "/request/savestockinfo",
   	            data : str_params,
   	            method : 'post',
   	            dataType : "json",
   	            success : function(response){
   	              if(response.is_error == 0)
   	              {
   	            	 var p_id = $("input[name=p_id]").val();
   	                 window.location.href = base_url + "/inventory/stocks";
   	              }
   	            }
   	        });
            }

        });
	},
	DisplayExchangeRate : function(){
		 var base_url = $('#BASE_URL').val();
	      var _token = $('input[name=_token]').val();
	      var exchange_rate_from = $('input[name=company_currency]').val();
	      var exchange_rate_to = $('select[name=is_stock_currency]').val();
	      $('input[name=is_stock_exchange_rate]').attr('disabled','disabled');
	      $('input[name=is_stock_exchange_rate]').css({opacity : '0.6'});
	        var str_params ={exchange_rate_from : exchange_rate_from , _token : _token , exchange_rate_to : exchange_rate_to};
	         $.ajax
	        ({
	            url : base_url + "/request/general/getexchangerate",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $('input[name=is_stock_exchange_rate]').val(response.exchange_rate);
	            	  $('input[name=is_stock_exchange_rate]').removeAttr('disabled');
	        	      $('input[name=is_stock_exchange_rate]').css({opacity : '1'});
	              }
	            }
	        });
	},
	SaveStockInfo : function(){
		return products_module.SaveStockSubmitHandler();
	},
	SaveStockSubmitHandler : function(){
		 var ProductForm = $('#FORM_ADD_SOCKET');
         var error3 = $('.alert-danger', ProductForm);
         var success3 = $('.alert-success', ProductForm);

         ProductForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
        	   fk_warehouse_id  : {
            	required: true
               },
               is_quanity : {
                     required: true
	           },
		        is_price_stock : {
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
                var base_url = $('#BASE_URL').val();

    	        var str_params = $("#FORM_ADD_SOCKET").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/products/addstock",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	            	 var p_id = $("input[name=p_id]").val();
    	                 window.location.href = base_url + "/inventory/editproduct/" + p_id;
    	              }
    	            }
    	        });
             }

         });
	},
	SaveProductInfo : function(){
		return products_module.SaveProductSubmitHandler();
	},
    SaveAndNewProductInfo : function(){
		return products_module.SaveAndNewProductSubmitHandler();
	},
    SaveAndNewProductSubmitHandler : function(){
        var ProductForm = $('#FORM_SAVE_PRODUCT');
        var error3 = $('.alert-danger', ProductForm);
        var success3 = $('.alert-success', ProductForm);

        ProductForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                p_product_stock_alert : {
                    required : true,
                    number:true
                },
                fk_pc_id : {
                    required: true
                },
                p_product_quantity : {
                    number:true
                },
                p_product_weight : {
                    required: true,
                    number:true
                },
                p_product_weight_unit : {
                    required: true
                },
                p_product_length : {
                    required: true,
                    number:true
                },
                p_product_length_unit : {
                    required: true
                },
                p_product_width : {
                    required: true,
                    number:true
                },
                p_product_width_unit : {
                    required: true
                },
                p_product_height : {
                    required: true,
                    number:true
                },
                p_product_height_unit : {
                    required: true
                },
                p_product_area : {
                    required: true,
                    number:true
                },
                p_product_area_unit : {
                    required: true
                },
                p_product_selling_price : {
                    required : true,
                    number:true
                },
                p_product_min_selling_price : {
                    number:true
                },
                p_product_currency : {
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

                var FormDataFields = $("form[id=FORM_SAVE_PRODUCT]");

                var data = new FormData();
                var index = 0;

                $.each($("input[type=file]"), function(i, obj) {
                    var name = $(this).attr('name');
                    $.each(obj.files,function(j,file){
                        data.append(name, file);
                    })
                });

                FormDataFields.find('input,select').each(function(){
                    data.append($(this).attr('name'), $(this).val() );
                });
                const p_product_description  = $.editor.getData();

                data.append("p_product_description", p_product_description );
                $.ajax
                ({
                    url : base_url + "/request/products/saveproductinfo",
                    data : data,
                    async: false,
                    cache: false,
                    method : 'post',
                    contentType: false,
                    processData: false,
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/inventory/addnewproduct";
                        }
                    }
                });
            }

        });
    },
	SaveProductSubmitHandler : function(){
		 var ProductForm = $('#FORM_SAVE_PRODUCT');
         var error3 = $('.alert-danger', ProductForm);
         var success3 = $('.alert-success', ProductForm);

         ProductForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
               p_product_stock_alert : {
	          	 required : true,
	          	 number:true
	           },
               fk_pc_id : {
                 required: true
	           },
	           p_product_quantity : {
                 number:true
	           },
               p_product_weight : {
                 required: true,
                 number:true
	           },
	           p_product_weight_unit : {
                 required: true
               },
               p_product_length : {
                 required: true,
                 number:true
               },
               p_product_length_unit : {
	             required: true
               },
               p_product_width : {
                 required: true,
                 number:true
	           },
	           p_product_width_unit : {
	             required: true
	           },
	           p_product_height : {
	             required: true,
	             number:true
               },
               p_product_height_unit : {
                 required: true
               },
               p_product_area : {
                 required: true,
                 number:true
               },
               p_product_area_unit : {
                 required: true
               },
               p_product_selling_price : {
            	 required : true,
            	 number:true
               },
               p_product_min_selling_price : {
	          	 number:true
	           },
               p_product_currency : {
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

    	        var FormDataFields = $("form[id=FORM_SAVE_PRODUCT]");

    	        var data = new FormData();
    	        var index = 0;

    	        $.each($("input[type=file]"), function(i, obj) {
    	                var name = $(this).attr('name');
    	                $.each(obj.files,function(j,file){
    	                        data.append(name, file);
    	                })
    	        });

    	        FormDataFields.find('input,select').each(function(){
    	                data.append($(this).attr('name'), $(this).val() );
    	        });
    	        const p_product_description  = $.editor.getData();

    	        data.append("p_product_description", p_product_description );
    	         $.ajax
    	        ({
    	            url : base_url + "/request/products/saveproductinfo",
    	            data : data,
    	            async: false,
    	            cache: false,
    	            method : 'post',
    	            contentType: false,
    	            processData: false,
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/inventory/products";
    	              }
    	            }
    	        });
             }

         });
	}
};
