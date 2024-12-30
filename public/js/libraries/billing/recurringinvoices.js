$(function(){
	recinvoices_module.DisplayListRecurringInvoices();
	$("#generalSearch").on('keyup',recinvoices_module.DisplayListRecurringInvoices);
	$("select").on('change',recinvoices_module.DisplayListRecurringInvoices);

	$('#LstRecurringInvoices').on('click',"a[id*=EDIT_RECINVOICE_]",recinvoices_module.EditRecurringInvoiceForm);
	$('#LstRecurringInvoices').on('click',"a[id*=DELETE_RECINVOICE_]",recinvoices_module.DeleteRecurringInvoiceData);
})