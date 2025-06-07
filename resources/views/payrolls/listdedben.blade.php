<?php


?>

@foreach($lst_dedben  as $index => $dedben_info)
    <tr  class="odd gradeX" data-db_id="{{ $dedben_info->db_id }}">
        <td><input type="checkbox" name="ck_db_{{ $dedben_info->db_id }}" id="CK_DB_{{ $dedben_info->db_id }}" class="checkboxes" value="{{ $dedben_info->db_id }}" /></td>
        <td>{{ $dedben_info->db_id }}</td>
        <td>{{ $dedben_info->Company->cd_company_name }}</td>
        <td>{{ $dedben_info->db_type }}</td>
        <td>{{ $dedben_info->db_ben_ded_label }}</td>
        <td>{{ $dedben_info->db_amount }}&nbsp;<b>{{ $dedben_info->Currency->cc_currency_code }}</b></td>
        <td>{{ $dedben_info->db_end_date }}</td>
        <td><a href="#" data-db_id="{{ $dedben_info->db_id }}" id="EDIT_DEDBEN_{{ $dedben_info->db_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-db_id="{{ $dedben_info->db_id }}"  id="DELETE_DEDBEN_{{ $dedben_info->db_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
