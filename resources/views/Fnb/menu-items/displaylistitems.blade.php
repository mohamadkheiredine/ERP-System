@foreach($lst_products as $index => $item_info)
<tr class="odd gradeX" data-fi_id="{{ $item_info->fi_id }}">
  <td>
    <input type="checkbox" name="ck_item_{{ $item_info->fi_id }}" id="CK_ITEM_{{ $item_info->fi_id }}" class="checkboxes" value="{{ $item_info->fi_id }}" />
  </td>
  <td>{{ $item_info->fi_id }}</td>
  <td>{{ $item_info->fi_item_name }}</td>
  <td>{{ $item_info->fi_cost_price }}&nbsp;<b>{{ $item_info->Currency ? $item_info->Currency->cc_currency_code : "" }}</b></td>
  <td>
    <a href="{{ url('/fnb/menuitems/edititem/' . $item_info->fi_id) }}" id="EDIT_ITEM_{{ $item_info->fi_id }}">
      <i class="fas fa-edit"></i>
    </a>
  </td>
  <td>
    <a href="#" data-fi_id="{{ $item_info->fi_id }}" id="DELETE_ITEM_{{ $item_info->fi_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
