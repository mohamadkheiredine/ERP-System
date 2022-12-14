<?php
/***********************************************************
lstemployeespayroll.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th>#</th>
				<th title="id">ID</th>
				<th title="fullname">Full Name</th>
				<th title="email">Email</th>
				<th title="phone">Phone</th>
				<th title="mobile">Mobile</th>
				<th title="sallary">Sallary</th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_payroll  as $index => $pay_info)
                <tr  class="odd gradeX" data-up_id="{{ $pay_info->up_id }}">
                	<td><input type="checkbox" name="ck_up_{{ $pay_info->up_id }}" id="CK_UP_{{ $pay_info->up_id }}" class="checkboxes" value="{{ $pay_info->up_id }}" /></td>
                   <td>{{ $pay_info->up_id }}</td>
                   <td>{{ $pay_info->users->u_fullname }}</td> 
                   <td>{{ $pay_info->users->u_email }}</td>
    				<td>{{ $pay_info->users->u_phone }}</td>
    				<td>{{ $pay_info->users->u_mobile }}</td>
                   <td>{{ $pay_info->up_sallary_amount . " " . $pay_info->currencies->cc_currency_code }}</td> 
                </tr>
                @endforeach
		</tbody>
</table>