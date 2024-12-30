<?php
/***********************************************************
listinbound.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>

@foreach($lst_result_workflow as $index => $cw_info)
<tr  class="odd gradeX" data-cw_id="{{ $cw_info->cw_id }}">
   <td>{{ $cw_info->cw_creation_date }}</td>
   <td>{{ $cw_info->cw_result_note }}</td>
   <td>{{ $cw_info->Result->cr_result_title }}</td>
   <td>{{ $cw_info->AssignedTo->u_fullname }}</td>
</tr>
@endforeach