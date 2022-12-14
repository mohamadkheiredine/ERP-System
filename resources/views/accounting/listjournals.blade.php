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

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id" style="white-space: nowrap;">ID</th>
				<th title="code" style="white-space: nowrap;">Journal Code</th>
				<th title="label" style="white-space: nowrap;">Label</th>
				<th title="type" style="white-space: nowrap;">Type</th>
				<th title="Active" style="white-space: nowrap;">Active</th>
			</tr>
		</thead>
		<tbody>

		     @foreach ($lst_journals as $key => $journal_info )
                <tr>
    				<td>{{ $journal_info->aj_id }}</td>
    				<td>{{ $journal_info->aj_journal_code }}</td>
    				<td>{{ $journal_info->aj_journal_label }}</td>
    				<td>{{ $journal_types_array[ $journal_info->aj_type_journal ]['ty_journal_type'] }}</td>
    				<td>
    					<span class="m-switch m-switch--icon m-switch--info">
							<label>
								<input type="checkbox" {{ $journal_info->aj_is_active == 1 ? "checked" : "" }} name="aj_journal_active_{{ $journal_info->aj_id }}" value="{{ $journal_info->aj_id }}" />
								<span class="ActualSwitch"></span>
							</label>
						</span>
    				</td>
    			</tr>
		     @endforeach

			</tbody>
</table>