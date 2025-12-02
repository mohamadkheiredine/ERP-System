@foreach($lst_products as $index => $item_info)
<tr class="odd gradeX" data-mi_id="{{ $item_info->mi_id }}">
  <td>
    <input type="checkbox" name="ck_item_{{ $item_info->mi_id }}" id="CK_ITEM_{{ $item_info->mi_id }}" class="checkboxes" value="{{ $item_info->mi_id }}" />
  </td>
  <td>{{ $item_info->mi_id }}</td>
  <td>{{ $item_info->mi_item_name }}</td>
  <td>{{ $item_info->mi_base_price }}&nbsp;<b>{{ $item_info->Currency ? $item_info->Currency->cc_currency_code : "" }}</b></td>
  <td>
    <a href="{{ url('/fnb/menuitems/edititem/' . $item_info->mi_id) }}" id="EDIT_ITEM_{{ $item_info->mi_id }}">
      <i class="fas fa-edit"></i>
    </a>
  </td>
  <td>
    <a href="#" data-mi_id="{{ $item_info->mi_id }}" id="DELETE_ITEM_{{ $item_info->mi_id }}">
        <i class="fa fa-minus-circle" aria-hidden="true" height="16"></i>
    </a>
  </td>
</tr>
@endforeach
