<?php
/***********************************************************
 * listdepreciation.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/3/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



?>


@foreach($lst_asset_depreciations  as $index => $dep_info)
    <tr  class="odd gradeX" data-ad_id="{{ $dep_info->ad_id }}">
        <td><input type="checkbox" name="ck_ad_{{ $dep_info->ad_id }}" id="CK_AD_{{ $dep_info->ad_id }}" class="checkboxes" value="{{ $dep_info->ad_id }}" /></td>
        <td>{{ $dep_info->ad_id }}</td>
        <td>{{ $dep_info->Asset->aa_asset_name }}</td>
        <td>{{ $dep_info->ad_depreciation_amount }}</td>
        <td>{{ $dep_info->ad_new_value }}</td>
        <td><a href="#" data-ad_id="{{ $dep_info->ad_id }}" id="EDIT_DEPR_{{ $dep_info->ad_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-ad_id="{{ $dep_info->ad_id }}" id="DELETE_DEPR_{{ $dep_info->ad_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
