<?php
/***********************************************************
listtemplateitems
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
Page to dislay list of items template 
***********************************************************/


?>


@foreach( $lst_template_items as $index => $item_info )
<tr  class="odd gradeX" data-ti_id="{{ $item_info->ti_id }}">
        <td><input type="checkbox" name="ck_ti_{{ $item_info->ti_id }}" id="CK_TI_{{ $item_info->ti_id }}" class="checkboxes" value="{{ $item_info->ti_id }}" /></td>
        <td>{{ $item_info->ti_id }}</td>
        <td>{{ $item_info->Service->cs_service_title }}</td>
        <td>{{ $item_info->ti_total_price }}&nbsp;<b>{{ $item_info->Currency->cc_currency_code }}</b></td>
        <td><a href="#" data-ti_id="{{ $item_info->ti_id }}" id="EDIT_ITEM_{{ $item_info->ti_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
</tr>
@endforeach