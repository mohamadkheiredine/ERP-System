@foreach($lst_menu_items_orders_modifiers as $index => $item_order_modifier_info)
<tr class="odd gradeX" data-im_id="{{ $item_order_modifier_info->im_id }}">
  <td>
    <input type="checkbox" name="ck_item_order_modifier_{{ $item_order_modifier_info->im_id }}" id="CK_ITEM_ORDER_MODIFIER_{{ $item_order_modifier_info->im_id }}" class="checkboxes" value="{{ $item_order_modifier_info->im_id }}" />
  </td>
  <td>{{ $item_order_modifier_info->im_id }}</td>
  <td>{{ $item_order_modifier_info->im_modifier_name }}</td>
  <td>{{ $item_order_modifier_info->im_modifier_type }}</td>
  <td>{{ $item_order_modifier_info->im_modifier_cost }}</td>
  <td>
    <a href="#" data-im_id="{{ $item_order_modifier_info->im_id }}" id="DELETE_ITEM_ORDER_MODIFIER_{{ $item_order_modifier_info->im_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach

