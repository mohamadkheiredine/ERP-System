/**
 * 
 */

$(function(){
	jobtitles_module.displayListJobTitles();
	$(".LstJobTitlesGrid").on('click',"a[id*=EDIT_JOB_TITLE_]",jobtitles_module.DisplayEditJobTitleForm);
	$(".LstJobTitlesGrid").on('click',"a[id*=DELETE_JOB_TITLE_]",jobtitles_module.DeleteJobTitleData);
})