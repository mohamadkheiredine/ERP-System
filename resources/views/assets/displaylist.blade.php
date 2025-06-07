<?php
/***********************************************************
 * displaylist.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/2/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@foreach($lst_assets  as $index => $asset_info)
    <tr  class="odd gradeX" data-aa_id="{{ $asset_info->aa_id }}">
        <td><input type="checkbox" name="ck_aa_{{ $asset_info->aa_id }}" id="CK_AA_{{ $asset_info->aa_id }}" class="checkboxes" value="{{ $asset_info->aa_id }}" /></td>
        <td>{{ $asset_info->aa_id }}</td>
        <td>{{ $asset_info->Category->ac_category_name }}</td>
        <td>{{ $asset_info->Location->il_location_name }}</td>
        <td>{{ $asset_info->aa_asset_name }}</td>
        <td>{{ $asset_info->aa_purchase_price }}</td>
        <td>{{ $asset_info->aa_current_value }}</td>
        <td>{{ $asset_info->Currency->cc_currency_code }}</td>
        <td><a href="#" data-aa_id="{{ $asset_info->aa_id }}" id="EDIT_ASSET_{{ $asset_info->aa_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-aa_id="{{ $asset_info->aa_id }}" id="DELETE_ASSET_{{ $asset_info->aa_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
