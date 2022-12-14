<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($lst_customers_obj as $index => $customer_info)
<tr  class="odd gradeX" data-ic_id="{{ $customer_info->ic_id }}">
	<td><input type="checkbox" name="ck_ic_{{ $customer_info->ic_id }}" id="CK_IC_{{ $customer_info->ic_id }}" class="checkboxes" value="{{ $customer_info->ic_id }}" /></td>
   <td>{{ $customer_info->ic_id }}</td>
   <td>{{ $customer_info->ic_customer_code }}</td> 
   <td>{{ $customer_info->Account->aa_account_ref }}</td> 
   <td>{{ $customer_info->ic_customer_name }}</td>
   <td>{{ $customer_info->ic_customer_phone }}</td>
    <td><a href="#" data-ic_id="{{ $customer_info->ic_id }}" id="EDIT_CUSTOMER_{{ $customer_info->ic_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td> 
    <td><a href="#" data-ic_id="{{ $customer_info->ic_id }}"   id="DELETE_CUSTOMER_{{ $customer_info->ic_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
</tr>
@endforeach