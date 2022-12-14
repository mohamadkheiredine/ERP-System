<?php
/***********************************************************
listledger.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display List of ledger ( transaction and movememnt) Saved in the database
***********************************************************/
?>
<table class="m-datatable" id="html_table" width="100%">
	<thead>
		<tr>
			<th title="#">#</th>
			<th title="id">ID</th>
			<th title="Date">Date</th>
			<th title="Accounting Doc">Accounting Doc</th>
			<th title="Journal">Journal</th>
			<th title="edit">Edit</th>
			<th title="Delete">Delete</th>
		</tr>
	</thead>
	<tbody>

	     @foreach ($transaction_obj as $index => $trans_info )
	     	  <tr  class="odd gradeX" data-at_id="{{ $trans_info->at_id }}">
                <td><input type="checkbox" name="ck_at_{{ $trans_info->at_id }}" id="CK_AT_{{ $trans_info->at_id }}" class="checkboxes" value="{{ $trans_info->at_id }}" /></td>
				<td>{{ $trans_info->at_id }}</td>
				<td>{{ $trans_info->at_transaction_date }}</td>
				<td>{{ $trans_info->at_accounting_doc }}</td>
				<td>{{ $journals_array[ $trans_info->fk_acc_journal_id ]['aj_journal_code'] . " - " . $journals_array[ $trans_info->fk_acc_journal_id ]['aj_journal_label'] }}</td>
				<td style="width:2px;"><a  data-at_id="{{ $trans_info->at_id }}"  href="#"  id="EDIT_TRANS_{{ $trans_info->at_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
                <td style="width:2px;"><a  data-at_id="{{ $trans_info->at_id }}"  href="#"  id="DELETE_TRANS_{{ $trans_info->at_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
			</tr>
	     @endforeach

		</tbody>
</table>