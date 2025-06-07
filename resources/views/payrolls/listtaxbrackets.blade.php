<?php
/***********************************************************
 * listtaxbrackets.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 3/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@foreach($lst_taxes  as $index => $tax_info)
    <tr  class="odd gradeX" data-tb_id="{{ $tax_info->tb_bracket_id }}">
        <td><input type="checkbox" name="ck_tb_{{ $tax_info->tb_bracket_id }}" id="CK_TB_{{ $tax_info->tb_bracket_id }}" class="checkboxes" value="{{ $tax_info->tb_bracket_id }}" /></td>
        <td>{{ $tax_info->tb_bracket_id }}</td>
        <td>{{ $tax_info->Company->cd_company_name }}</td>
        <td>{{ $tax_info->tb_bracket_label }}</td>
        <td>{{ $tax_info->tb_min_salary }}</td>
        <td>{{ $tax_info->tb_max_salary }}</td>
        <td>{{ $tax_info->tb_tax_rate }}</td>
        <td><a href="#" data-tb_id="{{ $tax_info->tb_bracket_id }}" id="EDIT_BRACKET_{{ $tax_info->tb_bracket_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-tb_id="{{ $tax_info->tb_bracket_id }}"  id="DELETE_BRACKET_{{ $tax_info->tb_bracket_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
