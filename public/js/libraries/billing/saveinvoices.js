/**
 *
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#BI_INVOICE_NOTE' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );

	 new tempusDominus.TempusDominus(document.getElementById('BI_INVOICE_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true,
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "L"

		 }
	});
     if($('#IP_BILLING_DATE').length > 0 )
         new tempusDominus.TempusDominus(document.getElementById('IP_BILLING_DATE'),{
             display: {
                  components: {
                      calendar: true,
                      date: true,
                      month: true,
                      year: true,
                      decades: true,
                      clock: false,
                      hours: false,
                      minutes: false,
                      seconds: false,
                      useTwentyfourHour: undefined
                    }
             },
             localization: {
                 format : "L"

             }
        });



	$('#BI_INVOICE_DATE').on('changeDate', function() {
	   var current_date = $('#BI_INVOICE_DATE').val();

	   invoices_module.GenerateInvoiceCode(current_date);
	});
	$("#BTN_SAVE_INVOICE").on("click",invoices_module.SaveInvoiceInfo);
	$("#BTN_SAVE_NEW").on("click",invoices_module.SavenNewInvoiceInfo);


	var bi_id = $("input[name=bi_id]").val();
	if(bi_id != null)
	{
		invoices_module.DisplayListInvoiceProducts();
		invoices_module.DisplayListInvoicePayments();
		receipts_module.DisplayListInvoiceReceipts();
                 invoices_module.GetAccountInformation();
		$("#BTN_ADD_PRODUCT").on("click",invoices_module.OpenInsertItemsPopup);
		$("#BI_SERVICE_ID").on("change",invoices_module.ShowPaymentType);
		$("#BTN_ADD_SERVICE").on("click",invoices_module.OpenInsertServicesPopup);
		$("#BTN_INSERT_ITEM").on("click",invoices_module.SaveItemsInfo);
		$("#BTN_INSERT_SERVICE").on("click",invoices_module.SaveServiceInfo);
		$("#BTN_SAVE_PAYMENT").on("click",invoices_module.SavePaymentInvoiceInfo);
		$("#BTN_CREATE_ROWS").on("click",invoices_module.CreateRemoveNumberofRows);
		$("#BTN_SAVE_ROWS").on("click",invoices_module.SaveNewRowsInfo);
		$("#BTN_GENERATE_RECEIPTS").on("click",invoices_module.GenerateReceiptsPayments);
		$(".quickactions").on("click",invoices_module.QuickActions);
		$(window).on("click",".DeleteRow",invoices_module.RemoveCurrentRow);
		$('#LstPaymentSplits').on("click",".EditPayment",invoices_module.EditPaymentInfo);
		$("#LstReceipts").on('click',"a[id*=PAY_]",invoices_module.PayReceipt);
		$("#LstReceipts").on('click',"a[id*=EDIT_IRECEIPT_]",invoices_module.EditIReceiptForm);
		$("#LstProducts").on('click',"a[id*=DELETE_ITEM_]",invoices_module.DeleteItemFromInvoice);
		$("#LstProducts").on('click',"a[id*=EDIT_ITEM_]",invoices_module.GetItemInvoiceInfo);
                $("#BTN_CLOSE").on('click',function(){
                    $("#InserItems").modal('toggle');
                });
		$("#BI_PRODUCT").on("change",invoices_module.DisplayProductInfo);
	}
	$("#BI_ACCOUNT_NUMBER").on("change",invoices_module.GetAccountInformation);
	$("#BI_ACCOUNT_NUMBER").on("blur",invoices_module.GetAccountInformation);
    $("#BI_PRODUCT_ID").on("change",invoices_module.SwitchOtherDropdownForProduct);
    $("#BI_PRODUCT_CODE_ID").on("change",invoices_module.SwitchPOtherDropdownForProduct);
    $("#BTN_LINK_ITEM").on("click",invoices_module.SaveLinkItem);
    $("#BI_INTERNAL_INVOICE").on("change",invoices_module.DisplayInternalCompaniesLst);

    //   $("#INVOICE_ACCOUNT_ID").select2('destroy').attr("disabled", true);


})
