$(function(){
	templates_module.DisplayListInvoiceTemplates();
	$("#generalSearch").on('keyup',templates_module.DisplayListInvoiceTemplates);
	$("select").on('change',templates_module.DisplayListInvoiceTemplates);

	$('#LstInvoiceTemplates').on('click',"a[id*=EDIT_TEMPLATE_]",templates_module.DisplayEditTemplateForm);
	$('#LstInvoiceTemplates').on('click',"a[id*=DELETE_TEMPLATE_]",templates_module.DeleteInvoiceTemplateData);
})