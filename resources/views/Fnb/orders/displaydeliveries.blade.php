@foreach($lst_deliveries as $index => $delivery_info)
<tr class="odd gradeX" data-delivery_id="{{ $delivery_info->delivery_id }}">
  <td>
    <input type="checkbox" name="ck_delivery_{{ $delivery_info->delivery_id }}" id="CK_DELIVERY_{{ $delivery_info->delivery_id }}" class="checkboxes" value="{{ $delivery_info->delivery_id }}" />
  </td>
  <td>{{ $delivery_info->od_delivery_address }}</td>
  <td>{{ $delivery_info->od_delivery_cost }}</td>
  <td>
    <a href="#" data-delivery_id="{{ $delivery_info->delivery_id }}" id="DELETE_DELIVERY_{{ $delivery_info->delivery_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach

