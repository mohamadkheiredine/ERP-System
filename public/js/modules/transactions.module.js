/**
 *
 */

transactions_module = {
		DisplayListLedger : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var start_date 				= $('input[name=start_date]').val();
			var page_number 			= $('input[name=page_number]').val();
		    var end_date 				= $('input[name=end_date]').val();
		    var journal_id 				= $('select[name=fk_acc_journal_id]').val();
		    var tm_ledger_account 		= $('select[name=tm_ledger_account]').val();
		    var tm_sub_ledger_account 	= $('select[name=tm_sub_ledger_account]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistmovements",
		        data : { _token : _token , start_date : start_date , end_date : end_date , journal_id : journal_id , tm_ledger_account : tm_ledger_account , tm_sub_ledger_account : tm_sub_ledger_account , page_number : page_number },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstLedger').html(response.display);
		        	$.pagination = $('#LedgerPagination').twbsPagination({
	                    totalPages: response.total_pages,
	                    visiblePages: 7,
	                    onPageClick: function (event, page) {
	                         $('input[name=page_number]').val(page);
	                         transactions_module.DisplayListLedger();
	                    }
	                });
		        	$("a[id*=EDIT_TRANS_]").on('click',transactions_module.EditTransactionInfo);
					$("a[id*=DELETE_TRANS_]").on('click',transactions_module.DeleteTransactionInfo);
					$("a[id*=DELETE_MOVEMENT_]").on('click',transactions_module.DeleteMovementInfo);
		        }
		    });
		},
		PrintAccountStatment : function(){
			var _token 					= $('input[name=_token]').val();
			var acc_account 				= $('select[name=acc_account]').val() != undefined ?  $('select[name=acc_account]').val() :  $('input[name=detail_account_id]').val();
			var base_url 				= $('input[name=base_url]').val();
			var currency_id 				= $('input[name=currency_id]').val();
			var start_date 				= $('input[name=start_date]').val();
			var end_date 				= $('input[name=end_date]').val();
		    var fisical_year 				= $('input[name=fisical_year]').val();
			var url = base_url + "/accounting/printaccountstatment/?_token=" + _token + "&acc_account=" + acc_account + "&currency_id=" + currency_id + "&start_date=" + start_date + "&end_date=" + end_date;
			window.open(url, '_blank');
		},
		PrintAllAccountStatmentDetails : function(){
			var _token 					= $('input[name=_token]').val();
		    var acc_account 				= $('select[name=acc_account]').val() != undefined ?  $('select[name=acc_account]').val() :  $('input[name=detail_account_id]').val();
		    var base_url 				= $('input[name=base_url]').val();
		    var fisical_year 				= $('input[name=fisical_year]').val();
		    var ck_include_before 				= $('input[name=ck_include_before]:checked').length == 1 ? 1 : 0;
			var url = base_url + "/accounting/printaccountstatment/?_token=" + _token + "&acc_account=" + acc_account + "&fisical_year=" + fisical_year + "&ck_include_before=" + ck_include_before;
			window.open(url, '_blank');
		},
		DisplayListAccountBalance : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var start_date 				= $('input[name=start_date]').val();
		    var end_date 				= $('input[name=end_date]').val();
		    var acc_account_payable 	= $('select[name=acc_account_payable]').val();
		    var acc_account_receivable 	= $('select[name=acc_account_receivable]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistaccountbalance",
		        data : { _token : _token , start_date : start_date , end_date : end_date , acc_account_payable : acc_account_payable , acc_account_receivable : acc_account_receivable },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstAccountBalance').html(response.display);
		        }
		    });
		},
		DisplayListAccountStatment : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var start_date 				= $('input[name=start_date]').val();
			var end_date 				= $('input[name=end_date]').val();
			var search_query 				= $('input[name=search_query]').val();
		    var acc_account 				= $('select[name=acc_account]').val();
		    var ck_include_before 				= $('input[name=ck_include_before]:checked').length == 1 ? 1 : 0;
		    var params = { start_date : start_date ,end_date : end_date , search_query : search_query ,acc_account: acc_account, _token : _token , include_before : ck_include_before };
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistaccountstotals",
		        data : params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstAccountStatment').html(response.display);
		        }
		    });
		},
		DisplayListStatmentDetails : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var start_date 				= $('input[name=start_date]').val();
			var end_date 				= $('input[name=end_date]').val();
			var search_query 				= $('input[name=search_query]').val();
		    var acc_account 				= $('select[name=acc_account]').val();
		    var fisical_year = $('input[name=fisical_year]').val();
		    var ck_include_before 				= $('input[name=ck_include_before]:checked').length == 1 ? 1 : 0;
		    var params = { start_date : start_date ,end_date : end_date , acc_account : acc_account , search_query : search_query , _token : _token , include_before : ck_include_before , fisical_year : fisical_year};
		    $("#LstAccountStatment").html("<div style='width:100%;text-align:center' align='center'><img src='" +  base_url  + "/images/loader.gif' /></div>")
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistaccountstotals",
		        data : params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstAccountStatment').html(response.display);

		        }
		    });
		},
		OpenTransactionDetailsWindow : function(){
			var tm_id 		= $(this).data('tm_id');
			var tran_id 	= $(this).data('tran_id');
			var base_url 	= $('input[name=base_url]').val();
			var fisical_year = $('input[name=fisical_year]').val();

			window.open(base_url + "/transaction/transactiondetails/" + tran_id + "/" + tm_id + "?fisical_year=" + fisical_year, "_blank");

		},
		ShowAccounttransactionDetails : function($this = undefined){
			var base_url 						= $('input[name=base_url]').val();
			var _token 							= $('input[name=_token]').val();
			var order_by 						= $('select[name=order_by]').val();
			var search_query 					= $('input[name=search_query]').val();
			var start_date 						= $('input[name=start_date]').val();
			var end_date 						= $('input[name=end_date]').val();
			var ck_include_before 				= $('input[name=ck_include_before]').is(':checked') ? 1 : 0;
			var fisical_year 					= $('input[name=fisical_year]').val();
			//account_id

			var def_account_id = 0;
			if($this == undefined)
			{
				def_account_id = $('input[name=detail_account_id]').val();

			}
			else
			{
				def_account_id =  $this.data('account_id');
				$('input[name=detail_account_id]').val(def_account_id);
			}

			var currency_id 			= 0;
			if($this == undefined )
				currency_id = $('input[name=sel_currency_id]').val();
			else
				currency_id = $this.data('currency_id');
			$('input[name=sel_currency_id]').val(currency_id);
		    $("#LstAccountStatment").html("<div style='width:100%;text-align:center' align='center'><img src='" +  base_url  + "/images/loader.gif' /></div>")
			var params = { account_id : def_account_id ,currency_id : currency_id , _token : _token , start_date : start_date , end_date : end_date , order_by : order_by , search_query : search_query , fisical_year : fisical_year , ck_include_before : ck_include_before };
			 $.ajax
			    ({
			        url : base_url + "/request/accounting/showtransactionaccountdetails",
			        data : params,
		            method : 'post',
		            dataType : "json",
		            beforeSend : function(){
		            },
			        success : function(response){
			        	$('#LstAccountStatment').html(response.display);
			        	$('button[name=btn_export]').on('click',transactions_module.PrintAccountStatment);
			        }
			    });
		},
		DisplayListEmptyTransactions : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var start_date 				= $('input[name=start_date]').val();
		    var end_date 				= $('input[name=end_date]').val();
		    var journal_id 				= $('select[name=fk_acc_journal_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylistemptytransactions",
		        data : { _token : _token , start_date : start_date , end_date : end_date , journal_id : journal_id },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		        	$('#LstEmptyTransactions').html(response.display);
		        	$.transactions_datatable = $('#LstEmptyTransactions').mDatatable({

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
	        					field: 'id',
	        					type: 'number',
	        					sortable: true,
	        					width: 50,
	        				},
	        				{
	        					field: 'Date',
	        					type: 'date',
	        					sortable: true,
	        					width: 200
	        				},
	        				{
	        					field: 'Accounting Doc',
	        					type: 'text',
	        					sortable: true,
	        					width: 250
	        				},
	        				{
	        					field: 'Journal',
	        					type: 'text',
	        					sortable: true,
	        					width: 250
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
		        	$("a[id*=EDIT_TRANS_]").on('click',transactions_module.EditTransactionInfo);
					$("a[id*=DELETE_TRANS_]").on('click',transactions_module.DeleteTransactionInfo);
		        }
		    });
		},
		AddNewMovementRow : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			$.ajax
		    ({
		        url : base_url + "/request/accounting/addnewmovementrows",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		        	$("#TRANS_MOVEMENTS").append(response.display);
		        	$(".row-select").select2();
		    		$("a.RemoveMov").on('click',transactions_module.RemoveCurrentMovementRow);
		        }
		    });
		},
		RemoveCurrentMovementRow : function(){
			var $this = $(this).parents('tr');
			bootbox.confirm("Are you sure you want to remove this row ?", function(result){
				$this.fadeOut("slow",function(){
					$(this).remove();
				})
			});
		},
		DisplayListMovements : function(){
			var base_url 				= $('input[name=base_url]').val();
			var _token 					= $('input[name=_token]').val();
			var at_id 				= $('input[name=at_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displaylisttransactionmovements",
		        data : { _token : _token , at_id : at_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstMovements').html(response.display);
		        	if(response.is_error == 1)
	        		{
	        	 	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
	        		}
		        	$("a[id*=EDIT_MOV_]").on('click',transactions_module.EditMovementInfo);
		        	$("a[id*=DELETE_MOV_]").on('click',transactions_module.DeleteMovementInfo);
		        }
		    });
		},
		EditTransactionInfo : function(){
			var at_id = $(this).data('at_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/accounting/transactions/editform/" + at_id;

		},
		EditMovementInfo : function(){
			var $this = $(this);
			var tm_id = $(this).data('tm_id');
			var _token 					= $('input[name=_token]').val();
		    var base_url = $("#BASE_URL").val();
		    $.ajax
		    ({
		        url : base_url + "/request/accounting/displayeditmovementrow",
		        data : { _token : _token , tm_id : tm_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$this.parents('tr').html(response.display);
		        	$("button[name=btn_save_row]").on('click',transactions_module.SaveMovementRowInfo);
		        }
		    });
		},
		DeleteTransactionInfo : function(){
			 var at_id = $(this).data('at_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={at_id : at_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/accounting/deletetransactioninfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  $.ledger_datatable.destroy();
					            	  transactions_module.DisplayListLedger();
					              }
					            }
					        });
					}
				});
		},
		DeleteMovementInfo : function(){
			var tm_id = $(this).data('tm_id');
			var at_id = $(this).data('at_id');
			bootbox.confirm("Are you sure you want to delete this Movement ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={tm_id : tm_id , at_id : at_id  , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/accounting/deletemovementinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  $("#SUCCESS_MSG").css({display : ""});
				            	  $("#ERROR_MSG").css({display : "none"});
				            	  if($.ledger_datatable != null)
			            		  {
				            		  $.ledger_datatable.destroy();
					            	  transactions_module.DisplayListLedger();
			            		  }
				            	  else
				            	  {
				            		  transactions_module.DisplayListMovements();
				            	  }

				              }
				              else
			            	  {
				            	  $("#SUCCESS_MSG").css({display : "none"});
				            	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
			            	  }
				            }
				        });
				}
			});
		},
		SaveTransactionInfo : function(){
			return transactions_module.SaveTransactionSubmitHandler();
		},
		SaveTransactionSubmitHandler : function(){
			 var TransForm = $('#FORM_SAVE_TRANSACTION');
	         var error3 = $('.alert-danger', TransForm);
	         var success3 = $('.alert-success', TransForm);

	         TransForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 at_transaction_date : {
	            		 required: true
	            	 },
	            	 fk_acc_journal_id : {
	                     required: true
	                   },
	                   at_accounting_doc : {
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
	    	        var str_params = $("#FORM_SAVE_TRANSACTION").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/accounting/savetransactioninfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/accounting/transactions/editform/" + response.at_id;
	    	              }
	    	              else
    	            	  {
	    	            	  $("#SUCCESS_MSG").css({display : "none"});
	    	            	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
	    	            	  transactions_module.DisplayListMovements();
    	            	  }
	    	            }
	    	        });
	             }

	         });
		},
		SaveMovementRowInfo : function(){
			var $this = $(this);
			var at_id 					= $("input[name=at_id]").val();
			var tm_id 					= $this.parents('tr').find("input[name=tm_id]").val();
			var tm_ledger_account 		= $this.parents('tr').find("select[name=tm_ledger_account]").val();
			var tm_sub_ledger_account 	= $this.parents('tr').find("select[name=tm_sub_ledger_account]").val();
			var tm_ledger_label	 		= $this.parents('tr').find("input[name=tm_ledger_label]").val();
			var tm_debit	 			= $this.parents('tr').find("input[name=tm_debit]").val();
			var tm_credit	 			= $this.parents('tr').find("input[name=tm_credit]").val();
			var _token 					= $('input[name=_token]').val();
			var base_url 				= $('input[name=base_url]').val();
			var params					= {tm_id : tm_id , at_id : at_id , tm_ledger_account : tm_ledger_account , tm_sub_ledger_account : tm_sub_ledger_account , tm_ledger_label : tm_ledger_label , tm_debit : tm_debit , tm_credit : tm_credit , _token : _token};
			 $.ajax
 	        ({
 	            url : base_url + "/request/accounting/savemovementrowinfo",
 	            data : params,
 	            method : 'post',
 	            dataType : "json",
 	            success : function(response){
 	              if(response.is_error == 0)
 	              {
 	            	 transactions_module.DisplayListMovements();
 	            	 $("#ERROR_MSG").css({display : "none"});
 	            	 $("#SUCCESS_MSG").css({display : ""});
 	              }
 	              else
            	  {
            	  $("#SUCCESS_MSG").css({display : "none"});
            	  $("#ERROR_MSG").css({display : ""}).html(response.error_msg);
            	  transactions_module.DisplayListMovements();
            	  }
 	            }
 	        });
		},
        QuickActions : function(){
			let  base_url 			= $('input[name=base_url]').val();
			let _token 				= $('input[name=_token]').val();
            $.ajax({
                url: base_url + "/request/accounting/downloadtemplate?_token=" + _token,
                method: "GET",
                success: function(data) {
                    const blob = new Blob([data]);
                    // Create a Blob URL for the binary data
                    var blobUrl = window.URL.createObjectURL(blob);
                    // Create a temporary anchor element
                    var a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = 'accounts-template.csv'; // Set the desired file name
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
    }

