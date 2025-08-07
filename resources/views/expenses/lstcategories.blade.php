



@foreach($lst_expenses_categories as $index => $category_info)
    <tr class="odd gradeX" data-ec_id="{{ $category_info->ec_id }}">
        <td><input type="checkbox" name="ck_ec_{{ $category_info->ec_id }}" id="CK_EC_{{ $category_info->ec_id }}" class="checkboxes" value="{{ $category_info->ec_id }}" /></td>
        <td>{{ $category_info->ec_id }}</td>
        <td>{{ $category_info->ec_name }}</td>
        <td>{{ $category_info->Category ? $category_info->Category->ec_name : "No Parent" }}</td>
        <td>{{ $category_info->ec_max_amount }}&nbsp;<b>{{ $category_info->Currency->cc_currency_code }}</b></td>
        <td><a href="#"  id="EDIT_CATEGORY_{{ $category_info->ec_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" id="DELETE_CATEGORY_{{ $category_info->ec_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
