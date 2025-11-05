category_module = {
    DisplayListCategories: function () {
        var base_url = $("input[name=base_url]").val();
        var _token = $("input[name=_token]").val();

        var page_number = $("input[name=page_number]").val();
        var general_search = $("input[name=general_search]").val();
        $.ajax({
            url: base_url + "/request/category/displaylistcategories",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
            },
            method: "get",
            dataType: "json",
            beforeSend: function () {},
            success: function (response) {
                $("#LstCategoriesGrid").html(response.display);
                $(".group-checkable").change(function () {
                    var set = $("category").find(
                        'tbody > tr > td:nth-child(1) input[type="checkbox"]'
                    );
                    var checked = $(this).prop("checked");
                    $(set).each(function () {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if (response.total_pages > 0) {
                    $.pagination = $("#CategoriesPagination").twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $("input[name=page_number]").val(page);
                            category_module.DisplayListCategories();
                        },
                    });
                }
            },
        });
    },
    SaveCategoryInfo: function () {
        return category_module.SaveCategoryInfoSubmitHandler();
    },
    SaveCategoryInfoSubmitHandler: function () {

		 var CategoryForm = $('#FORM_SAVE_CATEGORY');
         var error3 = $('.alert-danger', CategoryForm);
         var success3 = $('.alert-success', CategoryForm);

         CategoryForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 mc_category_name : {
                     required: true
                   },
                   mc_category_description : {
                     required: true,
                     minlength: 5
                   }
             },

             messages: { // custom messages for radio buttons and checkboxes

             },
             errorPlacement: function (error, element) { // render error placement for each input type
                 if (element.parent(".input-group").length > 0) {
                     error.insertAfter(element.parent(".input-group"));
                 } else if (element.attr("data-error-container")) {
                     error.appendTo(element.attr("data-error-container"));
                 } else if (element.parents('.radio-list').length > 0) {
                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                 } else if (element.parents('.radio-inline').length > 0) {
                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                 } else if (element.parents('.checkbox-list').length > 0) {
                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                 } else if (element.parents('.checkbox-inline').length > 0) {
                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                 } else {
                     error.insertAfter(element); // for other inputs, just perform default behavior
                 }
             },
             invalidHandler: function (event, validator) { //display error alert on form submit
                 success3.hide();
                 error3.show();
             },
             success: function (label) {
                 label
                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
             },
             highlight: function (element) { // hightlight error inputs
                 $(element)
                     .closest('.form-group').addClass('has-error'); // set error class to the control group
             },

             unhighlight: function (element) { // revert the change done by hightlight
                 $(element)
                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
             },
             submitHandler: function (form) {
                success3.show();
                error3.hide();
                var base_url = $('#BASE_URL').val();
    	       // var _token = $('input[name=_token]').val();

    	        var FormDataFields = $("form[id=FORM_SAVE_CATEGORY]");

    	        var data = new FormData();
    	        var index = 0;

    	        $.each($("input[type=file]"), function(i, obj) {
    	                var name = $(this).attr('name');
    	                $.each(obj.files,function(j,file){
    	                        data.append(name, file);
    	                })
    	        });

    	        FormDataFields.find('input,select').each(function(){

    	        		if($(this).attr('type') == 'checkbox')
    	        		{
    	        			let name = $(this).attr('name');
    	        			let value = $("input[name=" + name + "]:checked").length == 1 ? 1 : 0;
    	        			data.append($(this).attr('name'),value);
    	        		}
    	        		else
	        			{
    	        			data.append($(this).attr('name'), $(this).val() );
	        			}

    	        });
    	        const pc_description = $.editor.getData();

    	        data.append("pc_description", pc_description );
    	        var str_params = $("#FORM_SAVE_CATEGORY").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/category/saveinfo",
    	            data : data,
    	            async: false,
    	            cache: false,
    	            method : 'post',
    	            contentType: false,
    	            processData: false,
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/fnb/category";
    	              }
    	            }
    	        });
             }

         });
	},
        // var CategoryForm = $("#FORM_SAVE_KITCHEN");
        // var error3 = $(".alert-danger", CategoryForm);
        // var success3 = $(".alert-success", CategoryForm);

        // CategoryForm.validate({
        //     errorElement: "span",
        //     errorClass: "help-block help-block-error",
        //     focusInvalid: false,
        //     ignore: "",

        //     rules: {
        //         ks_name: { required: true, maxlength: 255 }, // Category Name
        //         ks_description: { required: true },
        //     },

        //     messages: {
        //         ks_name: {
        //             required: "Please enter the category name",
        //             maxlength: "Category name cannot exceed 255 characters",
        //         },
        //         ks_description: {
        //             required: "Please enter a description for the category station",
        //         }
        //     },

        //     errorPlacement: function (error, element) {
        //         error.insertAfter(element);
        //     },

        //     invalidHandler: function () {
        //         success3.hide();
        //         error3.show();
        //     },
        //     highlight: function (element) {
        //         $(element).closest(".form-group").addClass("has-error");
        //     },
        //     unhighlight: function (element) {
        //         $(element).closest(".form-group").removeClass("has-error");
        //     },
        //     success: function (label) {
        //         label.closest(".form-group").removeClass("has-error");
        //     },

        //     submitHandler: function () {
        //         success3.show();
        //         error3.hide();
        //         var base_url = $("#BASE_URL").val();
        //         var str_params = $("#FORM_SAVE_KITCHEN").serialize();

        //         $.ajax({
        //             url: base_url + "/request/category/saveinfo",
        //             data: str_params,
        //             method: "POST",
        //             dataType: "json",
        //             success: function (response) {
        //                 if (response.is_error == 0) {
        //                     window.location.href = base_url + "/fnb/category";
        //                 }
        //             },
        //         });
        //     },
        // });
    },

    DeleteCategoryData: function () {
        var ks_id = $(this).data("ks_id");
        bootbox.confirm("Are you sure you want to delete ?", function (result) {
            //result
            if (result == true) {
                var base_url = $("#BASE_URL").val();
                var _token = $("input[name=_token]").val();
                var str_params = { ks_id: ks_id, _token: _token };
                $.ajax({
                    url: base_url + "/request/category/deletecategoryinfo",
                    data: str_params,
                    dataType: "Json",
                    type: "delete",
                    success: function (response) {
                        if (response.is_error == 0) {
                            category_module.DisplayListCategories();
                        }
                    },
                });
            }
        });
    },
    EditCategoryInfo: function () {
        var ks_id = $(this).data("ks_id");
        console.log("category id is ", ks_id);
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/category/editform/" + ks_id;
    },
    backToPreviousPage: function () {
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/fnb/category";
    },
};



// /**
//  *
//  */
// prodcategory_module = {
// 		displayListProductCategories : function(){
// 			var base_url 	= $('input[name=base_url]').val();
// 		    var _token 		= $('input[name=_token]').val();
// 		    var page_number = $('input[name=page_number]').val();
// 		    var search_query = $('input[name=general_search]').val();
// 		    $.ajax
// 		    ({
// 		        url : base_url + "/request/products/displaylistcategory",
// 		        data : { _token : _token , page_number : page_number , search_query : search_query },
// 	            method : 'post',
// 	            dataType : "json",
// 	            beforeSend : function(){
// 	            },
// 		        success : function(response){
// 		            $('.LstCategoriesGrid').html(response.display);
//                              $('.group-checkable').change(function() {
//                                 var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
//                                 var checked = $(this).prop("checked");
//                                 $(set).each(function() {
//                                     $(this).prop("checked", checked);
//                                 });
//                                 $.uniform.update(set);
//                             });
//                            $.pagination = $('#ProductCategoriesPagination').twbsPagination({
//                                  totalPages: response.total_pages,
//                                  visiblePages: 7,
//                                  onPageClick: function (event, page) {
//                                       $('input[name=page_number]').val(page);
//                                       prodcategory_module.displayListProductCategories();
//                                  }
//                              });
// 		        }
// 		    });
// 		},
// 		AddNewProductCategoryForm : function(){
// 			var base_url = $("#BASE_URL").val();
// 			window.location.href = base_url + "/inventory/product/addcategory";
// 		},
// 	SaveProductCategoryInfo : function(){
// 		return prodcategory_module.SaveProductCategorySubmitHandler();
// 	},
// 	SaveProductCategorySubmitHandler : function(){
// 		 var CategoryForm = $('#FORM_SAVE_CATEGORY');
//          var error3 = $('.alert-danger', CategoryForm);
//          var success3 = $('.alert-success', CategoryForm);

//          CategoryForm.validate({
//              errorElement: 'span', //default input error message container
//              errorClass: 'help-block help-block-error', // default input error message class
//              focusInvalid: false, // do not focus the last invalid input
//              ignore: "", // validate all fields including form hidden input
//              rules: {
//             	 pc_category_title : {
//                      required: true
//                    },
//                    pc_category_description : {
//                      required: true,
//                      minlength: 5
//                    }
//              },

//              messages: { // custom messages for radio buttons and checkboxes

//              },
//              errorPlacement: function (error, element) { // render error placement for each input type
//                  if (element.parent(".input-group").length > 0) {
//                      error.insertAfter(element.parent(".input-group"));
//                  } else if (element.attr("data-error-container")) {
//                      error.appendTo(element.attr("data-error-container"));
//                  } else if (element.parents('.radio-list').length > 0) {
//                      error.appendTo(element.parents('.radio-list').attr("data-error-container"));
//                  } else if (element.parents('.radio-inline').length > 0) {
//                      error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
//                  } else if (element.parents('.checkbox-list').length > 0) {
//                      error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
//                  } else if (element.parents('.checkbox-inline').length > 0) {
//                      error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
//                  } else {
//                      error.insertAfter(element); // for other inputs, just perform default behavior
//                  }
//              },
//              invalidHandler: function (event, validator) { //display error alert on form submit
//                  success3.hide();
//                  error3.show();
//              },
//              success: function (label) {
//                  label
//                      .closest('.form-group').removeClass('has-error'); // set success class to the control group
//              },
//              highlight: function (element) { // hightlight error inputs
//                  $(element)
//                      .closest('.form-group').addClass('has-error'); // set error class to the control group
//              },

//              unhighlight: function (element) { // revert the change done by hightlight
//                  $(element)
//                      .closest('.form-group').removeClass('has-error'); // set error class to the control group
//              },
//              submitHandler: function (form) {
//                 success3.show();
//                 error3.hide();
//                 var base_url = $('#BASE_URL').val();
//     	       // var _token = $('input[name=_token]').val();

//     	        var FormDataFields = $("form[id=FORM_SAVE_CATEGORY]");

//     	        var data = new FormData();
//     	        var index = 0;

//     	        $.each($("input[type=file]"), function(i, obj) {
//     	                var name = $(this).attr('name');
//     	                $.each(obj.files,function(j,file){
//     	                        data.append(name, file);
//     	                })
//     	        });

//     	        FormDataFields.find('input,select').each(function(){

//     	        		if($(this).attr('type') == 'checkbox')
//     	        		{
//     	        			let name = $(this).attr('name');
//     	        			let value = $("input[name=" + name + "]:checked").length == 1 ? 1 : 0;
//     	        			data.append($(this).attr('name'),value);
//     	        		}
//     	        		else
// 	        			{
//     	        			data.append($(this).attr('name'), $(this).val() );
// 	        			}

//     	        });
//     	        const pc_description = $.editor.getData();

//     	        data.append("pc_description", pc_description );
//     	        var str_params = $("#FORM_SAVE_CATEGORY").serialize();
//     	         $.ajax
//     	        ({
//     	            url : base_url + "/request/products/savecategoryinfo",
//     	            data : data,
//     	            async: false,
//     	            cache: false,
//     	            method : 'post',
//     	            contentType: false,
//     	            processData: false,
//     	            dataType : "json",
//     	            beforeSend : function(){
//     	            },
//     	            success : function(response){
//     	              if(response.is_error == 0)
//     	              {
//     	                 window.location.href = base_url + "/inventory/productcategories";
//     	              }
//     	            }
//     	        });
//              }

//          });
// 	},
// 	DeleteProductCategoryData : function(){
// 		 var pc_id = $(this).parents('tr').data('pc_id');
// 		bootbox.confirm("Are you sure you want to delete ?", function(result){
// 			//result
// 			if(result == true)
// 			{
// 			      var base_url = $('#BASE_URL').val();
// 			      var _token = $('input[name=_token]').val();
// 			        var str_params ={pc_id : pc_id , _token : _token};
// 			         $.ajax
// 			        ({
// 			            url : base_url + "/request/products/deletecategoryinfo",
// 			            data : str_params,
// 			            dataType : "Json",
// 			            type : "POST",
// 			            success : function(response){
// 			              if(response.is_error == 0)
// 			              {
// 			            	  prodcategory_module.displayListProductCategories();
// 			              }
// 			            }
// 			        });
// 			}
// 		});
// 	},
// 	DisplayEditProductCategoryForm : function(){
// 		var pc_id = $(this).parents('tr').data('pc_id');
// 	    var base_url = $("#BASE_URL").val();
// 	    window.location.href = base_url + "/inventory/product/editcategory/" + pc_id;
// 	},
// 	CancelForm : function(){
// 		 window.history.back();
// 	}
// };
