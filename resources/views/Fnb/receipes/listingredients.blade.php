@foreach($lst_ingredients as $index => $ingredient_info)
    <tr class="ingredient-row" data-in_id="{{ $ingredient_info->in_id }}">

         <td style="width: 220px;">
            <select class="form-select ingredient-select" name="in_item_id[]">
                <option value="">Select ingredient</option>

                @foreach($lst_products as $product)
                    <option value="{{ $product->p_id }}"
                        @if($product->p_id == $ingredient_info->in_product_id) selected @endif>
                        {{ $product->p_product_name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td style="width: 80px;">
            <input type="number" step="0.01" class="form-control qty-input" name="in_stock_quantity" value="{{ $ingredient_info->in_stock_quantity }}">
        </td>

        <td style="width: 100px;">
            <select class="form-select" name="in_unit_of_measure">
            @foreach($lst_units as $index => $unit_info)
                <option @if($unit_info->su_id == $ingredient_info->in_unit_of_measure) selected @endif>
                    {{ $unit_info->su_unit_label }}
                </option>
            @endforeach
            </select>
        </td>

        <td style="width: 70px;">
            <input type="number" step="1" class="form-control waste-input" name="in_waste_percent" value="{{ $ingredient_info->in_waste_percent }}">
        </td>

        <td class="text-end" style="width: 130px;">
            <span class="unit-cost fw-bold">
                {{ $ingredient_info->in_cost_per_unit }}
            </span>
        </td>

        <td class="text-end" style="width: 130px;">
            <span class="line-cost fw-bold">
                {{ $ingredient_info->in_line_cost }}
            </span>
        </td>

        <td style="width: 180px;">
            <input type="text" class="form-control" name="in_notes" value="{{ $ingredient_info->in_notes }}" placeholder="optional">
        </td>

       <td class="text-center delete-ingredient"
            data-in_id="{{ $ingredient_info->in_id }}"
            data-item_id="{{ $ingredient_info->in_item_id }}">
            <button type="button" class="btn btn-light text-danger delete-ingredient-btn">
                <i class="fa fa-trash"></i>
            </button>
        </td>

    </tr>

@endforeach
