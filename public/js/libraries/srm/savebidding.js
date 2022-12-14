/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SB_BID_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 
	 $('select').select2();
	 $('#SB_START_DATE').datepicker({
		 format : "yyyy-mm-dd",
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#SB_END_DATE').datepicker({
		 format : "yyyy-mm-dd",
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#SB_RFQ_ISSUE_DATE').datepicker({
		 format : "yyyy-mm-dd",
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#SB_DUE_RFQ_DATE').datepicker({
		 format : "yyyy-mm-dd",
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $("#BTN_SAVE_BIDDING").on("click",bidding_module.SaveSupplierBiddingInfo);
	 var sb_id = $("input[name=sb_id]").val();
	 if(sb_id != null)
	 {
		 bidding_module.DisplayListBiddingProducts();
		 $("#BTN_ADD_PRODUCT").on("click",bidding_module.OpenAddProductBidding);
		 $("#BTN_INSERT_ITEMS").on("click",bidding_module.SaveInsertItemsInfo);
		 $("#BiddingProducts").on("click","a[id*=DELETE_ITEM_]",bidding_module.DeleteBiddingItems);
	 }
})