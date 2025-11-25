@foreach($lst_menu_items_orders as $index => $item_order_info)
<tr class="odd gradeX" data-oi_id="{{ $item_order_info->oi_id }}">
  <td>
    <input type="checkbox" name="ck_item_order_{{ $item_order_info->oi_id }}" id="CK_ITEM_order_{{ $item_order_info->oi_id }}" class="checkboxes" value="{{ $item_order_info->oi_id }}" />
  </td>
  <td>{{ $item_order_info->oi_id }}</td>
  <td>{{ $item_order_info->oi_quantity }}</td>
  <td>{{ $item_order_info->oi_unit_price }}</td>
  <td>{{ $item_order_info->oi_item_discount }}</td>
  <td>
    <a href="#" data-oi_id="{{ $item_order_info->oi_id }}" id="OPEN_MODIFIERS_ITEM_ORDER_{{ $item_order_info->oi_id }}" data-bs-toggle="modal" data-bs-target="#ModelPopUpModifiers">
        <i class="fas fa-edit"></i>
    </a>
  </td>
  <td>
    <a href="#" data-oi_id="{{ $item_order_info->oi_id }}" id="DELETE_ITEM_ORDER_{{ $item_order_info->oi_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach

