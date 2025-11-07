@foreach($lst_tables as $index => $table_info)
<tr class="odd gradeX" data-ft="{{ $table_info->ft_id }}">
  <td><input type="checkbox" name="ck_table_{{ $table_info->ft_id }}" id="CK_TABLE_{{ $table_info->ft_id }}" class="checkboxes" value="{{ $table_info->ft_id }}" /></td>
  <td>{{ $table_info->ft_id }}</td>
  <td>{{ $table_info->ft_label }}</td>
  <td>{{ $table_info->fl_floor_name }}</td>
  <td>{{ $table_info->ft_capacity }}</td>
  <td>
    <a href="#" data-ft_id="{{ $table_info->ft_id }}" id="EDIT_TABLE_{{ $table_info->ft_id }}">
        <i class="fas fa-edit" height="16"></i>
    </a>
  </td>
  <td>
    <a href="#" data-ft_id="{{ $table_info->ft_id }}" id="DELETE_TABLE_{{ $table_info->ft_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
