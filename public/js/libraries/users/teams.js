/**
 * 
 */
$(function(){
	teams_module.DisplayListUserTeams();
	$('.LstTeamsGrid').on("click","a[id*=EDIT_TEAM_]",teams_module.EditTeamForm);
	$('.LstTeamsGrid').on("click","a[id*=DELETE_TEAM_]",teams_module.DeleteUserTeamInfo);
})