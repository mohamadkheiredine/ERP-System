



@foreach($lst_stock as $index => $si_info)
    <tr  class="odd gradeX" data-is_id="{{ $si_info->p_id }}">
        <td></td>
        <td>{{ $si_info->p_id }}</td>
        <td>{{ $si_info->p_product_name }}</td>
        <td>{{ $si_info->w_warehouse_name }}</td>
        <td>{{ number_format($si_info->total_selling_price * $si_info->total_stock) }}&nbsp;&nbsp;<b>{{ $si_info->cc_currency_code }}</b></td>
        <td>{{ $si_info->total_stock }}</td>
        <td style="width:2px;">   </td>
        <td style="width:2px;"> </td>
    </tr>
@endforeach
