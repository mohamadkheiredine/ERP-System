@foreach($lst_modifiers as $index => $modifier_info)
<tr class="odd gradeX" data-m="{{ $modifier_info->m_id }}">
  <td><input type="checkbox" name="ck_modifier_{{ $modifier_info->m_id }}" id="CK_MODIFIER_{{ $modifier_info->m_id }}" class="checkboxes" value="{{ $modifier_info->m_id }}" /></td>
  <td>{{ $modifier_info->m_id }}</td>
  <td>{{ $modifier_info->m_modifier_name }}</td>
  <td>{{ $modifier_info->m_modifier_description }}</td>
  <td>{{ $modifier_info->m_quantity }}</td>
  <td>
    <a href="#" data-m_id="{{ $modifier_info->m_id }}" id="EDIT_MODIFIER_{{ $modifier_info->m_id }}">
        <i class="fas fa-edit" height="16"></i>
    </a>
  </td>
  <td>
    <a href="#" data-m_id="{{ $modifier_info->m_id }}" id="DELETE_MODIFIER_{{ $modifier_info->m_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
