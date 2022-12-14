/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SS_SUPPLIER_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('#SS_LOGO_PIC').on('change', function () {
	        var countFiles   = $(this)[0].files.length;
	        var imgPath      = $(this)[0].value;
	        var extn         = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	        var image_holder = $(".ListFiles");
	        image_holder.empty();

	        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
	            if (typeof (FileReader) != "undefined") {
	                //loop for each file selected for uploaded.
	                for (var i = 0; i < countFiles; i++)
	                {
	                    var reader = new FileReader();
	                    reader.onload = function (e) {
	                        var base_url = $('#BASE_URL').val();  

	                        $("#AVATAR_PIC").attr('src', e.target.result);
	                    }
	                    image_holder.show();
	                    reader.readAsDataURL($(this)[0].files[i]);
	                }

	            }
	        }
	    });
	$('select').select2();
	$("select[name=aa_parent_account]").select2('destroy');
	$("button[id*=BTN_SAVE_SUPPLIER]").on("click",suppliers_module.SaveSupplierInfo);
	 $("#BTN_ADD_ACCOUNT").on('click',suppliers_module.AddNewAccount);
	 $("#ADD_PURCHASE_ACCOUNT").on('click',suppliers_module.OpenAddNewAccount);
	 $("#ADD_SALES_ACCOUNT").on('click',suppliers_module.OpenAddNewAccount);
})