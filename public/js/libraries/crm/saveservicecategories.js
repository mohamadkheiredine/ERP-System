/**
 * 
 */

$.editor
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SC_CATEGORY_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('#SC_AVATAR_PIC').on('change', function () {
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
	 $("#BTN_SAVE_CATEGORY").on('click',servicecategories_module.SaveServiceCategoryInfo);
})