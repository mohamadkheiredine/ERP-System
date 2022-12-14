/**
 * 
 */

itemscategory_module = {
	DisplayListItems : function(){
		var base_url 		= $('input[name=base_url]').val();
	    var _token 			= $('input[name=_token]').val()
	    var page_number 	= $('input[name=page_number]').val();
	    var pc_id 			= $('input[name=pc_id]').val();
	    var search_query 	= $('input[name=search_query]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/productcategories/displaylistitems",
	        data : { _token : _token , page_number : page_number , search_query : search_query , pc_id : pc_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	            $('#LstProducts').html(response.display);
                         $('.group-checkable').change(function() {
                            var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                            var checked = $(this).prop("checked");
                            $(set).each(function() {
                                $(this).prop("checked", checked);
                            });
                            $.uniform.update(set);
                        }); 
                       $.pagination = $('#ProductsPagination').twbsPagination({
                             totalPages: response.total_pages,
                             visiblePages: 7,
                             onPageClick: function (event, page) {
                                  $('input[name=page_number]').val(page);
                                  itemscategory_module.DisplayListItems();
                             }
                         });
	        }
	    });
	},
	AddNewCategory : function(){
		let base_url = $("input[name=base_url]").val();
		window.location.href = base_url + "/inventory/addnewproduct";
		return false;
	},
	EditProductPage : function(){
		var p_id = $(this).parent('tr').data('p_id');
		
		let base_url = $("input[name=base_url]").val();
		window.location.href = base_url + "/inventory/editproduct/" + p_id;
		
	}
};