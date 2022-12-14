/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SO_OPERATION_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('input[name=so_operation_date]').datepicker({
         todayHighlight: true,
         orientation: "bottom left",
         templates: {
             leftArrow: '<i class="la la-angle-left"></i>',
             rightArrow: '<i class="la la-angle-right"></i>'
         }
     }); 
	 $('input[name=so_operation_time]').timepicker({
         minuteStep: 1,
         defaultTime: '',
         showSeconds: true,
         showMeridian: false,
         snapToStep: true
     });
	 $('select').select2();
	 operations_module.DisplayOperationTypeFields();
	 $("#BTN_SAVE_OPERATION").on("click",operations_module.SaveOperationInfo);
	 $("#SO_OPERATION_TYPE").on("change",operations_module.DisplayOperationTypeFields);
	 $("#OPERATION_INFO").on("change","#SO_WAREHOUSE_SOURCE",operations_module.DisplayListOperationProducts);
	 $("#OPERATION_INFO").on("click","#ADD_NEW_PRODUCT",operations_module.AddNewProductRow);
	 $("#OPERATION_INFO").on("click",".deleteRow",operations_module.DeleteProductRow);
	 $("#OPERATION_INFO").on("click","#SO_WAREHOUSE_SOURCE",operations_module.GetVehiculeDropdown);
	 
})