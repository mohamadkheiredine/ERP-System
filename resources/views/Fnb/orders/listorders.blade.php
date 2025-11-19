@foreach($lst_orders as $index => $order_info)
<tr class="odd gradeX" data-fo="{{ $order_info->fo_id }}">
  <td><input type="checkbox" name="ck_order_{{ $order_info->fo_id }}" id="CK_ORDER_{{ $order_info->fo_id }}" class="checkboxes" value="{{ $order_info->fo_id }}" /></td>
  <td>{{ $order_info->fo_id }}</td>
  <td>{{ $order_info->fo_order_code }}</td>
  <td>{{ $order_info->fo_order_type }}</td>
  <td>{{ $order_info->fo_payment_status }}</td>
  <td>
    <a href="#" data-fo_id="{{ $order_info->fo_id }}" id="EDIT_ORDER_{{ $order_info->fo_id }}">
        <i class="fas fa-edit" height="16"></i>
    </a>
  </td>
  <td>
    <a href="#" data-fo_id="{{ $order_info->fo_id }}" id="DELETE_ORDER_{{ $order_info->fo_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
