$(function(){
	ClassicEditor
    .create( document.querySelector( '#IT_TEMPLATE_DESCRIPTION' ) )
    .then( newEditor => {
       $.temp_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    ClassicEditor
    .create( document.querySelector( '#IT_INVOICE_NOTE' ) )
    .then( newEditor => {
       $.notes_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    $('#BTN_SAVE_TEMPLATE').on('click',templates_module.SaveInvoiceTemplateInfo); 
    $('#BTN_SAVE_ITEM').on('click',templates_module.AddInvoiceTemplateItem); 
    $('#LstInvoiceTemplates').on('click','a[id*=EDIT_ITEM_]',templates_module.DeleteInvoiceTemplateItem); 
});