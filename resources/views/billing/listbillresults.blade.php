<?php
/***********************************************************
 * listbillresults.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/10/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>

@foreach($lst_billresult_workflow as $index => $bw_info)
    <tr  class="odd gradeX BillResultRow"  data-bw_id="{{ $bw_info->bw_id }}">
        <td>{{ $bw_info->bw_creation_date }}</td>
        <td>{{ $bw_info->bw_result_note }}</td>
        <td>{{ $bw_info->Result->cr_result_title }}</td>
        <td>{{ $bw_info->AssignedTo->u_fullname }}</td>
    </tr>
@endforeach
