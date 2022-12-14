<?php 

/***********************************************************
listnotes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List of notes for selected lead
***********************************************************/

?>

<ul class="Notes">
@foreach($notes_data as $index => $note_info)
	 
	<li>
		<div class="row" style="margin-bottom: 4px;">
			<div class="col-2" style="padding-left:0px;padding-right:0px;" align="right">
				<img src="{{ $note_info['profile_pic'] }}" class="CommentsImg" />
			</div>
			<div class="col-10">
				<p class="NoteText">{{ $note_info['note'] }}</p><br/>
				<span  class="NoteInfo" ><i class="far fa-clock"></i>&nbsp;{{ GetTimeDifference($note_info['note_date']) }} by <small>&nbsp;{{ $note_info['writer_name'] }}</small></span>
			</div>
		</div>
		<div class="row"><div class="col-12"  style="height: 10px;">&nbsp;</div></div>
	</li> 
@endforeach
</ul>