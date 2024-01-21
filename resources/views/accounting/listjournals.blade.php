<?php
/***********************************************************
listjournals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

 @foreach ($lst_journals as $key => $journal_info )
    <tr>
		<td>{{ $journal_info->aj_id }}</td>
		<td>{{ $journal_info->aj_journal_code }}</td>
		<td>{{ $journal_info->aj_journal_label }}</td>
		<td>{{ $journal_types_array[ $journal_info->aj_type_journal ]['ty_journal_type'] }}</td>
		<td>
			<div class="form-check">
                <input type="checkbox" class="form-check-input" {{ $journal_info->aj_is_active == 1 ? "checked" : "" }} name="aj_journal_active_{{ $journal_info->aj_id }}" value="{{ $journal_info->aj_id }}" />
            </div>
		</td>
	</tr>
 @endforeach