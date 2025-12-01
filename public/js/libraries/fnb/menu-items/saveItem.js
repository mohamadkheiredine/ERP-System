$(function(){
     ClassicEditor
     .create(document.querySelector('#MI_ITEM_DESCRIPTION'))
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch(error => {
         console.error(error);
     } );

    $("#BTN_SAVE_ITEM").on("click",fnb_items_module.SaveItemInfo);

    $("[name=back_form]").on("click",fnb_items_module.backToPreviousPage);

    fnb_items_module.DisplayListItemsModifiers();

    $("#BTN_SAVE_MODIFIER").on("click", fnb_items_module.SaveItemModifierInfo);


    $("#FK_MODIFIER_ID").on("change", fnb_items_module.getValues);

    $("#LstItemsModifiers").on(
        "click",
        "a[id*=DELETE_ITEM_MODIFIER_]",
        fnb_items_module.DeleteItemModifier
    );

    	 $('#MI_AVATAR_PIC').on('change', function () {
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

})
