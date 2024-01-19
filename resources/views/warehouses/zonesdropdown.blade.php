<?php
/***********************************************************
zonesdropdown.blade.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 29, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/

?>

 <select data-control="select2" data-placeholder="Select a zone" class="form-select"  name="fk_zone_id" id="FK_ZONE_ID" data-actions-box="true">
        <option value="">&nbsp;&nbsp;</option> 
         @foreach ( $lst_zones as $key => $zone_info )
                <option value="{{ $zone_info->wz_id }}">{{ $zone_info->wz_zone_label }}</option>
        @endforeach
</select>