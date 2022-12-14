/**
 * 
 */
$(function(){
	$('select').select2();
	$("#BTN_SAVE_ACCOUNT").on("click",chartaccounts_module.SaveAccountInfo);
	$("#BTN_SAVE_NEW_ACCOUNT").on("click",chartaccounts_module.SaveAndNewAccountInfo);
	$("#AA_SUB_ACCOUNT").on("change",chartaccounts_module.GenerateNewAccount);
	$("#AccountsTree").jstree();
	$("#AccountsTree").on("changed.jstree", function (e, data) {
		var account_id = $("#" + data.selected).data('account_id');
      var id = $("#" + data.selected).data('id');
      var _token = $("input[name=_token]").val();
      var base_url = $("input[name=base_url]").val();
      var params = { account_id : account_id , _token : _token };
      $.ajax
	    ({
	        url : base_url + "/request/accounting/generatelatestaccount",
	        data :params,
          method : 'post',
          dataType : "json", 
	      success : function(response){
	        	 var account_ref = response.account_ref;
	        	 $("#AA_CATEGORY_REF").val(account_ref);
	        	 $("#AA_ACCOUNT").val(account_ref);
	        	 $("#AA_SUB_ACCOUNT").val(id);
	        	 $("#AA_SUB_ACCOUNT").trigger('change');
	    }});
    });
})