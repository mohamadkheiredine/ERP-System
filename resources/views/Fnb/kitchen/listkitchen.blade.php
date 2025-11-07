@foreach($lst_kitchens as $index => $kitchen_info)
<tr class="odd gradeX" data-ks="{{ $kitchen_info->ks_id }}">
  <td><input type="checkbox" name="ck_kitchen_{{ $kitchen_info->ks_id }}" id="CK_KITCHEN_{{ $kitchen_info->ks_id }}" class="checkboxes" value="{{ $kitchen_info->ks_id }}" /></td>
  <td>{{ $kitchen_info->ks_id }}</td>
  <td>{{ $kitchen_info->ks_name }}</td>
  <td>{{ $kitchen_info->ks_description }}</td>
  <td>
    <a href="#" data-ks_id="{{ $kitchen_info->ks_id }}" id="EDIT_KITCHEN_{{ $kitchen_info->ks_id }}">
        <i class="fas fa-edit" height="16"></i>
    </a>
  </td>
  <td>
    <a href="#" data-ks_id="{{ $kitchen_info->ks_id }}" id="DELETE_KITCHEN_{{ $kitchen_info->ks_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
