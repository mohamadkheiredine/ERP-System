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
	 $('select').select2();
	$("#BI_INVOICE_DATE").datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
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
		$("#BTN_ADD_PRODUCT").on("click",invoices_module.OpenInsertItemsPopup);
		$("#BI_SERVICE_ID").on("change",invoices_module.ShowPaymentType);
		$("#BTN_ADD_SERVICE").on("click",invoices_module.OpenInsertServicesPopup);
		$("#BTN_INSERT_ITEM").on("click",invoices_module.SaveItemsInfo);
		$("#BTN_INSERT_SERVICE").on("click",invoices_module.SaveServiceInfo);
		$("#BTN_CREATE_ROWS").on("click",invoices_module.CreateRemoveNumberofRows);
		$("#BTN_SAVE_ROWS").on("click",invoices_module.SaveNewRowsInfo);
		$("#BTN_GENERATE_RECEIPTS").on("click",invoices_module.GenerateReceiptsPayments);
		$(".quickactions").on("click",invoices_module.QuickActions);
		$(window).on("click",".DeleteRow",invoices_module.RemoveCurrentRow);
		$("#LstReceipts").on('click',"a[id*=PAY_]",invoices_module.PayReceipt); 
		$("#LstReceipts").on('click',"a[id*=EDIT_IRECEIPT_]",invoices_module.EditIReceiptForm); 
		$("#LstProducts").on('click',"a[id*=DELETE_ITEM_]",invoices_module.DeleteItemFromInvoice); 
		$("#LstProducts").on('click',"a[id*=EDIT_ITEM_]",invoices_module.GetItemInvoiceInfo); 
	}
	
})