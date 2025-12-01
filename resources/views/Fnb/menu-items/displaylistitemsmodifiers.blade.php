@foreach($lst_menu_items_modifiers as $index => $item_modifier_info)
<tr class="odd gradeX" data-im_id="{{ $item_modifier_info->im_id }}">
  <td>
    <input type="checkbox" name="ck_item_modifier_{{ $item_modifier_info->im_id }}" id="CK_ITEM_MODIFIER_{{ $item_modifier_info->im_id }}" class="checkboxes" value="{{ $item_modifier_info->im_id }}" />
  </td>
  <td>{{ $item_modifier_info->im_id }}</td>
  <td>{{ $item_modifier_info->Item->mi_item_name }}</td>
  <td>{{ $item_modifier_info->Modifier->m_modifier_name ?? '—' }}</td>
  <td>{{ $item_modifier_info->im_override_cost }}</td>
  <td>
    <a href="#" data-im_id="{{ $item_modifier_info->im_id }}" id="DELETE_ITEM_MODIFIER_{{ $item_modifier_info->im_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
