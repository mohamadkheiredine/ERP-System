@foreach($lst_categories as $index => $category_info)
<tr class="odd gradeX" data-mc="{{ $category_info->mc_id }}">
  <td><input type="checkbox" name="ck_table_{{ $category_info->mc_id }}" id="CK_CATEGORY_{{ $category_info->mc_id }}" class="checkboxes" value="{{ $category_info->mc_id }}" /></td>
  <td>{{ $category_info->mc_id }}</td>

  <td>{{ $category_info->mc_category_name }}</td>

  <td>
    <a href="#" data-mc_id="{{ $category_info->mc_id }}" id="EDIT_CATEGORY_{{ $category_info->mc_id }}">
      <i class="fas fa-edit" height="16"></i>
    </a>
  </td>

  <td>
    <a href="#" data-mc_id="{{ $category_info->mc_id }}" id="DELETE_CATEGORY_{{ $category_info->mc_id }}">
      <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
