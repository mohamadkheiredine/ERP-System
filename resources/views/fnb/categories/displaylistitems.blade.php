@foreach($lst_products as $index => $product_info)
<tr class="odd gradeX" data-fi_id="{{ $product_info->fi_id }}">
  <td>
    <input type="checkbox" name="ck_product_{{ $product_info->fi_id }}" id="CK_PRODUCT_{{ $product_info->fi_id }}" class="checkboxes" value="{{ $product_info->fi_id }}" />
  </td>
  <td>{{ $product_info->fi_id }}</td>
  <td>{{ $product_info->fi_item_name }}</td>
  <td>
    <a href="{{ url('/fnb/categories/edititem/' . $product_info->fi_category_id . '/' . $product_info->fi_id) }}" id="EDIT_PRODUCT_{{ $product_info->fi_id }}">
      <i class="fas fa-edit"></i>
    </a>
  </td>
</tr>
@endforeach
