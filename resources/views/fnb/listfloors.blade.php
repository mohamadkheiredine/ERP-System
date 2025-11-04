@foreach($lst_floors as $index => $floor_info)
<tr class="odd gradeX" data-fl_id="{{ $floor_info->fl_id }}">
  <td><input type="checkbox" name="ck_floor_{{ $floor_info->fl_id }}" id="CK_FLOOR_{{ $floor_info->fl_id }}" class="checkboxes" value="{{ $floor_info->fl_id }}" /></td>
  <td>{{ $floor_info->fl_id }}</td>
  <td>{{ $floor_info->fl_floor_name }}</td>
  <td>
    <a href="#" data-fl_id="{{ $floor_info->fl_id }}" id="EDIT_FLOOR_{{ $floor_info->fl_id }}">
        <i class="fas fa-edit" height="16"></i>
    </a>
  </td>
  <td>
    <a href="#" data-fl_id="{{ $floor_info->fl_id }}" id="DELETE_FLOOR_{{ $floor_info->fl_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
