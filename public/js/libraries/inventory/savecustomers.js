/**
 * 
 */
$(function(){
	$('#IC_AVATAR_PIC').on('change', function () {
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

                        $("#CUSTOMER_LOGO_PIC").attr('src', e.target.result);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            }
        }
    });
	 ClassicEditor
     .create( document.querySelector( '#IC_CUSTOMER_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );

	$('select').select2(); 
	$('select[name=aa_parent_account]').select2('destroy');
	$("#BTN_SAVE_CUSTOMER").on('click',customers_module.SaveCustomerInfo);
	$("#BTN_ADD_ACCOUNT").on('click',customers_module.AddNewAccount);
})